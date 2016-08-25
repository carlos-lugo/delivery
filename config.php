<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

include_layout_template('header.php');

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Website configurations</h3>	
	<h4><a href='restaurant.php'>Restaurants</a></h4>
	<h4><a href='user.php'>Staff</a></h4>
	<h4><a href="postal-codes.php">Delivery postal codes</a></h4>
	<h4><a href="shipping-fee.php">Delivery prices</a></h4>
	<h4><a href="fat-tax.php">Tax earning by order</a></h4>
</div>

<?php include_layout_template('footer.php'); ?>