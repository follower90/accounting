<?php

namespace Accounting\Controller;

use Core\Authorize;
use Core\Router;
use Core\Orm;

class Profile extends Base
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->authorizer->getUser()) {
			Router::redirect('404');
		}
	}

	public function methodIndex()
	{
		$user = $this->authorizer->getUser();
		$vars['user'] = Orm::load('User', $user->getId())->getValues();

		$vars['categories'] = Orm::find('Category', [], [], ['sort' => ['id', 'asc']])->getHashMap('id', 'name');
		$vars['user_categories'] = Orm::find('Category', ['user.User'], [$user->getId()], ['sort' => ['id', 'asc']])->getValues('id');

		$data['content'] = $this->view->render('public/templates/profile.phtml', $vars);
		return $this->render($data);
	}

	public function methodSave()
	{
		$this->db = \Core\Database\PDO::getInstance();

		if (isset($_POST['save'])) {
			$name = $_POST['name'];
			$login = $_POST['login'];
			$password = trim($_POST['password']);

			$this->db->query("UPDATE `users` SET `name`=?, `login`=? WHERE `id`=?", array($name, $login, $user_id));

			if ($password != '') {
				$this->db->query("UPDATE `users` SET `password`=? WHERE `id`=?", array(md5($password), $user_id));
			}

			$this->db->query("DELETE FROM `user_categories` WHERE `user_id`=?", array($user_id));

			foreach ($_POST['categories'] as $row) {
				$this->db->query("INSERT INTO `user_categories` SET `category_id`=?, `user_id`=?", array($row, $user_id));
			}

			$vars['message'] = '<div class="success">Профиль обновлён</div><br/>';
		}

		$vars['user'] = $this->db->row("SELECT `login`,`name` FROM `users` WHERE `id`=?", array($user_id));
		$vars['categories'] = $this->db->rows("SELECT * FROM `Category`");
		$vars['my_categories'] = $this->db->rows("SELECT `category_id` as 'id' FROM `user_categories` WHERE `user_id`=?", array($user_id));

		Router::redirect('/profile');
	}
}
