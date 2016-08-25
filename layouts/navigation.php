<?php
global $session;
if (!$session->is_logged_in()) {
	$msg1 = "<a href='login.php'>Login</a>";
	$msg2 = "";
	$msg3 = "";
	$msg4 = "";
	$msg5 = "";
	$msg6 = "";
	$msg7 = "";
	$msg8 = "";
} else {
	$msg1 = "<a href='order-create.php'>New order</a>";
	$msg2 = "<a href='order-open.php'>Open</a>";
	$msg3 = "<a href='order-close.php'>Closed</a>";
	$msg4 = "<a href='order-deleted.php'>Deleted</a>";
	$msg5 = "<a href='config.php'>Config</a>";
	$msg6 = "<a href='reset-db.php'><mark><b>Reset database</b></mark></a>";
}
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>                        
			</button>
			<a class="navbar-brand">Delivery Logistic</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li><?php echo $msg1; ?></li>
				<li><?php echo $msg2; ?></li>
				<li><?php echo $msg3; ?></li>
				<li><?php echo $msg4; ?></li>
				<li><?php echo $msg5; ?></li>
				<li><?php echo $msg6; ?></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
		        <li><a href="#"><span class="glyphicon glyphicon-user"></span> 
		        	<?php if (isset($_SESSION['first_name'])) { echo $_SESSION['first_name'];
		        	} else { echo 'Guest'; } ?>
		        </a></li>
		        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
	    	</ul>
		</div>
	</div>
</nav>
<br><br>