<?php
global $session;
if (!$session->is_logged_in()) {
	$msg1 = "<li><a href='login.php'>Login</a></li>";
	$msg2 = "";
	$msg3 = "";
	$msg4 = "";
	$msg5 = "";
	$msg6 = "";
	$msg7 = "";
	$msg8 = "";
} else {
	$msg1 = "<li><a href='bike-order-open.php'>Abiertos</a></li>";
	$msg2 = "<li><a href='bike-order-close.php'>Cerrados</a></li>";
	$msg3 = "<li><a href='bike-order-deleted.php'>Eliminados</a></li>";
	$msg4 = "<li><a href='bike-restaurant.php'>Restaurantes</a></li>";
	$msg5 = "<li><a href='logout.php'>Logout</a></li>";
}
?>
<div id="navigation">
	<ul>
		<?php echo $msg1; ?>
		<?php echo $msg2; ?>
		<?php echo $msg3; ?>
		<?php echo $msg4; ?>
		<?php echo $msg5; ?>
		</ul>
</div>