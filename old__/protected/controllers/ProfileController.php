<?php

class ProfileController extends BaseController {

	protected $params;

    function __construct ($registry, $params)
	{
        $this->registry = $registry;
        parent::__construct($registry, $params);
    }

	public function indexAction()
	{
		$view = new View($this->registry);
		$settings = Registry::get('user_settings');

		$user_id = (int)$_SESSION['user_id'];

		/*
		* SAVE THE PROFILE
		*/

		if(isset($_POST['save'])) {

			$name = $_POST['name'];
			$login = $_POST['login'];
			$password = trim($_POST['password']);

			$this->db->query("UPDATE `users` SET `name`=?, `login`=? WHERE `id`=?", array($name, $login, $user_id));

			if($password!='') {
				$this->db->query("UPDATE `users` SET `password`=? WHERE `id`=?", array(md5($password), $user_id));
			}

			$this->db->query("DELETE FROM `user_categories` WHERE `user_id`=?", array($user_id));

			foreach ($_POST['categories'] as $row) {
				$this->db->query("INSERT INTO `user_categories` SET `category_id`=?, `user_id`=?", array($row, $user_id));
			}

			$vars['message'] = '<div class="success">Профиль обновлён</div><br/>';

		}


		$vars['user'] = $this->db->row("SELECT `login`,`name` FROM `users` WHERE `id`=?", array($user_id));
		$vars['categories'] = $this->db->rows("SELECT * FROM `categories`");
		$vars['my_categories'] = $this->db->rows("SELECT `category_id` as 'id' FROM `user_categories` WHERE `user_id`=?", array($user_id));
		
		$data['content'] = $view->Render('profile.phtml', $vars);

		return $this->Render($data);
	}

}