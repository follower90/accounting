<?php

class ApiController extends BaseController
{

	protected $params;
	protected $db;

	function  __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);

	}

	public function indexAction()
	{
		/* API router */

		$action = $this->params['action'];

		/* Show list of entries  */

		if ($action == 'list' && $this->params['api'] == 'types') {
			$this->showAllTypes();
		} elseif ($action == 'list') {
			$this->showEntries((int)$_POST['user_id']);
		} elseif ($action == 'view') {


			switch ($_SERVER['REQUEST_METHOD']) {

				case 'DELETE':
					$this->deleteEntry((int)$this->params['id']);
					break;

				case 'PUT':
					$this->updateEntry((int)$_POST['id']);
					break;

				case 'POST':
					if (isset($_POST['id'], $_POST['user_id'])) {
						$this->showEntry((int)$_POST['user_id'], (int)$_POST['id']);
					} else
						$this->newEntry();
					break;
			}
		}

	}

	private function showEntries($user_id)
	{

		if (isset($_GET['month'], $_GET['year'])) {
			$entries = $this->getEntries($user_id, (int)$_GET['month'], (int)$_GET['year']);
		} else {
			$entries = $this->getEntries($user_id);
		}

		$this->sendResponse($entries);
	}

	private function showAllTypes()
	{

		$types = $this->db->rows("SELECT * FROM `Category`");

		$this->sendResponse($types);

	}

	private function showEntry($user_id, $entry_id)
	{

		$entry = $this->db->row("
        		SELECT e.*, cat.name as type, cat.type as d
        		FROM `Entry` e
        		LEFT JOIN `Category` cat ON e.category_id = cat.id
        		WHERE e.`user_id`=? AND e.`id`=?", array($user_id, $entry_id));

		if (!$entry) $entry = array();
		$this->sendResponse($entry);
	}

	private function newEntry()
	{
		$inputString = file_get_contents('php://input');
		$data = json_decode($inputString);

		$this->db->query("INSERT INTO `Entry` SET `name`=?, `sum`=?, `date`=?, `category_id`=?",
			array($data->name, $data->sum, $data->date, $data->category_id));

		$response = array("success" => "true");
		$this->sendResponse($response);
	}

	private function sendResponse($array = array())
	{
		$view = new View($this->registry);
		echo $view->Render("empty.phtml", $array);
	}

	private function updateEntry()
	{
		$inputString = file_get_contents('php://input');
		$data = json_decode($inputString);

		$this->db->query("UPDATE `Entry` SET `name`=?, `sum`=?, `date`=?, `category_id`=? WHERE `id`=?",
			array($data->name, $data->sum, $data->date, $data->category_id, $data->id));

		$response = array("success" => "true");
		$this->sendResponse($response);
	}

	private function deleteEntry($id)
	{

		$this->db->query("DELETE FROM `Entry` WHERE `id`=?", array($id));

		$response = array("success" => "true");
		$this->sendResponse($response);
	}

	private function getEntries($user_id, $month = '', $year = '')
	{
	echo '123';
		ini_set('display_errors', true);
		
		$dateStart = date("Y-m-d", strtotime(' -1 week'));
		$dateEnd = date("Y-m-d");

		if ($month != '' && $year != '') {
			$dateStart = date("Y-m-d", strtotime($year . '-' . $month . '-01'));
			$dateEnd = date("Y-m-d", strtotime($year . '-' . $month . '-31'));
		}

		$query = "
        		SELECT e.*, cat.name as type, cat.type as d
        		FROM `Entry` e
        		LEFT JOIN `Category` cat ON e.category_id = cat.id

        		WHERE e.date BETWEEN ? AND ? 
        		AND e.user_id = ?
        		ORDER BY e.id DESC";

		$params = array($dateStart, $dateEnd, $user_id);

		return $this->db->rows($query, $params);
	}
}
