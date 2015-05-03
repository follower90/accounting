<?php

class BaseController
{

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

	protected function getStats($userId)
	{
		$got = array();
		$spent = array();
		$i = 0;

		$statSpent = $this->db->rows("
							SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
							FROM `Entry` e
							LEFT JOIN `Category` cat ON e.category_id = cat.id

							WHERE cat.type='-' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($userId));

		$statGot = $this->db->rows("
							SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
							FROM `Entry` e
							LEFT JOIN `Category` cat ON e.category_id = cat.id

							WHERE cat.type='+' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($userId));

		foreach ($statSpent as $row) {
			$push = false;
			foreach ($statGot as $row2) {
				if($row['year']==$row2['year']) {
					if($row['month']==$row2['month']) {
						array_push($got, $row2);
						$push = true;
					}
				}
			}

			if(!$push) {
				array_push($got, array('month'=>$row['month'],'year'=>$row['year'],'sum'=>0));
			}

			array_push($spent, $row);
			$i++;
		}

		return array ('got' => $got, 'spent' => $spent, 'gotRaw' => $statGot, 'spentRaw' => $statSpent);
	}

	protected function resultsSpentByYear($userId)
	{
		$res = array();
		$statSpentYear = $this->db->rows("
							SELECT DATE_FORMAT(e.date, '%Y') as year, SUM(e.sum) as sum
							FROM `Entry` e
							LEFT JOIN `Category` cat ON e.category_id = cat.id

							WHERE cat.type='-' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y')", array($userId));

		foreach($statSpentYear as $row) {
			$res[$row['year']] = $row['sum'];
		}

		return $res;
	}

	protected function resultsGotByYear($userId)
	{
		$res = array();
		$statGotYear = $this->db->rows("
							SELECT DATE_FORMAT(e.date, '%Y') as year, SUM(e.sum) as sum
							FROM `Entry` e
							LEFT JOIN `Category` cat ON e.category_id = cat.id

							WHERE cat.type='+' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y')", array($userId));

		foreach($statGotYear as $row) {
			$res[$row['year']] = $row['sum'];
		}

		return $res;
	}
}
