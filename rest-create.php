<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

if(isset($_POST['submit'])) {
	
	$id = null;
	$name = trim($_POST['name']);
	$street_name = trim($_POST['street_name']);
	$street_number = trim($_POST['street_number']);
	$postal_code = trim($_POST['postal_code']);
	$manager = trim($_POST['manager']);
	$phone = trim($_POST['phone']);
	$email = trim($_POST['email']);
	$status = trim($_POST['status']);
	$user = trim($_POST['user']);
	$password = trim($_POST['password']);
	
	$restaurant = Restaurant::make(
		$id,
		$name,
		$street_name,
		$street_number,
		$postal_code,
		$manager,
		$phone,
		$email,
		$status,
		$user,
		$password
	);
	
	if ($restaurant) {
		if($restaurant->save()) {
			// Success
    		$session->message("Restaurant added successfully.");
    		redirect_to('restaurant.php');
		} else {
			// Failure
    		$message = "Error to register the restaurant."; //join("<br />", $item->errors);
		} 
	} else {
		// Failure
    	$message = "The fields in the form can't be empty"; //join("<br />", $item->errors);
	}
} else {
	$id = "";
	$name = "";
	$street_name = "";
	$street_number = "";
	$postal_code = "";
	$manager = "";
	$phone = "";
	$email = "";
	$status = "";
	$user = "";
	$password = "";
}

include_layout_template('header.php'); 
?>
<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<h3>Register new restaurant</h3>
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<form action="rest-create.php" method="POST" class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-sm-2" for="name">Name: </label>
		    <div class="col-sm-4">
			    <input id="name" type="text" name="name" value="<?php echo htmlentities($name); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="street">Street name: </label>
		    <div class="col-sm-4">
			    <input id="street" type="text" name="street_name" value="<?php echo htmlentities($street_name); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="number">Building number: </label>
		    <div class="col-sm-4">
			    <input id="number" type="text" name="street_number" value="<?php echo htmlentities($street_number); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="postcode">Postal code: </label>
		    <div class="col-sm-4">
			    <input id="postcode" type="text" name="postal_code" value="<?php echo htmlentities($postal_code); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="resp">Manager: </label>
		    <div class="col-sm-4">
			    <input id="resp" type="text" name="manager" value="<?php echo htmlentities($manager); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="phone">Phone: </label>
		    <div class="col-sm-4">
			    <input id="phone" type="text" name="phone" value="<?php echo htmlentities($phone); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="email">Email: </label>
		    <div class="col-sm-4">
			    <input id="email" type="text" name="email" value="<?php echo htmlentities($email); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="user">User: </label>
		    <div class="col-sm-4">
			    <input id="user" type="text" name="user" value="<?php echo htmlentities($user); ?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="password">Password: </label>
		    <div class="col-sm-4">
			    <input id="password" type="text" name="password" value="<?php echo htmlentities($password); ?>" class="form-control" />
			</div>
		</div>
	    <input type="hidden" name="status" value="Activo">
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Create" class="btn btn-primary" />
	   		</div>
	   	</div>
    </form>
</div>

<?php include_layout_template('footer.php'); ?>
		
