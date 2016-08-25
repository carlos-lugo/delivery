<?php require_once("includes/initialize.php");

if(!$session->is_logged_in()) {
	redirect_to("index.php");
}

//$bike_array = User::find_all();
$total_cost = 0;
$total_bike_profit = 0;
$lapse = 'day';
$id_rest = $_SESSION['user_id'];

if(isset($_POST['lapse'])) {
	//$id_bike = $_POST['id_bike'];
	$lapse = $_POST['lapse'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	
	if ($lapse=='day') {
		$orders_array = Orders::find_all_from_day('register_time', $year, $month, $day);
	} else {
		$orders_array = Orders::find_all_from_month('register_time', $year, $month);
	}

	if ($id_rest!=0) {
		$rest = Restaurant::find_by_what('id', $id_rest);
		foreach($orders_array as $key => $value) {
			if ($value->id_rest != $id_rest) {
				unset($orders_array[$key]);
			}
		}
	}

	foreach($orders_array as $key => $value) {
		if ($value->status != 'Cerrado') {
			unset($orders_array[$key]);
		}
	}

} else {
	$day = strftime("%d", time());
	$month = strftime("%m", time());
	$year = strftime("%Y", time());
	$orders_array = null;
	$orders_array = Orders::find_all_from_day('register_time', $year, $month, $day);

	if ($id_rest!=0) {
		$rest = Restaurant::find_by_what('id', $id_rest);
		foreach($orders_array as $key => $value) {
			if ($value->id_rest != $id_rest) {
				unset($orders_array[$key]);
			}
		}
	}

	foreach($orders_array as $key => $value) {
		if ($value->status != 'Cerrado') {
			unset($orders_array[$key]);
		}
	}
}

include_layout_template('header.php');

?>

<?php include_layout_template('rest-navigation.php'); ?>
<div class="container">
	
	<?php if($message) { echo '<div class="alert alert-info">'.output_message($message).'</div>';} ?>
	<h3>Closed orders:</h3>
	<form id="filter" action="userest-order-close.php" method="POST" class="form-horizontal" role="form">
		<h4>Search filter</h4>
		<div class="form-group">
	    	<label class="control-label col-sm-2" for="autocomplete">Report from:</label> 
	    	<div class="col-sm-3">
	    		<label class="radio-inline">
					<input type="radio" name="lapse" value="day" <?php if($lapse=='day') echo 'checked'; ?>>Day
    			</label>
    			<label class="radio-inline">
					<input type="radio" name="lapse" value="month" <?php if($lapse=='month') echo 'checked'; ?>>Month<br>
    			</label>
    		</div>
    	</div>
    	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
				<input type="number" name="day" min="1" max="31" value="<?php echo $day; ?>" style="width: 50px;"> 
				<select name="month" form="filter">
					<option value="01" <?php if($month==1) echo 'selected'; ?>>January</option>
					<option value="02" <?php if($month==2) echo 'selected'; ?>>February</option>
					<option value="03" <?php if($month==3) echo 'selected'; ?>>March</option>
					<option value="04" <?php if($month==4) echo 'selected'; ?>>April</option>
					<option value="05" <?php if($month==5) echo 'selected'; ?>>May</option>
					<option value="06" <?php if($month==6) echo 'selected'; ?>>June</option>
					<option value="07" <?php if($month==7) echo 'selected'; ?>>July</option>
					<option value="08" <?php if($month==8) echo 'selected'; ?>>August</option>
					<option value="09" <?php if($month==9) echo 'selected'; ?>>September</option>
					<option value="10" <?php if($month==10) echo 'selected'; ?>>October</option>
					<option value="11" <?php if($month==11) echo 'selected'; ?>>November</option>
					<option value="12" <?php if($month==12) echo 'selected'; ?>>December</option>
				</select> 
				<select name="year" form="filter">
					<option value="2015" <?php if($year==2015) echo 'selected'; ?>>2015</option>
					<option value="2016" <?php if($year==2016) echo 'selected'; ?>>2016</option>
					<option value="2017" <?php if($year==2017) echo 'selected'; ?>>2017</option>
				</select>
	    	</div>
		</div>
		<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" value="Search" class="btn btn-primary" />
	   		</div>
	   	</div>
	</form>
	<h4>Resultados de b&uacute;squeda</h4>
	<?php
		if($orders_array) {
			echo '<div class="table-responsive">';
			$table_header  = '<table class="table table-hover"><tr>';
			$table_header .= '<th>Rest</th>';
			$table_header .= '<th>Address</th>';
			$table_header .= '<th>To</th>';
			$table_header .= '<th></th>';
			$table_header .= '</tr>';
			echo $table_header;
			$row_count = 0;
			foreach($orders_array as $key => $value) {
				$row_count = $row_count + 1;
				$restaurant = Restaurant::find_by_what('id', $value->id_rest);
				$bike = User::find_by_what('id', $value->id_bike);
				$table_body  = '<tr>';
				$table_body .= '<td>'.$restaurant->name.'</td>';
				$table_body .= '<td>'.$value->street_name.' '.$value->street_number.'<br>cp: '.$value->postal_code.'</td>';
				$table_body .= '<td>'.date('H:i', strtotime($value->accepted_to_time)).'</td>';
				
				$table_body .= '<td>';
				$table_body .= '<table><tr>';
				$table_body .= '<td><form action="userest-order-details.php" method="GET">';
				$table_body .= '<input type="hidden" name="id_order" value="'.$value->id.'">';
				$table_body .= '<input type="submit" value="Details" class="btn btn-primary btn-xs" />';
				$table_body .= '</form></td>';
				$table_body .= '</tr></table>';
				$table_body .= '</td>';
				$table_body .= '</tr>';
				echo $table_body;
				$total_cost = $total_cost + $value->cost;
				$total_bike_profit = $total_bike_profit + $value->bike_profit;
			}
			echo '</table></div>';
			echo '<p class="bg-primary">Total revenue: '.$total_cost.' Euros</p>';
			// echo '<p class="bg-primary">Ganancia total del repartidor: '.$total_bike_profit.' Euros</p><br>';
		} else {
			echo "<p>The list is empty.</p>";
		}
	?>
</div>

<?php include_layout_template('footer.php'); ?>