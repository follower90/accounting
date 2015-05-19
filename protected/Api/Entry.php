<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Authorize;
use Core\Api as Api;

class Entry extends Api
{

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();
	}

	public function methodGet($args)
	{
		if ($entry = Orm::load('Entry', $args['id'])) {
			$result = $entry->getValues();
			$result['cat'] = Orm::load('Category', $entry->getValue('category_id'))->getValue('name');

			return $result;
		}

		return false;
	}

	public function methodSave($args)
	{
		$this->db = \Core\Database\PDO::getInstance();

		if ($id = $this->user->getId()) {

			if ($args['id']) {
				$this->db->query("
						UPDATE `Entry` set date=?, name=?, category_id=?, sum=?
						WHERE id=? and user_id=?",
					[date("Y-m-d", strtotime($args['date'])), $args['name'], $args['cat'], $args['sum'], $args['id'], $id]
				);
			} else {
				$args['id'] = $this->db->insert_id("
					INSERT INTO `Entry` set date=?, name=?, category_id=?, sum=?, user_id=?",
					[date("Y-m-d", strtotime($args['date'])), $args['name'], $args['cat'], $args['sum'], $id]
				);
			}

			$data = $this->db->row("
					SELECT e.*, cat.name as type, cat.type as d
					FROM `Entry` e
					LEFT JOIN `Category` cat ON e.category_id = cat.id

					WHERE e.id = ?
					AND e.user_id = ?",
				[$args['id'], $id]);

			return $data;
		}

		return false;
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

	public function methodDelete($args)
	{
		if ($entry = Orm::findOne('Entry', ['id', 'user_id'], [$args['id'], $this->user->getId()])) {
			Orm::delete($entry);
			return true;
		}
	}
}
