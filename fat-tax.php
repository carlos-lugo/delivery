<?php require_once("includes/initialize.php"); 

if((!$session->is_logged_in())||($session->level!='Manager')) {
	redirect_to("index.php");
}

include_layout_template('header.php');

$fat_tax = Fattax::find_by_id(1);

if (isset($_GET['tax'])) {
	$tax = $_GET['tax'];
	Fattax::update_one_by_what(1, 'tax', $tax);
	$message = "Successfully updated";
	redirect_to('fat-tax.php');
}

?>

<?php include_layout_template('navigation.php'); ?>

<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Tax earning by order</h3>	
	<form action="fat-tax.php" method="GET" class="form-horizontal" role="form">
		<div class="form-group">
			<input type="text" name="tax" value="<?php echo $fat_tax->tax; ?>">
			<input type="hidden" name="request" value="save">
			<input type="submit" value="Save" />
		</div>
	</form>
</div>

<?php include_layout_template('footer.php'); ?>