<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

if ($_POST['action']=='edit') {
	
	$id = $_POST['id_rest'];
	$restaurant = Restaurant::find_by_what('id', $id);
	$name = $restaurant->name;
	$street_name = $restaurant->street_name;
	$street_number = $restaurant->street_number;
	$postal_code = $restaurant->postal_code;
	$manager = $restaurant->manager;
	$phone = $restaurant->phone;
	$email = $restaurant->email;
	$status = $restaurant->status;
	$user = $restaurant->user;
	$password = $restaurant->password;

} elseif ($_POST['action']=='save') {
	
	$id = $_POST['id_rest'];
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
    		$session->message("The restaurant was registered successfully.");
			//$session->set_orders_id_session($item->item_id); //$session->item_id($item->item_id);
    		redirect_to('restaurant.php');
		} else {
			// Failure
    		$message = "Error to register the restaurant"; //join("<br />", $item->errors);
		} 
	} else {
		// Failure
    	$message = "The fields from form can't be empty"; //join("<br />", $item->errors);
	}
	
} elseif ($_POST['action']=='delete') {
	
	$id = $_POST['id_rest'];
	$restaurant = Restaurant::find_by_what('id', $id);
	
	if($restaurant->update_one_by_what($id, 'status', 'Borrado')) {
			// Success
			$session->message("Restaurant was successfully deleted");
			redirect_to('restaurant.php');
		} else {
			// Failure
			$message = "Error, the restaurant was not deleted"; //join("<br />", $item->errors);
			redirect_to('restaurant.php');
		}

} elseif ($_POST['action']=='reactivate') {
	
	$id = $_POST['id_rest'];
	$restaurant = Restaurant::find_by_what('id', $id);
	
	if($restaurant->update_one_by_what($id, 'status', 'Activo')) {
		// Success
		$session->message("The restaurant was reactivated successfully");
		redirect_to('restaurant.php');
	} else {
		// Failure
		$message = "Error, the restaurant was not reactivated"; //join("<br />", $item->errors);
		redirect_to('restaurant.php');
	}
			
} else { redirect_to('restaurant.php'); }


include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<h3>Edit restaurant</h3>
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<form action="rest-edit.php" method="POST" class="form-horizontal" role="form">
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
	    <input type="hidden" name="status" value="<?php echo $status; ?>">
	    <input type="hidden" name="action" value="save">
	    <input type="hidden" name="id_rest" value="<?php echo $id; ?>">
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Save" class="btn btn-primary" />
	   		</div>
	   	</div>
    </form>
</div>


<?php include_layout_template('footer.php'); ?>