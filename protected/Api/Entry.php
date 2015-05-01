<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Api as Api;

class Entry extends Api
{
	public function methodGet()
	{
		if ($examples = Orm::load('Entry', $this->request['id'])) {
			return static::output($news->getValues());
		}

		return static::output([]);
	}

	public function methodSave()
	{
		/*$request = $this->request();
		if ($id = $this->logined()) {

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

			$data = $this->db->row("
					SELECT e.*, cat.name as type, cat.type as d
					FROM `Entry` e
					LEFT JOIN `Category` cat ON e.category_id = cat.id

					WHERE e.id = ?
					AND e.user_id = ?",
				array($request['id'], $id));

			return json_encode(array('data' => $data));
		}

		return json_encode(array('error' => false));*/
	}


	public function methodList()
	{

		/*
			function getcurrentmonthAction()
	{
		if ($id = $this->logined()) {

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
	*/
		$offset = $this->request['offset'];
		$limit = $this->request['limit'];

		if ($examples = Orm::find('Entry', [], [], ['offset' => $offset, 'limit' => $limit])) {
			return static::output($examples->getData());
		}

		return static::output([]);
	}

	public function methodDelete()
	{
		$id = $this->request['id'];
		$user_id = $_SESSION['user_id'];

		if ($entry = Orm::findOne('Entry', ['id', 'user_id'], [$this->request['id'], $user_id])) {
			Orm::delete($entry);
			return static::output(['success' => true]);
		}
	}
}
