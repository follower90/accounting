<?php

class AjaxController extends BaseController{
	
	function __construct ($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}

	function indexAction()
	{
	}

	function deleteEntryAction() 
	{
		$id = (int)$_POST['id'];
		$user_id = $_SESSION['user_id'];

		$try = $this->db->rows("SELECT `id` FROM `entries` WHERE `id` =? AND `user_id`= ?", array($id, $user_id));
		if(count($try) > 0 )
		{
			$this->db->query("DELETE FROM `entries` WHERE id=? AND `user_id`= ?", array($id, $user_id));
			echo 'success';

		}
	}

	function editshowAction()
	{
		$id = (int)$_POST['id'];
		$user_id = $_SESSION['user_id'];

		$entry = $this->db->row("SELECT tb.*, cat.`name` as catname, cat.`type` FROM `entries` tb 
									LEFT JOIN `categories` cat on cat.id = tb.category_id
										WHERE tb.`id` =? AND tb.`user_id`= ?
									", array($id, $user_id));

		if($entry['id'] !='')
		{
			$result['name'] = html_entity_decode($entry['name']);
			$result['category'] = $entry['category_id'];
			$result['sum'] = $entry['sum'];
			$result['date'] =  date("d.m.Y", strtotime($entry['date']));
			$result['type'] = $entry['type'];
			$result['cat'] = $entry['catname'];
			echo json_encode($result);
		}
	}

	function editsaveAction()
	{
		$id = $_POST['id'];
		$name = $_POST['name'];
		$sum = $_POST['sum'];
		$_date = explode('.', $_POST['date']);
		$date = $_date[2].'-'.$_date[1].'-'.$_date[0];

		$cat = $_POST['cat'];
		$user_id = $_SESSION['user_id'];

		$try = $this->db->row("SELECT `id` FROM `entries` WHERE `id` =? AND `user_id`= ?", array($id, $user_id));

		if($try['id'] != '')
		{
			$this->db->query("UPDATE `entries` SET `name`=?, `date`=?, `category_id`=?, `sum`=? WHERE `id`=? AND `user_id`=?", array($name, $date, $cat, $sum, $id, $user_id));
			echo 'success';
		}
	}
}
?>