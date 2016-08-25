<?php require_once("includes/initialize.php"); 

if(!$session->is_logged_in()) {
	redirect_to("index.php");
}

if ($_GET['id_order']) {
	$id = $_GET['id_order'];
	$order = Orders::find_by_what('id', $id);
	$restaurant = Restaurant::find_by_what('id', $order->id_rest);
	$bike = User::find_by_what('id', $order->id_bike);
	$author = User::find_by_what('id', $_SESSION['user_id']);
	$body = "";	

	if (isset($_POST['body'])) {
		$body = trim($_POST['body']);

		$new_comment = Comment::make($author->id, $order->id, $body);
		if($new_comment && $new_comment->save()) {
			// comment saved
			// No message needed; seeing the comment is proof enough.

			// Send email
			//$new_comment->try_to_send_notification();

			// Important!  You could just let the page render from here. 
			// But then if the page is reloaded, the form will try 
			// to resubmit the comment. So redirect instead:
			redirect_to("bike-order-details.php?id_order={$order->id}");
		} else {
			// Failed
			$message = "Error, the comment was not saved.";
		}
	}
	
} else { redirect_to('bike-order-open.php'); }

$comments = $order->comments();

include_layout_template('header.php');

?>

<?php include_layout_template('bike-navigation.php'); ?>
	
<div class="container">
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Order details</h3>
	<table class="table table-hover table-responsive table-striped">
		<tr>
			<td>Order ID</td>
			<td><?php echo $order->id; ?></td>
		</tr>
		<tr>
			<td>Restaurant</td>
			<td><?php echo $restaurant->name; ?></td>
		</tr>
		<tr>
			<td>Street</td>
			<td><?php echo $order->street_name; ?></td>
		</tr>
		<tr>
			<td>Number</td>
			<td><?php echo $order->street_number; ?></td>
		</tr>
		<tr>
			<td>Postal code</td>
			<td><?php echo $order->postal_code; ?></td>
		</tr>
		<tr>
			<td>Door number</td>
			<td><?php echo $order->apartment_number; ?></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><?php echo $order->phone; ?></td>
		</tr>
		<tr>
			<td>Reception time</td>
			<td><?php echo $order->reception_time; ?></td>
		</tr>
		<tr>
			<td>Requested delivery time</td>
			<td><?php echo $order->accepted_to_time; ?></td>
		</tr>
		<tr>
			<td>Order registration time</td>
			<td><?php echo $order->register_time; ?></td>
		</tr>
		<tr>
			<td>Order assign time</td>
			<td><?php echo $order->assigned_time; ?></td>
		</tr>
		<tr>
			<td>Finish time</td>
			<td><?php echo $order->finished_time; ?></td>
		</tr>
		<tr>
			<td>Delivery boy</td>
			<td><?php 
				if ($bike) { 
					echo $bike->full_name();
				} else {
					echo 'Sin asignar';
				}
			?></td>
		</tr>
		<tr>
			<td>Delivery price</td>
			<td><?php echo $order->cost; ?></td>
		</tr>
		<tr>
			<td>Delivery boy earn</td>
			<td><?php echo $order->bike_profit; ?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><?php echo $order->status; ?></td>
		</tr>
	</table>
	<!-- <form action="order-edit.php" method="POST">
		<input type="hidden" name="action" value="edit">
		<input type="hidden" name="id_order" value="<?php echo $order->id; ?>">
		<input type="submit" value="Editar" class="btn btn-primary" />
	</form> -->

	<br>
	<h3>Order comments</h3>
		<?php foreach($comments as $comment): ?>
		<?php 
			if ($comment->status == 'Restaurante') {
				$comment_user = Restaurant::find_by_what('id', $comment->id_user);
			} else {
				$comment_user = User::find_by_what('id', $comment->id_user);
			}
		?>
			<p><?php echo htmlentities($comment_user->full_name()); ?> wrote:</p>
			<blockquote>
				<?php echo strip_tags($comment->text, '<strong><em><p>'); ?>
				<footer><?php echo datetime_to_text($comment->created); ?></footer>
			</blockquote>
		<?php endforeach; ?>
		<?php if(empty($comments)) { echo "No comments so far."; } ?>

	<div id="comment-form">
		<h3>Write comment</h3>
		<form action="bike-order-details.php?id_order=<?php echo $order->id; ?>" method="POST" class="form-horizontal" role="form">
			<div class="form-group">
			<label class="control-label col-sm-2" for="name">Name: </label>
		    <div class="col-sm-4">
			    <input id="name" type="text" value="<?php echo $author->full_name(); ?>" class="form-control" readonly />
			</div>
		</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="body">Comment: </label>
			    <div class="col-sm-4">
				    <textarea id="body" name="body" rows="5" value="<?php echo htmlentities($body); ?>" class="form-control" /></textarea>
				</div>
			</div>
			<input type="hidden" name="status" value="Activo">
		    <div class="form-group">
		    	<div class="col-sm-offset-2 col-sm-10">
		   			<input type="submit" name="submit" value="Guardar comentario" class="btn btn-primary" />
		   		</div>
		   	</div>
		</form>
	</div>

</div>


<?php include_layout_template('footer.php'); ?>




			