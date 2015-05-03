<?php

class IndexController extends BaseController
{
	protected $params;

	function __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}

	function indexAction()
	{
		$view = new View($this->registry);
		$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
		
		if (isset($_GET['month'], $_GET['year'])) {
			$vars['archive'] = true;

			$year = $_GET['year'];
			$month = $_GET['month'];

			$firstDayMonth = $year . '-' . $month . '-01';
			$lastDayMonth = $year . '-' . $month . '-31';
			$vars['thisMonthName'] = $view->getMonth($_GET['month']);
			$DATE = $year . '-' . $month . '-31';
		} else {
			$vars['archive'] = false;

			$firstDayMonth = date("Y-m") . '-01';
			$lastDayMonth = date("Y-m") . '-31';
			$vars['thisMonthName'] = $view->getMonth(date("m"));
			$DATE = date("Y-m-d");
		}

		$vars['thismonth'] = $this->db->rows("
			SELECT e.*, cat.name as type, cat.type as d
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id

			WHERE e.date BETWEEN ? AND ?
			AND e.user_id = ?
			ORDER BY e.id DESC", array($firstDayMonth, $lastDayMonth, $user_id));

		$best = $this->db->rows("
			SELECT SUM(e.sum) as sum
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id
			WHERE cat.type='+' AND e.user_id = ?
			GROUP BY DATE_FORMAT(e.date, '%Y%m')
			ORDER BY sum DESC LIMIT 1", array($user_id));

		if (count($best) >= 1) $vars['best'] = $best[0];
		else $vars['best'] = '0';

		if (isset($_GET['month'])) {
			$month = (int)$_GET['month'];
			$year = (int)$_GET['year'];

			$from = $year . '-' . $month . '-01';
			$to = $year . '-' . $month . '-31';
		} elseif (isset($_GET['year'])) {
			$year = (int)$_GET['year'];

			$from = $year . '-01-01';
			$to = $year . '-12-31';
		} else {
			$from = date("Y-m") . '-01';
			$to = date("Y-m") . '-31';
		}

		$vars['catstats'] = $this->db->rows("
			SELECT SUM(e.sum) as sum,
			e.category_id as category,
			cat.name as catname

			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id
			WHERE e.user_id = ? AND e.date BETWEEN ? AND ?
			AND e.category_id NOT IN (?, ?)
			GROUP BY e.category_id
			ORDER BY e.category_id, e.date ASC", array($user_id, $from, $to, 6, 7));

		$vars['statSpentYear'] = $this->resultsSpentByYear($user_id);
		$vars['statGotYear'] = $this->resultsGotByYear($user_id);

		$stats = $this->getStats($user_id);

		$vars['statGot'] = $stats['got'];
		$vars['statSpent'] = $stats['spent'];

		$vars['statSpentJSON'] = $this->toJSON($stats['spentRaw']);
		$vars['statGotJSON'] = $this->toJSON($stats['gotRaw']);


		if (isset($_POST['AddNew'])) {
			if ($_POST['date'] == '') $vars['error'] .= 'date';
			if ($_POST['name'] == '') $vars['error'] .= 'name';
			if ($_POST['amount'] == '') $vars['error'] .= 'sum';

			if ($vars['error'] == '') {
				$type = $_POST['types'];
				$date = date("Y-m-d", strtotime($_POST['date']));
				$name = $_POST['name'];
				$amount = floatval($_POST['amount']);

				$entry = new Entry();
				$entry->setValues(
					array('name', 'user_id', 'category_id', 'date', 'sum'),
					array($name, $user_id, $type, $date, $amount)
				);

				$entry->save();
				header("Location: / ");
			}
		}

		if (isset($_SESSION['user_id'])) {
			$vars['typesArray'] = $this->getTypes();
			$vars['types'] = $this->showTypes($vars['typesArray']);
		}

		$data['content'] = $view->Render('main.phtml', $vars);
		return $this->Render($data);
	}
}
