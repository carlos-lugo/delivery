<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Restaurant list:</h3>
	<?php
		$db_array = Restaurant::find_all_by_what('status','"Activo"');
		if($db_array) {
			echo '<div class="table-responsive">';
			$table_header  = '<table class="table table-hover"><tr>';
			$table_header .= '<th>Name</th>';
			$table_header .= '<th>Street name</th>';
			$table_header .= '<th>Building number</th>';
			$table_header .= '<th>Postal code</th>';
			$table_header .= '<th>Manager</th>';
			$table_header .= '<th>Phone</th>';
			$table_header .= '<th>Email</th>';
			$table_header .= '<th>Actions</th>';
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
				$table_body .= '<td>';
				$table_body .= '<table><tr>';
				$table_body .= '<td><form action="rest-edit.php" method="POST">';
				$table_body .= '<input type="hidden" name="action" value="edit">';
				$table_body .= '<input type="hidden" name="id_rest" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Edit" class="btn btn-primary btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '<td><form action="rest-edit.php" method="POST">';
				$table_body .= '<input type="hidden" name="action" value="delete">';
				$table_body .= '<input type="hidden" name="id_rest" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Delete" class="btn btn-danger btn-xs" />';
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
	</br>
	<button onclick="location.href='rest-create.php';" class="btn btn-primary">Add restaurant</button>
	<button onclick="location.href='rest-deleted.php';" class="btn btn-primary">Deleted restaurants</button>
</div>

<?php include_layout_template('footer.php'); ?>