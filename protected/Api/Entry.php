<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Api as Api;

class Entry extends Api
{

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();
	}

	public function methodGet()
	{
		if ($entry = Orm::load('Entry', $this->request['id'])) {
			$result = $entry->getValues();
			$result['cat'] = Orm::load('Category', $entry->getValue('category_id'))->getValue('name');

			$this->output($result);
		}

		$this->output([]);
	}

	public function methodSave()
	{

		$this->db = \Core\Database\PDO::getInstance();

		$request = $this->request();

		if ($id = $this->user->getId()) {

			if ($request['id']) {
				$this->db->query("
						UPDATE `Entry` set date=?, name=?, category_id=?, sum=?
						WHERE id=? and user_id=?",
					[date("Y-m-d", strtotime($request['date'])), $request['name'], $request['cat'], $request['sum'], $request['id'], $id]
				);
			} else {
				$request['id'] = $this->db->insert_id("
					INSERT INTO `Entry` set date=?, name=?, category_id=?, sum=?, user_id=?",
					[date("Y-m-d", strtotime($request['date'])), $request['name'], $request['cat'], $request['sum'], $id]
				);
			}

			$data = $this->db->row("
					SELECT e.*, cat.name as type, cat.type as d
					FROM `Entry` e
					LEFT JOIN `Category` cat ON e.category_id = cat.id

					WHERE e.id = ?
					AND e.user_id = ?",
				[$request['id'], $id]);

			$this->output($data);
		}

		$this->output([]);
	}


	public function methodList($args)
	{
		$entries = Orm::find('Entry', ['user_id', '>date<'], [$this->user->getId(), [$args['from'], $args['to']]], ['sort' => ['id', 'desc']])->getData();
		$categories = Orm::find('Category')->getData();

		array_walk($entries, function(&$entry) use (&$categories) {
			$category = $categories[$entry['category_id']];
			$entry['type'] = $category['type'];
			$entry['category'] = $category['name'];
			return $entry;
		});

		return $entries;
	}

	public function methodDelete()
	{
		$id = $this->request('id');

		if ($entry = Orm::findOne('Entry', ['id', 'user_id'], [$id, $this->user->getId()])) {
			Orm::delete($entry);
			$this->output(['success' => true]);
		}
	}
}
