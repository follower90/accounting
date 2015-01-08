<?php
require_once 'PHPExcel.php';

class ExportxlsController extends BaseController
{
	protected $params;

	function __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}

	public function indexAction()
	{
		$xls = new PHPExcel();
		$user_id = (int)$_SESSION['user_id'];

		if (file_exists(SITE_PATH . "/tmp/xls/export_" . $user_id . ".xlsx")) {
			unlink(SITE_PATH . "/tmp/xls/export_" . $user_id . ".xlsx");
		}

		$DATA = $this->db->rows("SELECT e.`name`, e.`date`, e.`sum`, cat.`name` as 'catname', 
								DATE_FORMAT(e.`date`, '%Y') as year,  
								DATE_FORMAT(e.`date`, '%m') as month

								FROM `Entry` e 
								LEFT JOIN `Category` cat ON e.`category_id` = cat.`id`
								WHERE e.`user_id`=?
								ORDER BY e.`date` ASC", array($user_id));

		$xls->getProperties()
			->setCreator("Accounting export")
			->setLastModifiedBy("Accounting export")
			->setTitle("Accounting export")
			->setSubject("Accounting export")
			->setDescription("Accounting export")
			->setKeywords("accounting export xls")
			->setCategory("Accounting export XLSX file");

		$YEARS = array();
		$line = 1;
		foreach ($DATA as $r) {
			if (!in_array($r['year'], $YEARS)) {

				$newSheet = $xls->createSheet();
				$newSheet->setTitle($r['year']);

				$xls->getSheetByName($r['year'])->getColumnDimension('A')->setWidth(20);
				$xls->getSheetByName($r['year'])->getColumnDimension('B')->setWidth(16);
				$xls->getSheetByName($r['year'])->getColumnDimension('C')->setWidth(20);
				$xls->getSheetByName($r['year'])->getColumnDimension('D')->setWidth(10);

				array_push($YEARS, $r['year']);
				$line = 1;
			}

			$sh = $xls->getSheetByName($r['year']);

			$sh->setCellValue('A' . $line, $r['name']);
			$sh->setCellValue('B' . $line, $r['date']);
			$sh->setCellValue('C' . $line, $r['catname']);
			$sh->setCellValue('D' . $line, $r['sum']);
			$line++;
		}

		$sheetIndex = $xls->getIndex($xls->getSheetByName('Worksheet'));
		$xls->removeSheetByIndex($sheetIndex);

		$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
		$objWriter->save(SITE_PATH . "/tmp/xls/export_" . $user_id . ".xlsx");
		$saved = true;
		if ($saved) {
			$file = SITE_PATH . "/tmp/xls/export_" . $user_id . ".xlsx";
			header("Content-Type: application/octet-stream");
			header("Accept-Ranges: bytes");
			header("Content-Length: " . filesize($file));
			header("Content-Disposition: attachment; filename=export.xls");
			readfile($file);
		}
	}
}
