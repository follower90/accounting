<?php
class Entry extends Object
{
	public static $fields = array(
		'name' => array(
			'type' => 'varchar',
		),
		'user_id' => array(
			'type' => 'int',
		),
		'category_id' => array(
			'type' => 'int',
		),
		'date' => array(
			'type' => 'date',
		),
		'sum' => array(
			'type' => 'float',
			'default' => 0,
		)
	);

	public function __construct($id = null)
	{
		parent::__construct($id);
	}
}
