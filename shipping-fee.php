<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

$rest_array = Restaurant::find_all_by_what('status','"Activo"');
$postal_codes_array = Postalcode::find_all_order_by('postcode');

if (isset($_GET['id_rest'])) {

	$id_rest = $_GET['id_rest'];
	$rest = Restaurant::find_by_id($id_rest);

	if (isset($_POST['request'])) {
	
		$id = trim($_POST['id_sh_fee']);
		$id_postal_code = trim($_POST['id_postal_code']);
		$price = trim($_POST['price']);
			
		$sh_fee = Shippingfee::make(
			$id,
			$id_postal_code,
			$id_rest,
			$price
		);
		
		if (isset($sh_fee)) {
			if($sh_fee->save()) {
				// Success
	    		$session->message("Delivery cost updated successfully.");
	    		$link = 'shipping-fee.php?id_rest='.$id_rest;
	    		redirect_to($link);
			} else {
				// Failure
	    		$message = "Error, can't update the delivery cost"; //join("<br />", $item->errors);
			} 
		} else {
			// Failure
	    	$message = "Error, delivery cost can't be empty"; //join("<br />", $item->errors);
		}
	}

} 

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Delivery cost by postal code</h3><br>
	<?php
		if ($rest_array) {
			$select_body  = '<form action="shipping-fee.php" id="rest_form" method="GET" class="form-horizontal" role="form">';
		    $select_body .= '<div class="form-group">';
		    $select_body .= '<label class="control-label col-sm-4" for="sel1">Select restaurant:</label>';
		    $select_body .= '<div class="col-sm-4">';
			$select_body .= '<select name="id_rest" form="rest_form" class="form-control" id="sel1">';
			if (isset($rest->name)) {
				$select_body .= '<option value="'.$id_rest.'">'.$rest->name.'</option>'; 
			}
			foreach($rest_array as $key => $value) {
				$select_body .= '<option value="'.$value->id.'">'.$value->name.'</option>';
			}
			$select_body .= '</select><br>';
			$select_body .= '<input type="submit" value="Search" class="btn btn-primary" />';
			$select_body .= '</div></div></form>';
			echo $select_body;
		} else {
			echo "<p>No registered restaurants in database.</p>";
		}
	?>

	
	<?php
		if(isset($id_rest)) {
			$table_header  = '<table class="table table-hover table-responsive table-striped"><tr>';
			$table_header .= '<th colspan="3">Tariff for restaurant ';
			$table_header .= $rest->name.'</th>';
			$table_header .= '</tr>';
			echo $table_header;
			$row_count = 0;
			foreach($postal_codes_array as $key => $value) {
				$row_count = $row_count + 1;
				$sh_fee_obj = Shippingfee::find_by_two_params('id_postal_code', $value->id, 'id_rest', $id_rest);
				if (isset($sh_fee_obj[0]->price)) {
					$sh_fee_array = array('price'=>$sh_fee_obj[0]->price,'id'=>$sh_fee_obj[0]->id);
				} else {
					$sh_fee_array = array('price'=>'0','id'=>null);
				}
				$table_body  = '<tr>';
				$table_body .= '<td>'.$value->postcode.'</td>';
				$table_body .= '<td>'.$sh_fee_array['price'].' euros</td>';
				$table_body .= '<td>';
				$table_body .= '<form action="shipping-fee.php?id_rest='.$id_rest.'" method="POST">';
				$table_body .= '<input type="number" name="price" min="0" max="50" value="'.$sh_fee_array['price'].'">  ';
				$table_body .= '<input type="hidden" name="id_sh_fee" value="'.$sh_fee_array['id'].'">';
				$table_body .= '<input type="hidden" name="id_postal_code" value="'.$value->id.'">';
				$table_body .= '<input type="hidden" name="request" value="save">';
				$table_body .= '<input type="submit" value="Save" class="btn btn-primary btn-sm" />';
				$table_body .= '</form>';
				$table_body .= '</td>';
				$table_body .= '</tr>';
				echo $table_body;
			}
			echo '</table>';
		} else {
			echo "<p>Select a restaurant and click Search.</p>";
		}
	?>

</div>

<?php include_layout_template('footer.php'); ?>