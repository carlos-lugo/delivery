<?php

// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Comment extends DatabaseObject {

	protected static $table_name="bike_comments";
	protected static $db_fields=array('id', 'id_user', 'id_order', 'created', 'text', 'status');

	public $id;
	public $id_user;
	public $id_order;
	public $created;
	public $text;
	public $status;

	// "new" is a reserved word so we use "make" (or "build")
	public static function make($id_user, $id_order, $text="", $status='') {
    if(!empty($id_user) && !empty($id_order) && !empty($text)) {
		$comment = new Comment();
	    $comment->id_user = (int)$id_user;
	    $comment->id_order = (int)$id_order;
	    $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
	    $comment->text = $text;
	    $comment->status = $status;
	    return $comment;
		} else {
			return false;
		}
	}
	
	public static function find_comments_on($id_order=0) {
    global $database;
    $sql = "SELECT * FROM " . self::$table_name;
    $sql .= " WHERE id_order=" .$database->escape_value($id_order);
    $sql .= " ORDER BY created ASC";
    return self::find_by_sql($sql);
	}
	
	public function try_to_send_notification() {
		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->Host     = "your.host.com";
		$mail->Port     = 25;
		$mail->SMTPAuth = false;
		$mail->Username = "your_username";
		$mail->Password = "your_password";

		$mail->FromName = "Photo Gallery";
		$mail->From     = "";
		$mail->AddAddress("", "Photo Gallery Admin");
		$mail->Subject  = "New Photo Gallery Comment";
		$created = datetime_to_text($this->created);
		$mail->Body     =<<<EMAILBODY

A new comment has been received in the Photo Gallery.

  At {$created}, {$this->author} wrote:

{$this->body}

EMAILBODY;
		$result = $mail->Send();
		return $result;
		}
	

}

?>