<?php
class Object
{
	private $_db;
	private $_table;
	private $_object;
	private $_fields;

	public function __construct($id = 0)
	{
		global $db;
		$this->_db =& $db;

		$this->_table = get_called_class();
		$fields = $this->_getFields($this->_table);

		if ((int)$id > 0) {
			$this->_object = $this->_db->row("SELECT $fields FROM `$this->_table` WHERE id=$id");	
		} else {
			foreach ($this->_fields as $key => $row) {
				$this->_object[$key] = $this->_typeConvert($key, $row['default']);
			}
		}
	}

	public function getValue($field)
	{
		if (!isset($this->_object[$field])) {
			return false;
		}

		return $this->_object[$field];
	}

	public function getValues()
	{
		return $this->_object;
	}

	public function setValue($field, $value)
	{
		if (!array_key_exists($field, $this->_object)) {
			return false;
		}

		$this->_object[$field] = $this->_typeConvert($field, $value);
	}

	public function setValues($fields = array(), $values = array())
	{
		for ($i = 0; $i < sizeof($fields); $i++) {
			$this->setValue($fields[$i], $values[$i]);
		}
	}

	public function save()
	{
		foreach ($this->_object as $key => $value) {
			if ($key != 'id') {
				$updateFields[] = $key . "='" . $value."'";
			}
		}
		
		if ($this->_object['id']) {
			$this->_db->query("UPDATE `$this->_table` SET " . implode(', ', $updateFields) . " WHERE id='" . $this->_object['id']. "'");
		} else {
			return $this->_db->insert_id("INSERT INTO `$this->_table` SET " . implode(', ', $updateFields) . " ");
		}
	}

	private function _typeConvert($type, $value)
	{
		$type = $this->_fields[$field]['type'];
		switch ($type) {
			case 'int':
				return (int)$value;
				break;

			case 'float':
				return (float)$value;
				break;

			case 'enum':
				if (!in_array($value, $this->_fields[$field]['values'])) {
					return false;
				}

				return $value;
				break;

			default:
				return (String)$value;
				break;
		}
	}

	private function _getFields($table)
	{
		$this->_fields = $table::$fields;
		foreach ($this->_fields as $key => $value) {
			$fields[] = $key;
		}

		$fields[] = 'id';
		return implode(',', $fields);
	}
}
