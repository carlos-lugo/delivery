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
	$msg1 = "<li><a href='order-create.php'>Nuevo pedido</a></li>";
	$msg2 = "<li><a href='order-open.php'>Abiertos</a></li>";
	$msg3 = "<li><a href='order-close.php'>Cerrados</a></li>";
	$msg4 = "<li><a href='order-deleted.php'>Eliminados</a></li>";
	$msg5 = "<li><a href='restaurant.php'>Restaurantes</a></li>";
	$msg6 = "<li><a href='user.php'>Personal</a></li>";
	$msg7 = "<li><a href='config.php'>Configuraciones</a></li>";
	$msg8 = "<li><a href='logout.php'>Logout</a></li>";
}
?>
<div id="navigation">
	<ul>
		<li><a href="index.php">Inicio</a></li>
		<?php echo $msg1; ?>
		<?php echo $msg2; ?>
		<?php echo $msg3; ?>
		<?php echo $msg4; ?>
		</ul>
</div>

<div id="navigation">
	<ul>
		<?php echo $msg5; ?>
		<?php echo $msg6; ?>
		<?php echo $msg7; ?>
		<?php echo $msg8; ?>
		</ul>
</div>