<?php

namespace Accounting\Controller;

use Accounting\Object\Category;
use Core\Object\User;
use Core\Router;
use Core\Orm;

class Profile extends Base
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->user) {
			Router::redirect('404');
		}
	}

	public function methodIndex()
	{
		$vars['user'] = $this->user->getValues();

		$vars['categories'] = Category::all()
			->setSorting('id')
			->load()
			->getHashMap('id', 'name');

		$vars['user_categories'] = Category::all()
			->addFilter('user.User', $this->user->getId())
			->setSorting('id')
			->load()
			->getValues('id');

		$data['content'] = $this->view->render('public/templates/profile.phtml', $vars);
		return $this->render($data);
	}

	public function methodSave()
	{
		$password = trim($this->request('password'));

		$this->user->setValues([
			'name' => $this->request('name'),
			'login' => $this->request('login'),
		]);

		$this->user->setValue('categories', $this->request('categories'));

		if ($password != '') {
			$this->user->setValue('password', md5($password));
		}

		$this->user->save();
		Router::redirect('/profile');
	}
}
