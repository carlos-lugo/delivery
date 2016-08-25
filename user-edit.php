<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

if ($_POST['action']=='edit') {
	
	$id = $_POST['id_user'];
	$user = User::find_by_what('id', $id);
	$first_name = $user->first_name;
	$last_name = $user->last_name;
	$email = $user->email;
	$password = $user->password;
	$phone = $user->phone; 
	$level = $user->level;
	$status = $user->status;
			
} elseif ($_POST['action']=='save') {
	
	$id = $_POST['id_user'];
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$phone = trim($_POST['phone']);
	$level = trim($_POST['level']);
	$status = trim($_POST['status']);
	
	$user = User::make(
		$id,
		$first_name,
		$last_name,
		$email,
		$password,
		$phone,
		$level,
		$status
	);
	
	if ($user) {
		if($user->save()) {
			// Success
    		$session->message("User was successfully registered.");
    		redirect_to('user.php');
		} else {
			// Failure
    		$message = "Error, can't register new user"; //join("<br />", $item->errors);
		} 
	} else {
		// Failure
    	$message = "User's fields can't be empty"; //join("<br />", $item->errors);
	}
	
} elseif ($_POST['action']=='delete') {
	
	$id = $_POST['id_user'];
	$user = User::find_by_what('id', $id);
	
	if($user->update_one_by_what($id, 'status', 'Borrado')) {
			// Success
			$session->message("User deleted successfully");
			redirect_to('user.php');
		} else {
			// Failure
			$message = "Error, user was not deleted"; //join("<br />", $item->errors);
			redirect_to('user.php');
		}

} elseif ($_POST['action']=='reactivate') {
	
	$id = $_POST['id_user'];
	$user = User::find_by_what('id', $id);
	
	if($user->update_one_by_what($id, 'status', 'Activo')) {
		// Success
		$session->message("The user was reactivated succesfully");
		redirect_to('user.php');
	} else {
		// Failure
		$message = "Error, the user wasn't reactivated"; //join("<br />", $item->errors);
		redirect_to('user.php');
	}
			
} else { redirect_to('user.php'); }


include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<h3>Edit user info of <?php echo htmlentities($first_name); ?></h3>
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<form action="user-edit.php" method="POST" class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-sm-2" for="first_name">Name: </label>
		    <div class="col-sm-4">
	    		<input id="first_name" type="text" name="first_name" value="<?php echo htmlentities($first_name); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="last_name">Last name: </label>
		    <div class="col-sm-4">
	    		<input id="last_name" type="text" name="last_name" value="<?php echo htmlentities($last_name); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="phone">Phone: </label>
		    <div class="col-sm-4">
	    		<input id="phone" type="text" name="phone" value="<?php echo htmlentities($phone); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="email">User: </label>
		    <div class="col-sm-4">
	    		<input id="email" type="text" name="email" value="<?php echo htmlentities($email); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="pass">Password: </label>
		    <div class="col-sm-4">
				<input id="pass" type="text" name="password" value="<?php echo htmlentities($password); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="sel1">Level: </label>
		    <div class="col-sm-4">
	    		<select name="level" class="form-control" id="sel1">
					<option value="Motorista">Motorista</option>
					<option value="Manager">Manager</option>
				</select>
			</div>
		</div>
	    <input type="hidden" name="status" value="<?php echo $status; ?>">
	    <input type="hidden" name="action" value="save">
	    <input type="hidden" name="id_user" value="<?php echo $id; ?>">
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Save" class="btn btn-primary" />
	   		</div>
	   	</div>
    </form>
</div>


<?php include_layout_template('footer.php'); ?>