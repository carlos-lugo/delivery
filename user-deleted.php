<?php require_once("includes/initialize.php");

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Deleted users:</h3>
	<?php
		$db_array = User::find_all_by_what('status','"Borrado"');
		if($db_array) {
			echo '<div class="table-responsive">';
			$table_header  = '<table class="table table-hover"><tr>';
			$table_header .= '<th>Name</th>';
			$table_header .= '<th>Last name</th>';
			$table_header .= '<th>Phone</th>';
			$table_header .= '<th>Level</th>';
			$table_header .= '<th>Actions</th>';
			$table_header .= '</tr>';
			echo $table_header;
			foreach($db_array as $key => $value) {
				$table_body  = '<tr>';
				$table_body .= '<td>'.$value->first_name.'</td>';
				$table_body .= '<td>'.$value->last_name.'</td>';
				$table_body .= '<td>'.$value->phone.'</td>';
				$table_body .= '<td>'.$value->level.'</td>';
				$table_body .= '<td>';
				$table_body .= '<table><tr>';
				$table_body .= '<td><form action="user-edit.php" method="POST">';
				$table_body .= '<input type="hidden" name="action" value="edit">';
				$table_body .= '<input type="hidden" name="id_user" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Edit" class="btn btn-primary btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '<td><form action="user-edit.php" method="POST">';
				$table_body .= '<input type="hidden" name="action" value="reactivate">';
				$table_body .= '<input type="hidden" name="id_user" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Reactivate" class="btn btn-success btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '</tr></table>';
				$table_body .= '</td>';
				$table_body .= '</tr>';
				echo $table_body;
			}
			echo '</table>';
		} else {
			echo "<p>The list is empty.</p>";
		}
	?>
	
</div>

<?php include_layout_template('footer.php'); ?>