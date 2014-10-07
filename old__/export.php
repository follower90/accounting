<?php


define('SITE_PATH', dirname(__FILE__)."/");
define('CLASSES', dirname(__FILE__)."/protected/classes/");
define('CONROLLERS', dirname(__FILE__)."/protected/controllers/");
define('MODULES', dirname(__FILE__)."/protected/modules/");

include_once('protected/config.php');	
include_once('protected/library/library.php');
include_once('protected/classes/Registry.php');
include_once('protected/classes/PDOchild.php');	
include_once('protected/classes/Log.php');
include_once('protected/classes/View.php');



$db = array('host'=> $DB_Host,
			'name'=> $DB_Name,
			'user'=> $DB_UserName,
			'password'=> $DB_Password,
			'charset'=> $DB_Charset);


$tpl = array('source'=>$PathToTemplate,
			 'styles'=>$PathToCSS,
			 'images'=>$PathToImages,
			 'jscripts'=>$PathToJavascripts,
			 'flash'=>$PathToFlash,
			 'tmp'=>$PathToTMP,
			 'dump'=>$PathToDUMP);

$registry = new Registry;

$registry->set('db_settings', $db);
$registry->set('tpl_settings', $tpl);
$registry->set('theme', $theme);
$registry->set('editor', $editor);


class Export {

	private $registry;
	protected $db;

	public function __construct($registry)

	{
		$this->registry = $registry;
		$this->db = new PDOchild($registry);
	}

	public function backup()
	{
		$dbConfig = $this->registry['db_settings'];
		//exec("mysqldump -u{$dbConfig['user']} -p{$dbConfig['password']} {$dbConfig['name']} > output.sql");
	}
}


$export = new Export($registry);
$export->backup();