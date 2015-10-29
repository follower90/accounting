<?php

namespace Accounting\Controller;

use Accounting\Object\Category;
use Core\Router;

/*
 * todo
 * Refacor it ASAP
 * Govnokod
 */
class Index extends Base
{
	public function methodIndex()
	{
		$data = [];
		$data['content'] = '';

		if (!$this->user) {
			return false;
		}

		$vars['categories'] = Category::all()
			->addFilter('user.User', $this->user->getId())
			->setSorting('id')
			->load()
			->getHashMap('id', 'name');

		$vars['new_entry'] = $this->view->render('public/templates/new_entry.phtml', $vars);

		$month = $this->request('month');
		$year = $this->request('year');

		if ($month && $year) {
			$dateFrom = $year . '-' . $month . '-01';
			$dateTo = $year . '-' . $month . '-31';
			$vars['archiveDate'] = \Core\Library\Date::getMonth($month) . ' ' . $year;
		} else {
			$dateFrom = date("Y-m") . '-01';
			$dateTo = date("Y-m") . '-31';
		}

		$entries = $this->execute('Accounting.Api.Entry:list', ['from' => $dateFrom, 'to' => $dateTo]);
		$vars['entries_table'] = $this->view->render('public/templates/entries_table.phtml', ['entries' => $entries, 'categories' => $vars['categories']]);

		$vars['getThisMonth'] = $this->monthSummary($entries, '+');
		$vars['spentThisMonth'] = $this->monthSummary($entries, '-');

		$vars['statSpentYear'] = $this->resultsByYear('-');
		$vars['statGotYear'] = $this->resultsByYear('+');

		$vars['catstats'] = $this->stats($dateFrom, $dateTo);
		$vars['best'] = $this->bestMonth();

		$stats = $this->getStats();

		$vars['statGot'] = $stats['got'];
		$vars['statSpent'] = $stats['spent'];

		$vars['statSpentJSON'] = $this->toJSON($stats['spentRaw']);
		$vars['statGotJSON'] = $this->toJSON($stats['gotRaw']);

		$data['content'] = $this->view->render('public/templates/main.phtml', $vars);

		return $this->render($data);
	}

	public function methodNewEntry()
	{
		$date = $this->request('date');
		$name = $this->request('name');
		$sum = $this->request('amount');
		$category = $this->request('types');

		if ($date && $name && $sum && $category) {
			\Core\Database\MySQL::insert('Entry', [
				'name' => $name,
				'user_id' => $this->user->getId(),
				'category_id' => $category,
				'date' => date("Y-m-d", strtotime($date)),
				'sum' => floatval($sum),
			]);
		}

		Router::redirect('/');
	}

	protected function monthSummary($entries, $type)
	{
		$sum = 0;

		array_walk($entries, function (&$entry) use (&$sum, $type) {
			if ($entry['category']['type'] == $type) {
				$sum += $entry['sum'];
			}
		});

		return $sum;
	}

	protected function bestMonth()
	{
		$this->db = \Core\Database\PDO::getInstance();

		$best = $this->db->rows("
			SELECT SUM(e.sum) as sum
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id
			WHERE cat.type='+' AND e.user_id = ?
			GROUP BY DATE_FORMAT(e.date, '%Y%m')
			ORDER BY sum DESC LIMIT 1", array($this->user->getId()));

		return (count($best) >= 1) ? $best[0] : 0;
	}

	protected function stats($from, $to)
	{
		$this->db = \Core\Database\PDO::getInstance();

		return $this->db->rows("
			SELECT SUM(e.sum) as sum,
			e.category_id as category,
			cat.name as catname

			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id
			WHERE e.user_id = ? AND e.date BETWEEN ? AND ?
			AND e.category_id NOT IN (?, ?)
			GROUP BY e.category_id
			ORDER BY e.category_id, e.date ASC", array($this->user->getId(), $from, $to, 6, 7));
	}

	protected function resultsByYear($type)
	{
		$this->db = \Core\Database\PDO::getInstance();
		$res = array();

		$statSpentYear = $this->db->rows("
			SELECT DATE_FORMAT(e.date, '%Y') as year, SUM(e.sum) as sum
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id

			WHERE cat.type='" . $type . "' AND e.user_id = ?
			GROUP BY DATE_FORMAT(e.date, '%Y')", array($this->user->getId()));

		foreach ($statSpentYear as $row) {
			$res[$row['year']] = $row['sum'];
		}

		return $res;
	}

	protected function getStats()
	{
		$this->db = \Core\Database\PDO::getInstance();

		$got = array();
		$spent = array();
		$i = 0;

		$statSpent = $this->db->rows("
			SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id

			WHERE cat.type='-' AND e.user_id = ?
			GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($this->user->getId()));

		$statGot = $this->db->rows("
			SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
			FROM `Entry` e
			LEFT JOIN `Category` cat ON e.category_id = cat.id

			WHERE cat.type='+' AND e.user_id = ?
			GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($this->user->getId()));

		foreach ($statSpent as $row) {
			$row['monthName'] = \Core\Library\Date::getMonth($row['month']);
			$push = false;
			foreach ($statGot as $row2) {
				if ($row['year'] == $row2['year']) {
					if ($row['month'] == $row2['month']) {
						array_push($got, $row2);
						$push = true;
					}
				}
			}

			if (!$push) {
				array_push($got, array('month' => $row['month'], 'monthName' => $row['monthName'], 'year' => $row['year'], 'sum' => 0));
			}

			array_push($spent, $row);
			$i++;
		}

		return array('got' => $got, 'spent' => $spent, 'gotRaw' => $statGot, 'spentRaw' => $statSpent);
	}

	protected function toJSON($array)
	{
		$res = '[';
		$first = true;

		foreach ($array as $val) {
			if (!$first) $res .= ', ';
			$first = false;
			$date = strtotime($val['year'] . '-' . $val['month'] . '-01') * 1000;
			$res .= '[' . $date . ', ' . $val['sum'] . ']';
		}

		$res .= ']';
		return $res;
	}

}
