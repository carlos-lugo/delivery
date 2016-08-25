<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Orders extends DatabaseObject {
	
	protected static $table_name="bike_orders";
	protected static $id_field_name="id";
	
	protected static $db_fields = array (
		'id', 
		'id_rest', 
		'street_name', 
		'street_number', 
		'postal_code', 
		'apartment_number', 
		'phone', 
		'reception_time', 
		'accepted_to_time', 
		'register_time', 
		'assigned_time', 
		'estimated_arrival', 
		'finished_time', 
		'id_bike', 
		'status',
		'cost',
		'bike_profit'
	);
	
	public $id;
	public $id_rest;
	public $street_name;
	public $street_number;
	public $postal_code;
	public $apartment_number;
	public $phone;
	public $reception_time;
	public $accepted_to_time;
	public $register_time;
	public $assigned_time;
	public $estimated_arrival;
	public $finished_time;
	public $id_bike;
	public $status;
	public $cost;
	public $bike_profit;
		
	public static function find_comments_on($photo_id=0) {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE photograph_id=" .$database->escape_value($photo_id);
		$sql .= " ORDER BY created ASC";
		return self::find_by_sql($sql);
	}
	
	public static function make(
			$id="",
			$id_rest="",
			$street_name="",
			$street_number="",
			$postal_code="",
			$apartment_number="",
			$phone="",
			$reception_time="",
			$accepted_to_time="",
			$register_time="",
			$assigned_time="",
			$estimated_arrival="",
			$finished_time="",
			$id_bike="",
			$status="",
			$cost="",
			$bike_profit=""
	) {
		if(!empty($street_name)) {
			$orders = new Orders();
			if ( $id != "" ) { $orders->id = $id; }
			$orders->id = $id;
			$orders->id_rest = $id_rest;
			$orders->street_name = $street_name;
			$orders->street_number = $street_number;
			$orders->postal_code = $postal_code;
			$orders->apartment_number = $apartment_number;
			$orders->phone = $phone;
			$orders->reception_time = $reception_time;
			$orders->accepted_to_time = $accepted_to_time;
			$orders->register_time = $register_time;
			$orders->assigned_time = $assigned_time;
			$orders->estimated_arrival = $estimated_arrival;
			$orders->finished_time = $finished_time;
			$orders->id_bike = $id_bike;
			$orders->status = $status;
			$orders->cost = $cost;
			$orders->bike_profit = $bike_profit;
			
			return $orders;
		} else {
			return false;
		}
	}
	
	public static function find_items_on($list_id=0) {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE list_id=" .$database->escape_value($list_id);
		$sql .= " ORDER BY created ASC";
		return self::find_by_sql($sql);
	}
	
	public function comments() {
		return Comment::find_comments_on($this->id);
	}
	
}

?>