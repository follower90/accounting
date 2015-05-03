<?php

class CategoryController extends BaseController {

	protected $params;

    function __construct ($registry, $params)
	{
        $this->registry = $registry;
        parent::__construct($registry, $params);
    }

	public function indexAction()
	{
		$vars = array();

		$view = new View($this->registry);
		$settings = Registry::get('user_settings');

		$user_id = (int)$_SESSION['user_id'];
		$category = (int)$this->params['category'];


		if(isset($_GET['month']))
		{
			$month = (int)$_GET['month'];
			$year = (int)$_GET['year'];

			$from = $year.'-'.$month.'-01';
   			$to = $year.'-'.$month.'-31';
		}
		elseif(isset($_GET['year']))
		{
			$year = (int)$_GET['year'];

			$from = $year.'-01-01';
   			$to = $year.'-12-31';
		}


		$vars['catname'] = $this->db->cell("SELECT name FROM categories WHERE id=?",array($category));

		if(isset($from, $to))
		{
			$sumByCat = $this->db->rows("SELECT SUM(e.sum) as sum, 
										DATE_FORMAT(e.date, '%Y') as year, 
										DATE_FORMAT(e.date, '%m') as month,
										DATE_FORMAT(e.date, '%d') as day
										FROM `entries` e
										WHERE 
										e.`category_id` = ? AND 
										e.date BETWEEN ? AND ? 
										AND e.user_id = ?
										GROUP BY e.id",array($category, $from, $to, $user_id));

			$vars['piedata'] = $this->toJSON($sumByCat);
		}

		$data['content'] = $view->Render('category.phtml', $vars);

		return $this->Render($data);
	}


	protected function toJSON($array)
	{		
		$res = '[';
		$first = true;

		foreach ($array as $val) 
		{
				if(!$first) $res .=', '; 
				$first = false;
				$date = strtotime($val['year'].'-'.$val['month'].'-'.$val['day'])*1000;
				$res .= '['.$date.', '.$val['sum'].']';
		}

		$res .= ']';
		return $res;
	}
}