<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

$rest_array = Restaurant::find_all_by_what('status','"activo"');

$validation = true;
if ($_POST['street_name'] == '') { $validation = false; }
if ($_POST['street_number'] == '') { $validation = false; }
if ($_POST['postal_code'] == '') { $validation = false; }
if ($_POST['apartment_number'] == '') { $validation = false; }
if ($_POST['phone'] == '') { $validation = false; }
if ($_POST['reception_time_hour'] == '') { $validation = false; }
if ($_POST['reception_time_minute'] == '') { $validation = false; }
if ($_POST['accepted_to_time_hour'] == '') { $validation = false; }
if ($_POST['accepted_to_time_minute'] == '') { $validation = false; }

if(isset($_POST['submit'])) {

	$rth = $_POST['reception_time_hour'];
	$rtm = $_POST['reception_time_minute'];
	$atth = $_POST['accepted_to_time_hour'];
	$attm = $_POST['accepted_to_time_minute'];

	$today_date = strftime("%Y-%m-%d ", time());
	$today_timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
	
	$reception_time = $today_date.$rth.':'.$rtm.':00';
	$accepted_to_time = $today_date.$atth.':'.$attm.':00';
	$register_time = $today_timestamp;

	$id = null;
	$id_rest = trim($_POST['id_rest']);
	$street_name = trim($_POST['street_name']);
	$street_number = trim($_POST['street_number']);
	$postal_code = trim($_POST['postal_code']);
	$apartment_number = trim($_POST['apartment_number']);
	$phone = trim($_POST['phone']);
	$status = 'Abierto';

	if ($validation) {
		$postcode_obj = Postalcode::find_by_what('postcode', $postal_code);
		if ($postcode_obj) {
			$shipfee_array = Shippingfee::find_by_two_params('id_postal_code', $postcode_obj->id, 'id_rest', $id_rest);
			$fattax_array = Fattax::find_all();
			$cost = $shipfee_array[0]->price;
			$bike_profit = $cost - $fattax_array[0]->tax;
		} else {
			$cost = 0;
			$bike_profit = 0;
		}
		
		$assigned_time = null;
		$estimated_arrival = null;
		$finished_time = null;
		$id_bike = null;

		$orders = Orders::make(
			$id,
			$id_rest,
			$street_name,
			$street_number,
			$postal_code,
			$apartment_number,
			$phone,
			$reception_time,
			$accepted_to_time,
			$register_time,
			$assigned_time,
			$estimated_arrival,
			$finished_time,
			$id_bike,
			$status,
			$cost,
			$bike_profit
		);
		
		if ($orders) {
			if($orders->save()) {
				// Success
	    		$session->message("The order was created successfully.");
				//$session->set_orders_id_session($item->item_id); //$session->item_id($item->item_id);
	    		redirect_to('order-open.php');
			} else {
				// Failure
	    		$message = "error to save the order, line 50"; //join("<br />", $item->errors);
			} 
		} else {
			// Failure
	    	$message = "The fields from the order form can't be empty"; //join("<br />", $item->errors);
		}
	} else {
		// Failure
	    $message = "The fields from the order form can't be empty"; //join("<br />", $item->errors);
	}
} else {
	$id = "";
	$id_rest = "";
	$street_name = "";
	$street_number = "";
	$postal_code = "";
	$apartment_number = "";
	$phone = "";
	$reception_time = "";
	$accepted_to_time = "";
	$register_time = "";

	$today_timestamp = strftime("%Y-%m-%d %H:%M:%S", time());

	$date_rt = new DateTime($today_timestamp);
	$date_rt->modify('-5 minutes');
	$rth = $date_rt->format("H");
	$rtm = $date_rt->format("i");

	$date_att = new DateTime($today_timestamp);
	$date_att->modify('+55 minutes');
	$atth = $date_att->format("H");
	$attm = $date_att->format("i");

}

include_layout_template('header.php');

?>

<?php
	require_once("javascripts/google-maps.js");
?>


<?php include_layout_template('navigation.php'); ?>

