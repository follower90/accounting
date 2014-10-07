<?php
class BaseController{
	
	protected $registry;
	protected $params;
	protected $key_lang="ru";

	function  __construct($registry, $params)
	{ 
		global $db;
		$this->db =& $db;
	}

	///////////////////
	public function Render($param = array())
	{
		if(!isset($_SESSION['user_id']) || $_SESSION['user_id']=='')
		{
			if(isset($_COOKIE['userid']) && (int)$_COOKIE['userid']!=0)
			{
				$row = $this->db->row("SELECT id, name FROM `users` WHERE id=?", array($_COOKIE['userid']));
				if($row)
				{
					$_SESSION['user_id'] = $row['id'];
					$_SESSION['name'] = $row['name'];
					setcookie('userid', $row['id'], time()+2592000);
					header("Location: /");
				}
			} else {
				$this->loginAction();
			}
		}

		if(!isset($this->params['topic'])) $param['topic'] = '';
		else $param['topic'] = $this->params['topic'];

		$data = $param;

		$view = new View($this->registry);
		$data['meta']['title'] = 'Бухгалтерия 0.7.0 beta';

		$data['styles'] = $view->Load(array('style.css',
											'bootstrap.min.css',
											'bootstrap-theme.css',
											'datepicker.css'
											,), 'styles');

		$data['scripts'] = $view->Load(array('jquery.min.js',
												'scripts.js',
												'bootstrap.min.js',
												'datepicker.js',
												'jquery.flot.min.js',
												'jquery.flot.canvas.min.js',
												'jquery.flot.time.min.js',
												'jquery.flot.pie.min.js'), 'scripts');

		$data['typesArray'] = $this->getTypes();
		$data['types'] = $this->showTypes($data['typesArray']);
		return ($view->Render("index.phtml", $data));
	}

	public function loginAction()
	{

		if(isset($_POST['name'],$_POST['pass'])) 
		{
			$row = $this->db->row("SELECT id, name FROM `users` WHERE login=? and password=?", array($_POST['name'], md5($_POST['pass'])));
			if($row)
			{
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['name'] = $row['name'];
				setcookie('userid', $row['id'], time()+2592000);
				header("Location: /");
			}
		}
	}

	protected function getTypes()
	{
		$types = $this->db->rows("SELECT c.* FROM `Category` c
								 RIGHT JOIN `user_categories` uc ON c.`id` = uc.`category_id`
								 WHERE uc.`user_id`=?
								 ORDER BY c.`name`", array((int)$_SESSION['user_id']));

		return $types;
	}

	protected function showTypes($types = array())
	{
		$result='';
		foreach ($types as $val) {
			$result .= '<option value="'.$val['id'].'" >'.$val['name'].'</option>';
		}
		return $result;
	}

	protected function getSum($type, $from, $to, $user_id)
	{

		$arr = $this->db->row("
				SELECT SUM(e.sum) as sum
				FROM `Entry` e
				LEFT JOIN `Category` cat ON e.category_id = cat.id


				WHERE e.date BETWEEN ? AND ? AND cat.`type` = ?
				AND e.user_id = ?
		 		ORDER BY e.date DESC",array($from, $to, $type, $user_id));

		return $arr['sum'];
	}

	protected function toJSON($array)
	{		
		$res = '[';
		$first = true;

		foreach ($array as $val) 
		{
				if(!$first) $res .=', '; 
				$first = false;
				$date = strtotime($val['year'].'-'.$val['month'].'-01')*1000;
				$res .= '['.$date.', '.$val['sum'].']';
		}

		$res .= ']';
		return $res;
	}
}