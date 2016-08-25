<?php require_once("includes/initialize.php");

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

$bike_array = User::find_all_by_what('status','"activo"');
$orders_array_open = Orders::find_all_by_what('status','"Abierto y borrado"');
$orders_array_close = Orders::find_all_by_what('status','"Cerrado y borrado"');
if (!$orders_array_open) { $orders_array_open = array(); }
if (!$orders_array_close) { $orders_array_close = array(); }
$orders_array = array_merge($orders_array_open, $orders_array_close);

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Deleted orders:</h3>
	<?php
		if($orders_array) {
			echo '<div class="table-responsive">';
			$table_header  = '<table class="table table-hover"><tr>';
			$table_header .= '<th>Rest</th>';
			$table_header .= '<th>Address</th>';
			$table_header .= '<th>to</th>';
			$table_header .= '<th>Delivery boy</th>';
			$table_header .= '<th>Status</th>';
			$table_header .= '<th>Actions</th>';
			$table_header .= '</tr>';
			echo $table_header;
			$row_count = 0;
			foreach($orders_array as $key => $value) {
				$row_count = $row_count + 1;
				$restaurant = Restaurant::find_by_what('id', $value->id_rest);
				$bike = User::find_by_what('id', $value->id_bike);
				$table_body  = '<tr>';
				$table_body .= '<td>'.$restaurant->name.'</td>';
				$table_body .= '<td>'.$value->street_name.' '.$value->street_number.'<br>cp: '.$value->postal_code.'</td>';
				$table_body .= '<td>'.date('H:i', strtotime($value->accepted_to_time)).'</td>';
				
				$table_body .= '<td>';
				if ($bike) { 
					$table_body .= $bike->full_name();
				} else {
					$table_body .= 'Not assigned';
				}
				$table_body .= '</td>';
				$table_body .= '<td>'.$value->status.'</td>';
				$table_body .= '<td>';
				$table_body .= '<table><tr>';
				$table_body .= '<td><form action="order-details.php" method="GET">';
				$table_body .= '<input type="hidden" name="id_order" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Details" class="btn btn-primary btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '<td><form action="order-edit.php" method="POST">';
				$table_body .= '<input type="hidden" name="action" value="reactivate">';
				$table_body .= '<input type="hidden" name="id_order" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Reactivate" class="btn btn-success btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '</tr></table>';
				$table_body .= '</td>';
				$table_body .= '</tr>';
				echo $table_body;
			}
			echo '</table></div>';
		} else {
			echo "<p>The list is empty.</p>";
		}
	?>
	
</div>

<?php include_layout_template('footer.php'); ?>