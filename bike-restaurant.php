<?php require_once("includes/initialize.php"); 

if(!$session->is_logged_in()) {
	redirect_to("index.php");
}

include_layout_template('header.php');

?>

<?php include_layout_template('bike-navigation.php'); ?>
	
<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Restaurant list:</h3>
	<?php
		$db_array = Restaurant::find_all_by_what('status','"Activo"');
		if($db_array) {
			echo '<div class="table-responsive">';
			$table_header  = '<table class="table table-hover"><tr>';
			$table_header .= '<th>Name</th>';
			$table_header .= '<th>Street</th>';
			$table_header .= '<th>Number</th>';
			$table_header .= '<th>Postal code</th>';
			$table_header .= '<th>Manager</th>';
			$table_header .= '<th>Phone</th>';
			$table_header .= '<th>Email</th>';
			$table_header .= '</tr>';
			echo $table_header;
			foreach($db_array as $key => $value) {
				$table_body  = '<tr>';
				$table_body .= '<td>'.$value->name.'</td>';
				$table_body .= '<td>'.$value->street_name.'</td>';
				$table_body .= '<td>'.$value->street_number.'</td>';
				$table_body .= '<td>'.$value->postal_code.'</td>';
				$table_body .= '<td>'.$value->manager.'</td>';
				$table_body .= '<td>'.$value->phone.'</td>';
				$table_body .= '<td>'.$value->email.'</td>';
				$table_body .= '</tr>';
				echo $table_body;
			}
			echo '</table></div>';
		} else {
			echo "<p>The list is empty.</p>";
		}
	?>
	</br>
</div>

<?php include_layout_template('footer.php'); ?>