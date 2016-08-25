<?php
require_once("includes/initialize.php");

if($session->is_logged_in()) {
  redirect_to("index.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  
  // Check database to see if username/password exist.
	$found_user = User::authenticate($email, $password);
	$found_rest_user = Restaurant::authenticate($email, $password);
	
  if ($found_user) {
    $session->login($found_user);
		log_action('Login', "{$found_user->first_name} logged in.");
    redirect_to("index.php");
  } elseif ($found_rest_user) {
  	$found_rest_user->first_name = $found_rest_user->name;
  	$found_rest_user->level = 'Restaurante';
    $session->login($found_rest_user);
	log_action('Login', "{$found_rest_user->first_name} logged in.");
    redirect_to("userest-order-open.php");
  } else {
    // username/password combo was not found in the database
    $message = "Username/password combination incorrect.";
  }
  
} else { // Form has not been submitted.
  $email = "";
  $password = "";
}

?>
<?php include_layout_template('header.php'); ?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Sign in</h3>

	<form action="login.php" method="post" class="form-horizontal" role="form">
		<div class="form-group">
	    	<label class="control-label col-sm-2" for="email">User:</label> 
	    	<div class="col-sm-10">
	    		<input id="email" type="text" name="email" maxlength="30" value="<?php echo htmlentities($email); ?>" class="form-control" />
	    	</div>
	    </div>
	    <div class="form-group">
	    	<label class="control-label col-sm-2" for="pass">Password:</label> 
	    	<div class="col-sm-10">
	    		<input id="pass" type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" class="form-control" />
	    	</div>
	    </div>
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Login" class="btn btn-primary" />
	   		</div>
	   	</div>
	</form>
</div>

<?php include_layout_template('footer.php'); ?>
