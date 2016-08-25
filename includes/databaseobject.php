<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class DatabaseObject {

	// Problema: cuando se desea heredar funciones de otra clase, no es posible correr las funciones de la clase padre usando los parámetros de la clase hijo, porque ya se ha definido desde un principio que "self" se va a referir a la clase donde está escrita, en este caso la clase padre.
	// Solución: en vez de usar "self" se coloca "static", para especificar que lo que se quiere es un late static binding, con esto es posible ejecutar las funciones de la clase padre usando parámetros de la clase hijo. Luego si se quiere el nombre de la clase hijo, se llama con get_called_class(), esto es necesario al momento de instanciar un nuevo objeto, ya no se usa $object = new self; sino que se usa $object = new get_called_class();
	
	// Common Database Methods
	public static function find_all() {
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}

	public static function find_all_order_by($order_column) {
		return static::find_by_sql("SELECT * FROM ".static::$table_name." ORDER BY {$order_column}");
	}

	public static function find_by_id($id=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_what($column='id', $value=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE {$column}={$value} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_all_by_what($column='id', $value=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE {$column}={$value}");
		return !empty($result_array) ? $result_array : false;
	}

	public static function find_by_two_params($column1='id', $value1=0, $column2, $value2=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE {$column1}={$value1} AND {$column2}={$value2}");
		return !empty($result_array) ? $result_array : false;
	}

	public static function find_all_from_day($column, $year, $month, $day) {
		$value = $year.'-'.$month.'-'.$day;
		$date = new DateTime($value);
		$value1 = $date->format("'Y-m-d H:i:s'");
		$date->modify('+1 day');
		$value2 = $date->format("'Y-m-d H:i:s'");
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE {$column} BETWEEN {$value1} AND {$value2}");
		return !empty($result_array) ? $result_array : false;
	}

	public static function find_all_from_month($column, $year, $month) {
		$value = $year.'-'.$month.'-01';
		$date = new DateTime($value);
		$value1 = $date->format("'Y-m-d H:i:s'");
		$date->modify('+1 month');
		$value2 = $date->format("'Y-m-d H:i:s'");
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE {$column} BETWEEN {$value1} AND {$value2}");
		return !empty($result_array) ? $result_array : false;
	}

	public function update_one_by_what($target_id, $column, $value) {
		global $database;
			// Don't forget your SQL syntax and good habits:
			// - UPDATE table SET key='value', key='value' WHERE condition
			// - single-quotes around all values
			// - escape all values to prevent SQL injection
		//$sanitized_value = escape_value($value);
		$sql = "UPDATE ".static::$table_name." SET {$column}='{$value}'";
		$sql .= " WHERE ".static::$id_field_name."=".$target_id;
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}

	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}

	private static function instantiate($record) {
		// Could check that $record exists and is an array
		$class_name = get_called_class();
		$object = new $class_name;

		// Simple, long-form approach:
		// $object->id 				= $record['id'];
		// $object->username 	= $record['username'];
		// $object->password 	= $record['password'];
		// $object->first_name = $record['first_name'];
		// $object->last_name 	= $record['last_name'];
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
		return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	protected function sanitized_attributes() {
		global $database;
		$clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}

	public function save() {
	  // A new record won't have an id yet.
		if ($this->id == '') { $this->id = null; }
		return isset($this->id) ? $this->update() : $this->create();
	}

	public function create() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$sql  = "INSERT INTO ".static::$table_name." (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= ") VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .= "')";
	if($database->query($sql)) {
		$this->id = $database->insert_id();
		return true;
	} else {
		return false;
	}
}

public function update() {
	global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
	$attributes = $this->sanitized_attributes();
	$attribute_pairs = array();
	foreach($attributes as $key => $value) {
		$attribute_pairs[] = "{$key}='{$value}'";
	}
	$sql = "UPDATE ".static::$table_name." SET ";
	$sql .= join(", ", $attribute_pairs);
	$sql .= " WHERE ".static::$id_field_name."=". $database->escape_value($this->id);
	$database->query($sql);
	return ($database->affected_rows() == 1) ? true : false;
}

public function delete() {
	global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
	$sql  = "DELETE FROM ".static::$table_name;
	$sql .= " WHERE ".static::$id_field_name."=". $database->escape_value($this->id);
	$sql .= " LIMIT 1";
	$database->query($sql);
	return ($database->affected_rows() == 1) ? true : false;

		// NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() 
		// after calling $user->delete().
}

public function delete_by_what($column='id', $id=0) {
	global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
	$sql  = "DELETE FROM ".static::$table_name;
	$sql .= " WHERE {$column}={$id}";
	$sql .= " LIMIT 1";
	$database->query($sql);
	return ($database->affected_rows() == 1) ? true : false;
}

}

