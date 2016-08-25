<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Shippingfee extends DatabaseObject {
	
	protected static $table_name="bike_shipping_fee";
	protected static $id_field_name="id";
	
	protected static $db_fields = array (
		'id', 
		'id_postal_code', 
		'id_rest', 
		'price'
	);
	
	public $id;
	public $id_postal_code;
	public $id_rest;
	public $price;
	
	public static function make(
			$id="",
			$id_postal_code="",
			$id_rest="",
			$price=""
	) {
		if(!empty($id_postal_code)) {
			$shipping_fee = new Shippingfee();
			if ( $id != "" ) { $shipping_fee->id = $id; }
			$shipping_fee->id = $id;
			$shipping_fee->id_postal_code = $id_postal_code;
			$shipping_fee->id_rest = $id_rest;
			$shipping_fee->price = $price;
			
			return $shipping_fee;
		} else {
			return false;
		}
	}
	
}

?>