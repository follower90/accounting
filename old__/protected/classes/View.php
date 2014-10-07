<?php
	class View
	{
		private $registry;
		public $tplDir = null;
		public $resources = null;
		public $vars = null;

		function __construct ($registry, $vars = array())
		{
			$this->vars = $vars;
			$this->registry = $registry;
			$tplSettings = $this->registry['tpl_settings'];//echo var_dump($tplSettings)."<br />";
			$this->resources = array();
			$this->resources['image'] = $tplSettings['images'];	
			$this->resources['styles'] = $tplSettings['styles'];
			$this->resources['scripts'] = $tplSettings['jscripts'];
			$this->tplDir = $tplSettings['source'];
		}
		
		public function Render($includeFile, $vars = '')
		{
			//$vars['module']="";
			if(isset($vars['module'])&&$vars['module']!="")
			{
				$pathTpl = MODULES.$vars['module']."/".$this->tplDir.$includeFile;//echo $pathTpl."<br />";
				if (!file_exists($pathTpl))
				{
					LOG::echoLog('Could not found template \'' . $pathTpl . '\' !!!');
					return;
				}
			}
			elseif(isset($this->registry['admin']))
			{
				$pathTpl = $this->tplDir."admin/".$includeFile;//echo $pathTpl."<br />";
				if(!file_exists($pathTpl))
				{
					$vars['action'] = $this->registry['admin'];
					$pathTpl = MODULES.$this->registry['admin']."/admin"."/".$this->tplDir.$includeFile;//echo $pathTpl."<br />";
					if(!file_exists($pathTpl))
					{
						LOG::echoLog('Could not found template \'' . $pathTpl . '\' !!!');
						return;
					}
				}
			}
			else{
				$theme = $this->registry['theme'];
				$pathTpl = $this->tplDir.$theme."/".$includeFile;//echo $pathTpl."<br />";
				if(!file_exists($pathTpl))
				{
					LOG::echoLog('Could not found template \'' . $pathTpl . '\' !!!');
					return;
				}
			}
            ob_start();
			//echo $pathTpl;
			 $pos = strpos($pathTpl, "home");
       		 if($pos === false)require SITE_PATH.'/'.$pathTpl;
			 else require $pathTpl;

			$contents = ob_get_contents();
        	ob_end_clean();
	       	return $contents;
		}
		
		public function LoadProdImage($id) {
  			$pathOrig = $this->resources['prodimage'] . $id . '/main.jpg';
  			$cacheID = md5($pathOrig);
			$cache = new Cache ();
   			if (!$path = $cache->LoadImage($cacheID)){
                 $path = $cache->SaveImage($pathOrig, $cacheID);
   			}
			return $path;
		}

		public function LoadProdImageFut($id, $type, $color, $size) {
            $pathOrig = $this->resources['prodimage'] . $id . '/'.$type.'/'.$color.'_'.$size.'.jpg';
  			$cacheID = md5($pathOrig);
			$cache = new Cache ();
   			if (!$path = $cache->LoadImage($cacheID)){
                 $path = $cache->SaveImage ($pathOrig,$cacheID);
   			}
			return $path;
		}

        public function LoadImgage($fileName)
		{
			$pathToResource = $this->resources['image'] . $fileName;
			if (!file_exists($pathToResource)) {
				LOG::echoLog ('Could not found resource \'' . $pathToResource . '\' (resource type \'' . $id_resource . '\')');
				return;
			}
			return '/' . $pathToResource;
        }

		public function LoadResource($id_resource, $fileName, $admin='')
		{
			if($admin=='')
			{
				$path1 = $this->tplDir.$this->registry['theme']."/".$this->resources[$id_resource].$fileName;
				$path2 = $this->resources[$id_resource].$fileName;
				if(file_exists($path1))
				{
					return $this->typeResource($id_resource, $path1);
				}
				elseif(file_exists($path2))
				{
					return $this->typeResource($id_resource, $path2);
				}
				else{
					LOG::echoLog ('Could not found resource \'' . $path1 . '\' (resource type \'' . $id_resource . '\')');
					return false;
				}
			}
			else{
				$path1 = $this->tplDir."admin/".$this->resources[$id_resource].$fileName;
				if(file_exists($path1))
				{
					return $this->typeResource($id_resource, $path1);
				}
				else{
					LOG::echoLog ('Could not found resource \'' . $path1 . '\' (resource type \'' . $id_resource . '\')');
					return false;
				}
			}
		}
		
		public function typeResource($type, $path)
		{
			if($type=="styles")return '<link rel="stylesheet" type="text/css" href="/'.$path.'" />';
			elseif($type=="scripts")return '<script type="text/javascript" src="/'.$path.'"></script>';
			elseif($type=="image")return '<link rel="shortcut icon"  href="/'.$path.'" />';
			else return'/'.$path;
		}
		
		public function Load($array, $type, $admin='')
		{
			$data='';
			if(count($array)>0)
			{
				$data = "\n";
				if($type=="styles")
				{
					for ($i=0;$i<count($array);$i++)
					{
						$data.= $this->LoadResource('styles', $array[$i], $admin)."\n";
					} 
				}
				else{
					for ($i=0;$i<count($array);$i++)
					{
						$data.= $this->LoadResource('scripts', $array[$i], $admin)."\n";		
					}
				}
			}
			return $data;
		}

		function date_view($str, $format="dd-mm-yy")
		{
			$dd = substr($str, 8, 2);

			$mm = substr($str, 5, 2);
			$MM = $this->getMonth($mm);
			$YY = substr($str, 0, 4);
			$yy = substr($str, 2, 2);
			$hh = substr($str, 11, 2);
			$ii = substr($str, 14, 2);
			$ss = substr($str, 17, 2);
            $DD = $this->getDay(mktime(0, 0, 0, $mm, $dd, $YY));
			$replace = array('YY'=>$YY, 'yy'=>$yy, 'mm'=>$mm, 'dd'=>$dd, 'DD'=>$DD, 'hh'=>$hh, 'ii'=>$ii, 'ss'=>$ss, 'MM'=>$MM);
			$str = strtr($format, $replace);
			return $str;
		}
		
		public function getMonth($month)
		{
			switch($month)
			{
				case "01":$month='Январь';break;
				case "02":$month='Февраль'; break;
				case "03":$month='Март';break;
				case "04":$month='Апрель';break;
				case "05":$month='Май'; break;
				case "06":$month='Июнь';break;
				case "07":$month='Июль';break;
				case "08":$month='Август';break;
				case "09":$month='Сентябрь';break;
				case "10";$month='Октябрь'; break;
				case "11":$month='Ноябрь';break;
				case "12": $month='Декабрь';break;
			}
			return $month;	
		}

        public function getDay($day)
        {
            $day=getdate($day);
            switch($day['wday'])
            {
                case "1":$day='Понидельник';break;
                case "2":$day='Вторник'; break;
                case "3":$day='Среда';break;
                case "4":$day='Четверг';break;
                case "5":$day='Пятница'; break;
                case "6":$day='Суббота';break;
                case "0":$day='Воскресенье';break;
            }
            return $day;
        }

        public function get_page_link($page, $cur_page, $var, $text='')
        {
            if (!$text)$text = $page;
            if ($page!=$cur_page)
            {
                $path=$_SERVER['REQUEST_URI'];
                //$reg = '/((\/|^)'.$var.'\/)[^&#]*/';
                //$url = ( preg_match( $reg, $path ) ? preg_replace($reg, '${1}', $path) : ( $path ? $path.'/' : '' ).$var.'/');
                $reg = '/((\/|^)'.$var.'\/)[^\/#]*/';
                $url = ( preg_match( $reg, $path ) ? preg_replace($reg, '${1}'.$page, $path) : ($path? $path.'/' : '' ).$var.'/'.$page);
                //echo "<br />{$url2}<br /><br />";
                $url=str_replace("//", "/", $url);
                return '<a href="'.$url.'">'.$text.'</a>';
            }
            return '<span>'.$text.'</span>';
        }
        public function getUrl2($var)
        {
            $reg = '/'.$var.'\/[a-z0-9]+/';
            $url = preg_replace($reg, '', $_SERVER['REQUEST_URI']);
            $url=str_replace("//", "/", $url);
            return $url;
        }
        public function getUrl($var, $page='')
        {
            $path=$_SERVER['REQUEST_URI'];
            //$reg = '/((\/|^)'.$var.'\/)[^&#]*/';
            //$url = ( preg_match( $reg, $path ) ? preg_replace($reg, '${1}', $path) : ( $path ? $path.'/' : '' ).$var.'/');
            $reg = '/((\/|^)'.$var.'\/)[^\/#]*/';
            $url = ( preg_match( $reg, $path ) ? preg_replace($reg, '${1}'.$page, $path) : ($path? $path.'/' : '' ).$var.'/'.$page);
            //echo "<br />{$url2}<br /><br />";
            $url=str_replace("//", "/", $url);
            return $url;
        }

	}
?>