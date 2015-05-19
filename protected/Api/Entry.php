<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Authorize;
use Core\Api as Api;
use Core\OrmMapper;

class Entry extends Api
{

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();
	}

	public function methodGet($args)
	{
		if (isset($args['id'])) {

			$mapper = OrmMapper::create('Entry');
			$mapper
				->setFilter(['id'], [$args['id']])
				->setFields(['id', 'name', 'date', 'sum'])
				->setFields(['category.type', 'category.name'])
				->setLimit(1);

			return $mapper->getDataMap();
		}

		return false;
	}

	public function methodSave($args)
	{
		if ($userId = $this->user->getId()) {

			if ($args['id']) {
				$entry = Orm::load('Entry', $args['id']);
			} else {
				$entry = Orm::create('Entry');
			}

			$entry->setValues([
				'date' => date('Y-m-d', strtotime($args['date'])),
				'name' => $args['name'],
				'category_id' => $args['cat'],
				'sum' => $args['sum'],
				'user_id' => $userId
			]);

			Orm::save($entry);

			return ['success' => true];
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
