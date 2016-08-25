<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

$id_order = null;
$action = null;
if (isset($_POST['action'])) {
	$action = $_POST['action'];
}

if ($action=='edit') {
	
	$id = $_POST['id_order'];
	$order = Orders::find_by_what('id', $id);
	$id_rest = $order->id_rest;
	$street_name = $order->street_name;
	$street_number = $order->street_number;
	$postal_code = $order->postal_code;
	$apartment_number = $order->apartment_number;
	$phone = $order->phone;
	$reception_time = $order->reception_time;
	$accepted_to_time = $order->accepted_to_time;
	$register_time = $order->register_time;
	$assigned_time = $order->assigned_time;
	$estimated_arrival = $order->estimated_arrival;
	$finished_time = $order->finished_time;
	$id_bike = $order->id_bike;
	$status = $order->status;
	$cost = $order->cost;
	$bike_profit = $order->bike_profit;

	$rth = date('H', strtotime($reception_time));
	$rtm = date('i', strtotime($reception_time));
	$rt_date = date('Y-m-d ', strtotime($reception_time));
	$atth = date('H', strtotime($accepted_to_time));
	$attm = date('i', strtotime($accepted_to_time));
	$att_date = date('Y-m-d ', strtotime($accepted_to_time));

} elseif ($action=='save') {

	$rth = $_POST['reception_time_hour'];
	$rtm = $_POST['reception_time_minute'];
	$rt_date = $_POST['rt_date'];
	$atth = $_POST['accepted_to_time_hour'];
	$attm = $_POST['accepted_to_time_minute'];
	$att_date = $_POST['att_date'];

	//$today = getdate();
	//$today_date = $today[year].'-'.$today[mon].'-'.$today[mday].' ';
	//$today_timestamp = $today_date.$today[minutes].':'.$today[hours].':'.$today[seconds];

	$reception_time = $rt_date.$rth.':'.$rtm.':00';
	$accepted_to_time = $att_date.$atth.':'.$attm.':00';
	//$register_time = $today_timestamp;
	
	$id = trim($_POST['id_order']);
	$id_rest = trim($_POST['id_rest']);
	$street_name = trim($_POST['street_name']);
	$street_number = trim($_POST['street_number']);
	$postal_code = trim($_POST['postal_code']);
	$apartment_number = trim($_POST['apartment_number']);
	$phone = trim($_POST['phone']);
	//$reception_time = trim($_POST['reception_time']);
	//$accepted_to_time = trim($_POST['accepted_to_time']);
	$register_time = trim($_POST['register_time']);
	$assigned_time = trim($_POST['assigned_time']);
	$estimated_arrival = trim($_POST['estimated_arrival']);
	$finished_time = trim($_POST['finished_time']);
	$id_bike = trim($_POST['id_bike']);
	$status = trim($_POST['status']);
	$cost = trim($_POST['cost']);
	$bike_profit = trim($_POST['bike_profit']);
		
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
    		$session->message("Order was created successfully.");
    		redirect_to('order-open.php');
		} else {
			// Failure
    		$message = "error to save the order"; //join("<br />", $item->errors);
		} 
	} else {
		// Failure
    	$message = "Fields in order can't be empty"; //join("<br />", $item->errors);
	}

} elseif ($action=='close') {
	
	$id = $_POST['id_order'];
	$order = Orders::find_by_what('id', $id);
	if ($order) { 
		$order->status = 'Cerrado';
	}
	
	if ($order->update_one_by_what($id, 'status', $order->status)) {
			// Success
			$session->message("The order has been closed successfully");
			$today_timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
			$order->update_one_by_what($id, 'finished_time', $today_timestamp);
			redirect_to('order-open.php');
		} else {
			// Failure
			$message = "The order was not closed"; //join("<br />", $item->errors);
			redirect_to('order-open.php');
		}

} elseif ($action=='delete') {
	
	$id = $_POST['id_order'];
	$order = Orders::find_by_what('id', $id);
	if ($order) { 
		$comments = $order->comments();
		$order->status = $order->status.' y borrado';
	}
	
	//if($order->delete_by_what('id', $id)) {  //order won't be deleted, just status change to deleted
	if ($order->update_one_by_what($id, 'status', $order->status)) {
			// Success
			$session->message("The order was successfully deleted");
			//because order won't be deleted, then comments also don't need it
			//foreach ($comments as $comment) { $comment->delete_by_what('id', $comment->id); }
			redirect_to('order-open.php');
		} else {
			// Failure
			$message = "Error, order was not deleted"; //join("<br />", $item->errors);
			redirect_to('order-open.php');
		}

} elseif ($action=='reactivate') {
	
	$id = $_POST['id_order'];
	$order = Orders::find_by_what('id', $id);
	if ($order) { 
		$comments = $order->comments();
		if ($order->status=='Abierto y borrado') {
			$order->status = 'Abierto';
		} else {
			$order->status = 'Cerrado';
		}
	}
	
	//if($order->delete_by_what('id', $id)) {  //order won't be deleted, just status change to deleted
	if ($order->update_one_by_what($id, 'status', $order->status)) {
			// Success
			$session->message("The order was reactivated successfully");
			//because order won't be deleted, then comments also don't need it
			//foreach ($comments as $comment) { $comment->delete_by_what('id', $comment->id); }
			redirect_to('order-open.php');
		} else {
			// Failure
			$message = "Error, order was not reactivated"; //join("<br />", $item->errors);
			redirect_to('order-open.php');
		}
			
} elseif ($action=='asignar') {
	
	$id_bike = $_POST['id_bike'];
	$id_order = $_POST['id_order'];
	$order = Orders::find_by_what('id', $id_order);
	$last_bike = User::find_by_what('id', $order->id_bike);
	if ($last_bike) { 
		$last_bike_fname = $last_bike->full_name();
	} else {
		$last_bike_fname = 'Sin asignar';
		}
	$author = User::find_by_what('id', $_SESSION['user_id']);
	
	if($order->update_one_by_what($id_order, 'id_bike', $id_bike)) {
			// Success
			$session->message("The order was assigned successfully");

			if ($order->assigned_time!='0000-00-00 00:00:00') {
				$body = 'The order was reasignated to a new delivery boy, before was '.$last_bike_fname;
				$new_comment = Comment::make($author->id, $order->id, $body);
				if($new_comment && $new_comment->save()) {
					// comment saved
				}
			} else {
				$time_now = strftime("%Y-%m-%d %H:%M:%S", time());
				$order->update_one_by_what($id_order, 'assigned_time', $time_now);
			}

			redirect_to('order-open.php');
		} else {
			// Failure
			$message = "Error, the order was not assigned"; //join("<br />", $item->errors);
			redirect_to('order-open.php');
		}

} else { redirect_to('order-open.php'); }


