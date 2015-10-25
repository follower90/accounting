<?php

namespace Accounting\Api;

use Core\Orm;
use Core\App;
use Core\Api as Api;

class Entry extends Api
{

	public function __construct()
	{
		if (!$this->user = App::getUser()) {
			Api::notAuthorized();
		}
	}

	public function methodGet($args)
	{
		if (isset($args['id'])) {

			$mapper = \Accounting\Object\Entry::all();
			$mapper
				->setFilter(['id'], [$args['id']])
				->setFields(['id', 'name', 'date', 'sum'])
				->setFields(['category.type', 'category.name'])
				->setLimit(1)
				->load();

			return $mapper->getDataMap();
		}

		return false;
	}

	public function methodSave($args)
	{

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
			'user_id' => $this->user->getId()
		]);

		Orm::save($entry);

		return ['success' => true, 'data' => $entry->getValues()];
	}


	public function methodList($args)
	{
		$mapper = \Accounting\Object\Entry::all();
		$mapper
			->setFilter(['user_id', '>date<'], [$this->user->getId(), [$args['from'], $args['to']]])
			->setFields(['id', 'name', 'date', 'sum'])
			->setFields(['category.id', 'category.type', 'category.name'])
			->setSorting('id', 'desc')
			->load();

		return $mapper->getDataMap();
	}

	public function methodDelete($args)
	{
		if ($entry = Orm::findOne('Entry', ['id', 'user_id'], [$args['id'], $this->user->getId()])) {
			Orm::delete($entry);
			return true;
		}
	}
}
