<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Restaurant extends DatabaseObject {
	
	protected static $table_name="bike_restaurants";
	protected static $id_field_name="id";
	protected static $db_fields = array(
		'id', 
		'name', 
		'street_name', 
		'street_number', 
		'postal_code', 
		'manager', 
		'phone', 
		'email', 
		'status',
		'user',
		'password'
	);
	
	public $id;
	public $name;
	public $street_name;
	public $street_number;
	public $postal_code;
	public $manager;
	public $phone;
	public $email;
	public $status;
	public $user;
	public $password;
	public $first_name;
	public $level;
	
	public static function find_comments_on($photo_id=0) {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE photograph_id=" .$database->escape_value($photo_id);
		$sql .= " ORDER BY created ASC";
		return self::find_by_sql($sql);
	}

	public static function authenticate($user="", $password="") {
		global $database;
		$user = $database->escape_value($user);
		$password = $database->escape_value($password);

		$sql  = "SELECT * FROM bike_restaurants ";
		$sql .= "WHERE user = '{$user}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND status = 'Activo' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public function full_name() {
		if(isset($this->name)) {
			return $this->name;
		} else {
			return "";
		}
	}

	public static function make(
			$id="",
			$name="",
			$street_name="",
			$street_number="",
			$postal_code="",
			$manager="",
			$phone="",
			$email="",
			$status="",
			$user="",
			$password=""
	) {
		if(!empty($street_name)) {
			$restaurant = new Restaurant();
			if ( $id != "" ) { $restaurant->id = $id; }
			$restaurant->id = $id;
			$restaurant->name = $name;
			$restaurant->street_name = $street_name;
			$restaurant->street_number = $street_number;
			$restaurant->postal_code = $postal_code;
			$restaurant->manager = $manager;
			$restaurant->phone = $phone;
			$restaurant->email = $email;
			$restaurant->status = $status;
			$restaurant->user = $user;
			$restaurant->password = $password;
			$restaurant->first_name = $name;
			$restaurant->level = 'Restaurante';
			
			return $restaurant;
		} else {
			return false;
		}
	}

}

?>