include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">

	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Editar pedido</h3>

	<form action="order-edit.php" method="POST" class="form-horizontal" role="form">

	    <div class="form-group">
		    <label class="control-label col-sm-2"  for="sel1">Restaurant:</label> 
		    <div class="col-sm-4">
			    <select name="id_rest" class="form-control" id="sel1"><?php
			    	$rest_array = Restaurant::find_all();
					$order_rest = Restaurant::find_by_what('id', $id_rest);
					$select_body  = '<option value="';
					$select_body .= $order_rest->id;
					$select_body .= '">';
					$select_body .= $order_rest->name;
					$select_body .= '</option>';
					echo $select_body;
			    	if($rest_array) {
						foreach($rest_array as $key => $value) {
							$select_body = '<option value="';
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
		    <label class="control-label col-sm-2" for="street_number">Building number: </label>
		    <div class="col-sm-4">
		    	<input id="street_number" type="text" name="street_number" value="<?php echo htmlentities($street_number); ?>" class="form-control" />
	    	</div>
	    </div>
	    <div class="form-group">
		    <label class="control-label col-sm-2" for="route">Street name: </label>
		    <div class="col-sm-10">
		    	<input id="route" type="text" name="street_name" value="<?php echo htmlentities($street_name); ?>" class="form-control" />
	    	</div>
	    </div>
	    <div class="form-group">
			<label class="control-label col-sm-2" for="locality">City: </label>
			<div class="col-sm-4">
				<input id="locality" class="form-control" value="Madrid" readonly />
	    	</div>
			<p hidden>State: <input id="administrative_area_level_1" /></p>
		    <label class="control-label col-sm-1" for="postal_code">Postal code: </label>
		    <div class="col-sm-5">
		    	<input id="postal_code" type="text" name="postal_code" value="<?php echo htmlentities($postal_code); ?>" class="form-control" />
			</div> 
	    </div>
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
	    <div class="form-group">
			<label class="control-label col-sm-2" for="cost">Delivery cost: </label>
			<div class="col-sm-4">
				<input id="cost" type="text" name="cost" value="<?php echo htmlentities($cost); ?>" class="form-control" />
	    	</div>
			<label class="control-label col-sm-1" for="bike_prof">Delivery boy earning: </label>
			<div class="col-sm-5">
				<input id="bike_prof" type="text" name="bike_profit" value="<?php echo htmlentities($bike_profit); ?>" class="form-control" />
	    	</div>
	    </div>
	    <h4>Received time: </h4>
	    <div class="form-group">
			<label class="control-label col-sm-2" for="h1h">hour</label>
			<div class="col-sm-2">
				<input id="h1h" type="number" name="reception_time_hour" min="0" max="24" value="<?php echo $rth; ?>" class="form-control">
			</div>
			<label class="control-label col-sm-1" for="h1m">minute</label>
			<div class="col-sm-2">
				<input id="h1m" type="number" name="reception_time_minute" min="0" max="59" value="<?php echo $rtm; ?>" class="form-control">
	    	</div>
	    	<label class="control-label col-sm-1" for="rt_date">date</label>
			<div class="col-sm-4">
				<input id="rt_date" type="text" name="rt_date" min="1" max="59" value="<?php echo $rt_date; ?>" class="form-control" readonly>
	    	</div>
		</div>
	    <h4>Accepted to: </h4>
	    <div class="form-group">
		    <label class="control-label col-sm-2" for="h2h">hour</label>
		    <div class="col-sm-2">
		    	<input id="h2h" type="number" name="accepted_to_time_hour" min="0" max="24" value="<?php echo $atth; ?>" class="form-control">
			</div>
			<label class="control-label col-sm-1" for="h2m">minute</label>
		    <div class="col-sm-2">
		    	<input id="h2m" type="number" name="accepted_to_time_minute" min="0" max="59" value="<?php echo $attm; ?>" class="form-control">
	    	</div>
	    	<label class="control-label col-sm-1" for="att_date">date</label>
		    <div class="col-sm-4">
		    	<input id="att_date" type="text" name="att_date" min="1" max="59" value="<?php echo $att_date; ?>" class="form-control" readonly>
	    	</div>
		</div>
		<input type="hidden" name="id" value="<?php echo htmlentities($id_order); ?>">
		<input type="hidden" name="register_time" value="<?php echo htmlentities($register_time); ?>">
		<input type="hidden" name="assigned_time" value="<?php echo htmlentities($assigned_time); ?>">
		<input type="hidden" name="estimated_arrival" value="<?php echo htmlentities($estimated_arrival); ?>">
		<input type="hidden" name="finished_time" value="<?php echo htmlentities($finished_time); ?>">
		<input type="hidden" name="id_bike" value="<?php echo htmlentities($id_bike); ?>">
		<input type="hidden" name="status" value="<?php echo htmlentities($status); ?>">
	    <input type="hidden" name="action" value="save">
	    <input type="hidden" name="id_order" value="<?php echo $id; ?>">
		<br>
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	   			<input type="submit" name="submit" value="Save" class="btn btn-primary" />
	   		</div>
	   	</div>
	</form>
</div>

<?php include_layout_template('footer.php'); ?>