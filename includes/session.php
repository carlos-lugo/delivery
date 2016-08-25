<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally 
// inadvisable to store DB-related objects in sessions

class Session {
	
	private $logged_in=false;
	public $user_id;
	public $first_name;
	public $level;
	public $status;
	public $message;
	
	function __construct() {
		session_start();
		$this->check_message();
		$this->check_login();
		if($this->logged_in) {
      // actions to take right away if user is logged in
		} else {
      // actions to take right away if user is not logged in
		}
	}
	
	public function is_logged_in() {
		return $this->logged_in;
	}

	public function login($user) {
    // database should find user based on username/password
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;   // session obj = $_SESSION = user obj
			$this->logged_in = true;
			$this->first_name = $_SESSION['first_name'] = $user->first_name;
			$this->level = $_SESSION['level'] = $user->level;
			$this->status = $_SESSION['status'] = $user->status;
			if($this->status=='Borrado'){
				unset($this->user_id);
				$this->logged_in = false;
			}
		}
	}
	
	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
		$_SESSION['first_name'] = 'Guest';
	}

	public function message($msg="") {
		if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
			$_SESSION['message'] = $msg;
		} else {
	    // then this is "get message"
			return $this->message;
		}
	}

	private function check_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
			$this->first_name = $_SESSION['first_name'];
			$this->level = $_SESSION['level'];
			$this->status = $_SESSION['status'];

			$user_check = User::find_by_id($this->user_id);
			if($user_check->status=='Borrado'){
				unset($_SESSION['user_id']);
				unset($this->user_id);
				$this->logged_in = false;
			}
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	
	private function check_message() {
		// Is there a message stored in the session?
		if(isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}
	
}

// payment insurance
//$today_date = strftime("%Y-%m-%d", time());
//if ($today_date >= "2016-04-25") { echo ""; die; }

$session = new Session();
$message = $session->message();

?>