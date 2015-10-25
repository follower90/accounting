<?php

namespace Accounting\Controller;

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
		$vars['user'] = Orm::load('User', $this->user->getId())->getValues();

		$vars['categories'] = Orm::find('Category', [], [], ['sort' => ['id', 'asc']])->getHashMap('id', 'name');
		$vars['user_categories'] = Orm::find('Category', ['user.User'], [$this->user->getId()], ['sort' => ['id', 'asc']])->getValues('id');

		$data['content'] = $this->view->render('public/templates/profile.phtml', $vars);
		return $this->render($data);
	}

	public function methodSave()
	{
		$password = trim($this->request('password'));

		$user = User::find($this->user->getId());
		$user->setValues([
			'name' => $this->request('name'),
			'login' => $this->request('login'),
		]);

		$user->setValue('categories', $this->request('categories'));

		if ($password != '') {
			$user->setValue('password', md5($password));
		}

		$user->save();
		Router::redirect('/profile');
	}
}
