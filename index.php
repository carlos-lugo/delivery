<?php require_once("includes/initialize.php"); 

if($session->is_logged_in()) {
	if($session->level!='Manager') {
		redirect_to("bike-order-open.php");
	}
}

include_layout_template('header.php');

?>


<?php include_layout_template('navigation.php'); ?>
	
<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>
		<?php
			 echo "User: ";
			 if ($session->is_logged_in()) { 
			 	echo " " . $_SESSION['first_name'];
			 } else {
			 	echo "Guest";	
			 }
		?>
	</h3>
</div>


<?php include_layout_template('footer.php'); ?>