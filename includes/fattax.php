<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Fattax extends DatabaseObject {
	
	protected static $table_name="bike_fat_tax";
	protected static $id_field_name="id";
	
	protected static $db_fields = array (
		'id',
		'tax'
	);
	
	public $id;
	public $tax;
	
	public static function make(
			$id="",
			$tax=""
	) {
		if(!empty($tax)) {
			$tax_obj = new Fattax();
			if ( $id != "" ) { $tax->id = $id; }
			$tax_obj->tax = $tax;
			
			return $tax_obj;
		} else {
			return false;
		}
	}
	
}

?>