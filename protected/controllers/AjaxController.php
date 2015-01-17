<?php

class AjaxController extends BaseController
{

	function __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}

	function indexAction()
	{
	}

	function deleteEntryAction()
	{
		$id = (int)$_POST['id'];
		$user_id = $_SESSION['user_id'];

		$try = $this->db->rows("SELECT `id` FROM `Entry` WHERE `id` =? AND `user_id`= ?", array($id, $user_id));
		if (count($try) > 0) {
			$this->db->query("DELETE FROM `Entry` WHERE id=? AND `user_id`= ?", array($id, $user_id));
			echo 'success';

		}
	}

	function request()
	{
		$request = file_get_contents('php://input');
		return (array) json_decode($request);
	}

	function loginAction()
	{
		if($this->logined()) {
			return json_encode(array('success' => true));
		}

		return json_encode(array('error' => false));
	}

	function getcurrentmonthAction()
	{
		if($id = $this->logined()) {

			$firstDayMonth = date("Y-m") . '-01';
			$lastDayMonth = date("Y-m") . '-31';

			$data = $this->db->rows("
					SELECT e.*, cat.name as type, cat.type as d
					FROM `Entry` e
					LEFT JOIN `Category` cat ON e.category_id = cat.id

					WHERE e.date BETWEEN ? AND ?
					AND e.user_id = ?
					ORDER BY e.id DESC",
				array($firstDayMonth, $lastDayMonth, $id));

			return json_encode(array('data' => $data));
		}

		return json_encode(array('error' => false));
	}

	function getitemAction()
	{
		$request = $this->request();
		if($id = $this->logined()) {

			$data = $this->db->row("
					SELECT e.*, cat.name as type, cat.type as d
					FROM `Entry` e
					LEFT JOIN `Category` cat ON e.category_id = cat.id

					WHERE e.id = ?
					AND e.user_id = ?
					ORDER BY e.id DESC",
				array($request['id'], $id));

			return json_encode(array('data' => $data));
		}

		return json_encode(array('error' => false));
	}

	function deleteitemAction()
	{
		$request = $this->request();
		if($id = $this->logined()) {
			$this->db->query("DELETE FROM `Entry` WHERE id=? and user_id=?", array($request['id'], $id));
			return json_encode(array('success' => true));
		}

		return json_encode(array('error' => false));
	}

	function saveitemAction()
	{
		$request = $this->request();
		if($id = $this->logined()) {

			if ($request['id']) {
				$this->db->query("
						UPDATE `Entry` set date=?, name=?, category_id=?, sum=?
						WHERE id=? and user_id=?",
					array(
						$request['date'], $request['name'], $request['category'],
						$request['sum'], $request['id'], $id
					)
				);
			} else {
				$request['id'] = $this->db->insert_id("
					INSERT INTO `Entry` set date=?, name=?, category_id=?, sum=?, user_id=?",
					array($request['date'], $request['name'], $request['category'], $request['sum'], $id)
				);
			}

			return json_encode(array('data' => true));
		}

		return json_encode(array('error' => false));
	}

	function getcatsAction()
	{
		if($id = $this->logined()) {
			$data = $this->db->rows("SELECT c.* FROM `Category` c
								 RIGHT JOIN `user_categories` uc ON c.`id` = uc.`category_id`
								 WHERE uc.`user_id`=?
								 ORDER BY c.`name`", array($id));
			return json_encode(array('data' => $data));
		}

		return json_encode(array('error' => false));
	}

	private function logined()
	{
		$request = $this->request();

		if (isset($request['username'], $request['password'])) {
			$row = $this->db->row(
				"SELECT id, name FROM `users` WHERE login=? and password=?",
				array($request['username'], md5($request['password']))
			);

			if ($row) {
				return $row['id'];
			}
		}

		return false;
	}

	function editshowAction()
	{
		$id = (int)$_POST['id'];
		$user_id = $_SESSION['user_id'];

		$entry = $this->db->row("SELECT tb.*, cat.`name` as catname, cat.`type` FROM `Entry` tb 
									LEFT JOIN `Category` cat on cat.id = tb.category_id
										WHERE tb.`id` =? AND tb.`user_id`= ?
									", array($id, $user_id));

		if ($entry['id'] != '') {
			$result['name'] = html_entity_decode($entry['name']);
			$result['category'] = $entry['category_id'];
			$result['sum'] = $entry['sum'];
			$result['date'] = date("d.m.Y", strtotime($entry['date']));
			$result['type'] = $entry['type'];
			$result['cat'] = $entry['catname'];
			echo json_encode($result);
		}
	}

	function editsaveAction()
	{
		$id = $_POST['id'];
		$name = $_POST['name'];
		$sum = $_POST['sum'];
		$_date = explode('.', $_POST['date']);
		$date = $_date[2] . '-' . $_date[1] . '-' . $_date[0];

		$cat = $_POST['cat'];
		$user_id = $_SESSION['user_id'];

		$try = $this->db->row("SELECT `id` FROM `Entry` WHERE `id` =? AND `user_id`= ?", array($id, $user_id));

		if ($try['id'] != '') {
			$this->db->query("UPDATE `Entry` SET `name`=?, `date`=?, `category_id`=?, `sum`=? WHERE `id`=? AND `user_id`=?", array($name, $date, $cat, $sum, $id, $user_id));
			echo 'success';
		}
	}
}
