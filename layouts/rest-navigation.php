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
	$msg1 = "<a href='userest-order-open.php'>Open orders</a>";
	$msg2 = "<a href='userest-order-close.php'>Closed orders</a>";
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
			</ul>
			<ul class="nav navbar-nav navbar-right">
		        <li><a href="logout.php"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['first_name']; ?></a></li>
		        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
	    	</ul>
		</div>
	</div>
</nav>
<br><br>