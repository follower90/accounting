<?php
class Category extends Object
{
	const TYPE_EARNING = '+';
	const TYPE_EXPENSE = '-';

	public static $fields = array(
		'type' => array(
			'type' => 'enum',
			'values' => array(self::TYPE_EARNING, self::TYPE_EXPENSE),
			'default' => self::TYPE_EARNING
		),
		'name' => array(
			'type' => 'varchar'
		)
	);

	public function __construct($id)
	{
		parent::__construct($id);
	}
}
