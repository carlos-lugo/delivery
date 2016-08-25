<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class User extends DatabaseObject {
	
	protected static $table_name="bike_users";
	protected static $id_field_name="id";
	protected static $db_fields = array('id', 'first_name', 'last_name', 'email', 'password', 'phone', 'level', 'status');
	
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	public $password;
	public $phone;
	public $level;
	public $status;
	
	public function full_name() {
		if(isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	public static function authenticate($email="", $password="") {
		global $database;
		$email = $database->escape_value($email);
		$password = $database->escape_value($password);

		$sql  = "SELECT * FROM bike_users ";
		$sql .= "WHERE email = '{$email}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND status = 'Activo' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function make(
			$id="",
			$first_name="",
			$last_name="",
			$email="",
			$password="",
			$phone="",
			$level="",
			$status=""
	) {
		if(!empty($first_name)) {
			$user = new User();
			if ( $id != "" ) { $user->id = $id; }
			$user->id = $id;
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->email = $email;
			$user->password = $password;
			$user->phone = $phone;
			$user->level = $level;
			$user->status = $status;
			
			return $user;
		} else {
			return false;
		}
	}

}

?>