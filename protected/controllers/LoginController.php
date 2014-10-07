<?php
class LoginController extends BaseController {
	
	protected $params;
	protected $db;
	
	function  __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}
	
	public function indexAction()
	{
		if(isset($_POST['login'], $_POST['password'])) {

			$login = trim($_POST['login']);
			$password = trim($_POST['password']);
		
			$this->checkLogin($login, $password);
		} else {
			$this->logout();
		}
	}

	private function checkLogin($login, $pass)
	{

		$row = $this->db->row("SELECT id, name FROM `users` WHERE login=? and password=?", array($login, md5($pass)));

		if($row)
		{
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['name'] = $row['name'];
			setcookie('userid', $row['id'], time()+2592000);
		} 
		else
			$row = array();

		$this->sendResponse($row);
	}

	private function logout()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['name']);
		setcookie('userid', '0', time()+2592000);

		$resposne = array('logout'=>'true');
		$this->sendResponse($response);
	}

	private function sendResponse($array = array())
	{
		$view = new View($this->registry);
		echo $view->Render("empty.phtml", $array);
	}
}