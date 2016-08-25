<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Postalcode extends DatabaseObject {
	
	protected static $table_name="bike_postal_codes";
	protected static $id_field_name="id";
	
	protected static $db_fields = array (
		'id', 
		'postcode'
	);
	
	public $id;
	public $postcode;
	
	public static function make(
			$id="",
			$postcode=""
	) {
		if(!empty($postcode)) {
			$postal_code = new Postalcode();
			if ( $id != "" ) { $postal_code->id = $id; }
			$postal_code->id = $id;
			$postal_code->postcode = $postcode;
			
			return $postal_code;
		} else {
			return false;
		}
	}
	
}

?>