<div class="container">

	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Create new order</h3>

	<form action="order-create.php" method="POST" class="form-horizontal" role="form">

	    <div class="form-group">
		    <label class="control-label col-sm-2"  for="sel1">Restaurant:</label> 
		    <div class="col-sm-4">
			    <select name="id_rest" class="form-control" id="sel1"><?php 
			    	if($rest_array) {
						foreach($rest_array as $key => $value) {
							$select_body  = '<option value="';
							$select_body .= $value->id;
							$select_body .= '">';
							$select_body .= $value->name;
							$select_body .= '</option>';
							echo $select_body;
						}
					} else {
						echo '<option value="">Error, no registered restaurants in database</option>';
					}
			    ?></select>
		    </div>
	    </div>
	    <div class="form-group">
	    	<label class="control-label col-sm-2" for="autocomplete">Google search:</label> 
	    	<div class="col-sm-10">
	    		<input id="autocomplete" placeholder="Building number and street name" onFocus="geolocate()" type="text" class="form-control" />
	    	</div>
	    </div>
	    <div class="form-group">
		    <label class="control-label col-sm-2" for="street_number">Building number: </label>
		    <div class="col-sm-4">
		    	<input id="street_number" type="text" name="street_number" value="<?php echo htmlentities($street_number); ?>" class="form-control" readonly>
	    	</div>
	    </div>
	    <div class="form-group">
		    <label class="control-label col-sm-2" for="route">Street name: </label>
		    <div class="col-sm-10">
		    	<input id="route" type="text" name="street_name" value="<?php echo htmlentities($street_name); ?>" class="form-control" readonly />
	    	</div>
	    </div>
	    <div class="form-group">
			<label class="control-label col-sm-2" for="locality">City: </label>
			<div class="col-sm-4">
				<input id="locality" class="form-control" readonly />
	    	</div>
			<p hidden>State: <input id="administrative_area_level_1" /></p>
		    <label class="control-label col-sm-1" for="postal_code">Postal code: </label>
		    <div class="col-sm-5">
		    	<input id="postal_code" type="text" name="postal_code" value="<?php echo htmlentities($postal_code); ?>" class="form-control" readonly />
			</div> 
	    </div>
		<p hidden>Country: <input id="country" /></p>
	    <div class="form-group">
			<label class="control-label col-sm-2" for="apt">Door number: </label>
			<div class="col-sm-4">
				<input id="apt" type="text" name="apartment_number" value="<?php echo htmlentities($apartment_number); ?>" class="form-control" />
	    	</div>
			<label class="control-label col-sm-1" for="phone">Phone: </label>
			<div class="col-sm-5">
				<input id="phone" type="text" name="phone" value="<?php echo htmlentities($phone); ?>" class="form-control" />
	    	</div>
	    </div>
	    <!-- <h4>Hora llamada&nbsp; -->
	    <div class="form-group">
			<label class="control-label col-sm-2" for="h1h">Call time: </label>
			<!-- <div class="col-sm-4"> -->
				<input id="h1h" type="number" name="reception_time_hour" min="0" max="24" value="<?php echo $rth; ?>" style="width:3em"> : 
			<!-- </div> -->
			<!-- <label class="control-label col-sm-1" for="h1m">minuto</label> -->
			<!-- <div class="col-sm-5"> -->
				<input id="h1m" type="number" name="reception_time_minute" min="1" max="59" value="<?php echo $rtm; ?>" style="width:3em">
	    	<!-- </div> -->
		</div>
		<!-- </h4> -->
	    <!-- <h4>Hora entrega &nbsp; -->
	    <div class="form-group">
		    <label class="control-label col-sm-2" for="h2h">Deliver to time: </label>
		    <!-- <div class="col-sm-4"> -->
		    	<input id="h2h" type="number" name="accepted_to_time_hour" min="0" max="24" value="<?php echo $atth; ?>" style="width:3em"> : 
			<!-- </div> -->
			<!-- <label class="control-label col-sm-1" for="h2m">minuto</label> -->
		    <!-- <div class="col-sm-5"> -->
		    	<input id="h2m" type="number" name="accepted_to_time_minute" min="1" max="59" value="<?php echo $attm; ?>" style="width:3em">
	    	<!-- </div> -->
		</div>
		<!-- </h4> -->
		<br>
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Create" class="btn btn-primary" />
	   		</div>
	   	</div>
	</form>
</div>



<script type="text/javascript">
	initialize();
</script>

<?php include_layout_template('footer.php'); ?>