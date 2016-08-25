<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

$request = null;
if (isset($_POST['request'])) {
	$request = $_POST['request'];
}

if ($request=='save') {

	$id = null;
	$postcode = trim($_POST['postal_code']);
	
	$postal_code = Postalcode::make(
		$id,
		$postcode
	);
	
	if ($postal_code) {
		if($postal_code->save()) {
			// Success
    		$session->message("Postal code saved.");
			//$session->set_orders_id_session($item->item_id); //$session->item_id($item->item_id);
    		redirect_to('postal-codes.php');
		} else {
			// Failure
    		$message = "Error, can't save postal code"; //join("<br />", $item->errors);
		} 
	} else {
		// Failure
    	$message = "The postal code field can't be empty"; 
	}

} elseif ($request=='delete') {
	
	$id = $_POST['id_postal_code'];
	$postal_code = Postalcode::find_by_what('id', $id);
	
	if($postal_code->delete_by_what('id', $id)) {
			// Success
			$session->message("The postal code was deleted successfully");
			redirect_to('postal-codes.php');
		} else {
			// Failure
			$message = "Error, the postal code wasn't deleted";
			redirect_to('postal-codes.php');
		}

} else {
	$postal_codes_array = Postalcode::find_all_order_by('postcode');
}

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>C&oacute;digos postales de reparto</h3>	
	
	<?php
		if($postal_codes_array) {
			$table_header  = '<table class="table table-hover table-bordered table-striped"><tr>';
			$table_header .= '<th>Postal codes</th>';
			$table_header .= '<th> </th>';
			$table_header .= '</tr>';
			echo $table_header;
			$row_count = 0;
			foreach($postal_codes_array as $key => $value) {
				$row_count = $row_count + 1;
				$table_body  = '<tr>';
				$table_body .= '<td>'.$value->postcode.'</td>';
				$table_body .= '<td>';
				$table_body .= '<form action="postal-codes.php" method="POST">';
				$table_body .= '<input type="hidden" name="request" value="delete">';
				$table_body .= '<input type="hidden" name="id_postal_code" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Delete" class="btn btn-danger btn-xs" />';
				$table_body .= '</form>';
				$table_body .= '</td>';
				$table_body .= '</tr>';
				echo $table_body;
			}
			echo '</table>';
		} else {
			echo "<p>The list is empty.</p>";
		}
	?>
	<form action="postal-codes.php" method="POST" class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-sm-4" for="postcode">Add postal code: </label>
		    <div class="col-sm-4">
			    <input id="postcode" type="text" name="postal_code" class="form-control" />
			</div>
		</div>
		<input type="hidden" name="request" value="save">
		<div class="form-group">
	    	<div class="col-sm-offset-4 col-sm-8">
	   			<input type="submit" name="submit" value="Crear" class="btn btn-primary" />
	   		</div>
	   	</div>
	</form>

</div>

<?php include_layout_template('footer.php'); ?>