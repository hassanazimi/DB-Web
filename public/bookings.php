<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_staff_signed_in() ?>
<?php $booking_set = find_all_bookings(); ?>
<?php
if(isset($_POST["clear"])) {
	$result = delete_all_bookings();
	if($result) {
		$_SESSION["message"] = "Booking is cleared";
		redirect_to("bookings.php");
	} else {
		$_SESSION["errors"] = "Error!";
		redirect_to("bookings.php");
	}
} else {
}
?>
<?php include("../includes/layouts/header.php"); ?>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<section class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="staff.php">Staff Area</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="staff.php">Home</a></li>
				<li><a href="manage_content.php">Manage Productions</a></li>
				<li class="active"><a href="bookings.php">Bookings</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</section>
</nav>
<section class="container">
	<div class="row">
		<?php echo message(); ?><?php echo errors(); ?>
	</div>
	<?php if(mysqli_num_rows($booking_set) > 0): ?>
		<div class="row">
			<section class="main col-lg-12">
				<table class="table table-hover">
					<thead>
					<tr>
						<th>Booking ID</th>
						<th>Customer Name</th>
						<th>Performance Name</th>
						<th>Booking Date</th>
						<th>Status</th>
						<th>Purchased</th>
					</tr>
					</thead>
					<tbody>
					<?php while($booking = mysqli_fetch_assoc($booking_set)): ?>
						<tr>
							<td><p><?php echo htmlentities($booking["booking_id"]); ?></p></td>
							<td><p><?php echo htmlentities(find_member_by_id($booking["customer_id"])["customer_name"]); ?></p></td>
							<td><p><?php echo htmlentities(find_performance_by_id($booking["performance_id"])["performance_name"]); ?></p></td>
							<td><p><?php echo datetime_to_text(htmlentities($booking["booking_date"])); ?></p></td>
							<td><?php if($booking["status"]): ?>
									<?php if($booking["purchased"]){ ?>
										<a href="#" class='btn btn-success btn-xs' disabled>
											Approved
										</a>
									<?php } else { ?>
										<a href="approvals.php?id=<?php echo urldecode($booking["booking_id"]); ?>" class='btn btn-success btn-xs'>
											Approved
										</a>
									<?php } ?>
								<?php else: ?>
									<a href="approvals.php?id=<?php echo urldecode($booking["booking_id"]); ?>" class='btn btn-danger btn-xs'>
										Needs Approval
									</a>
								<?php endif; ?>
							</td>
							<td><?php if($booking["purchased"]) {echo "<p class='text-success'>Yes</p>";} else {echo "<p class='text-danger'>No</p>";} ?></td>
						</tr>
					<?php endwhile; ?>
					</tbody>
				</table>
			</section>
		</div>
		<hr/>
		<div class="pull-right panel panel-default">
			<div class="panel-body">
				<p class="text-danger" style="line-height: 20px;">
					Important Reminder: <br/>
					<small style="line-height: 20px;">
						Please use this button to clear the history after the performance is finished. <br/>
						Using this button will clear the all bookings customer made. <br/>
						Make sure you do this after all of your considerations.
					</small>
				</p>
				<form method="post" action="bookings.php">
					<button class="btn btn-danger pull-right" name="clear" type="submit" onclick="return confirm('Are you sure?')">Clear
					                                                                                                               History
					</button>
				</form>
			</div>
		</div>
	<?php else: ?>
		<h2>No Booking Yet</h2>
	<?php endif; ?>
	<br/><br/>
</section>

<?php include("../includes/layouts/footer.php"); ?>
