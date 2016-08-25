<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

if(isset($_POST['submit'])) {
	
	$id = null;
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$phone = trim($_POST['phone']);
	$level = trim($_POST['level']);
	$status = trim('Activo');
	
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

    $message = "The fields from form can't be empty"; //join("<br />", $item->errors);
	
	$user_array = User::find_all();
	foreach ($user_array as $key => $value) {
		if ($value->email == $user->email) {
			$user = null;
			$message = "The name is already registered in database";
		}
	}

	if ($user) {
		if($user->save()) {
			// Success
    		$session->message("The new user was successfully registered.");
    		redirect_to('user.php');
		} else {
			// Failure
    		$message = "Error to register the user"; //join("<br />", $item->errors);
		} 
	}

} else {
	$id = "";
	$first_name = "";
	$last_name = "";
	$email = "";
	$password = "";
	$phone = "";
	$level = "";
	$status = "Activo";
}

include_layout_template('header.php'); 
?>

<?php include_layout_template('navigation.php'); ?>
<div class="container">
	<h3>Register new user</h3>
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<form action="user-create.php" method="POST" class="form-horizontal" role="form">
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
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Create" class="btn btn-primary" />
	   		</div>
	   	</div>
    </form>
</div>

<?php include_layout_template('footer.php'); ?>
		
