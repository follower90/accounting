<?php

	class IndexController extends BaseController {
		protected $params;
	    function __construct ($registry, $params)
		{
	        $this->registry = $registry;
	        parent::__construct($registry, $params);
	    }

		function indexAction()
	    {
	        $view = new View($this->registry);
			$settings = Registry::get('user_settings');

			$user_id = $_SESSION['user_id'];

	        $vars['lastweek'] = $this->db->rows("
	        		SELECT e.*, cat.name as type, cat.type as d
	        		FROM `entries` e
	        		LEFT JOIN `categories` cat ON e.category_id = cat.id

	        		WHERE e.date BETWEEN ? AND ? 
	        		AND e.user_id = ?
	        		ORDER BY e.id DESC",

	        		array(date("Y-m-d", strtotime(' -1 week')), date("Y-m-d"), $user_id));


	        if(isset($_GET['month'], $_GET['year']))
	        {
	        	$vars['archive'] = true;

	        	$year = $_GET['year'];
	        	$month = $_GET['month'];

	        	$firstDayMonth = $year.'-'.$month.'-01';
	        	$lastDayMonth = $year.'-'.$month.'-31';
	        	$vars['thisMonthName'] = $view->getMonth($_GET['month']);
	        	$DATE = $year.'-'.$month.'-31';
	        }
	        else {
	        	$vars['archive'] = false;

		        $firstDayMonth = date("Y-m").'-01';
		        $lastDayMonth = date("Y-m").'-31';
		        $vars['thisMonthName'] = $view->getMonth(date("m"));
		        $DATE = date("Y-m-d");
	    	}

	        $vars['thismonth'] = $this->db->rows("
	        		SELECT e.*, cat.name as type, cat.type as d
	        		FROM `entries` e
	        		LEFT JOIN `categories` cat ON e.category_id = cat.id

	        		WHERE e.date BETWEEN ? AND ?
	        		AND e.user_id = ?
	        		ORDER BY e.id DESC",array($firstDayMonth, $lastDayMonth, $user_id));


	        $vars['spentThisMonth'] = $this->getSum('-', $firstDayMonth, $DATE, $user_id);
	        $vars['getThisMonth'] = $this->getSum('+', $firstDayMonth, $DATE, $user_id);

	        
			$vars['error'] = '';


			$best = $this->db->rows("SELECT SUM(e.sum) as sum
							FROM `entries` e
							LEFT JOIN `categories` cat ON e.category_id = cat.id
							WHERE cat.type='+' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y%m')
							ORDER BY sum DESC LIMIT 1", array($user_id));

			if(count($best)>=1) $vars['best'] = $best[0];
			else $vars['best'] = '0';

			/* STATISTICS */

			//by category

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
			else 
			{
				$from = date("Y-m").'-01';
				$to = date("Y-m").'-31';
			}


			$vars['catstats'] = $this->db->rows("
							SELECT SUM(e.sum) as sum, 
							e.category_id as category,
							cat.name as catname

							FROM `entries` e
							LEFT JOIN `categories` cat ON e.category_id = cat.id
							WHERE e.user_id = ? AND e.date BETWEEN ? AND ?
							AND e.category_id NOT IN (?, ?)
							GROUP BY e.category_id 
							ORDER BY e.category_id, e.date ASC", array($user_id, $from, $to, 6, 7));

			$statSpent = $this->db->rows("
							SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
							FROM `entries` e
							LEFT JOIN `categories` cat ON e.category_id = cat.id

							WHERE cat.type='-' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($user_id));

			$statGot = $this->db->rows("
							SELECT SUM(e.sum) as sum, DATE_FORMAT(e.date, '%Y') as year, DATE_FORMAT(e.date, '%m') as month
							FROM `entries` e
							LEFT JOIN `categories` cat ON e.category_id = cat.id

							WHERE cat.type='+' AND e.user_id = ?
							GROUP BY DATE_FORMAT(e.date, '%Y%m')", array($user_id));


			$vars['statSpent'] = array();
			$vars['statGot'] = array();

			$i = 0;
			foreach ($statSpent as $row) 
			{
				$push = false;
				foreach ($statGot as $row2) 
				{
					if($row['year']==$row2['year'])
					{
						if($row['month']==$row2['month'])
						{
							array_push($vars['statGot'], $row2);
							$push = true;
						}
					}
				}
				if(!$push) {
					array_push($vars['statGot'], array('month'=>$row['month'],'year'=>$row['year'],'sum'=>0));
					$push = false;
				}
				array_push($vars['statSpent'], $row);
				$i++;
			}

			$vars['statSpentJSON'] = $this->toJSON($statSpent);
			$vars['statGotJSON'] = $this->toJSON($statGot);

			

	        // Добавляем новую запись
	        if(isset($_POST['AddNew']))
	    	{
				if($_POST['date']=='') $vars['error'] .= 'date';
				if($_POST['name']=='') $vars['error'] .= 'name';
				if($_POST['amount']=='') $vars['error'] .= 'sum';
				
				if($vars['error']=='')
				{
	    		$type = $_POST['types'];
	    		$date = date("Y-m-d", strtotime($_POST['date']));
	    		$name = $_POST['name'];
	    		$amount = floatval($_POST['amount']);

	    		$this->db->query("INSERT INTO `entries`
	    						SET `name`=?,`user_id`=?,`category_id`=?,`date`=?,`sum`=?",
	    							array($name, $user_id, $type, $date, $amount));
	    			header("Location: / ");	
				}
	    	}

	        $vars['types'] = $this->showTypes();
			$data['content'] = $view->Render('main.phtml', $vars);

			return $this->Render($data);
		}
	}
