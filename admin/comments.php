<?php
	/*
	*************************************
  	******** Manage Comments Page *********
	** For ** | Edit | Delete Comments **
	*************************************
	*/
	ob_start();

	session_start();

	$pageTitle = 'Comments';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$stmt = $con->prepare("SELECT comments.*, items.Name AS Item_name, users.Username AS Member_name FROM comments
									INNER JOIN items ON items.Item_ID = comments.Item_id
									INNER JOIN users ON users.UserID = comments.Member_id
									ORDER BY Com_ID DESC");
			$stmt->execute();
			$coms = $stmt->fetchAll();

			if (!empty($coms)) {
?>
				<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="table text-center table-bordered">
							<thead class="thead-dark">
								<tr>
									<th>#ID</th>
									<th>Comment</th>
									<th>Item Name</th>
									<th>Member Name</th>
									<th>Adding Date</th>
									<th>Control</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($coms as $com) {
										echo '<tr>';
											echo '<td>' . $com['Com_ID'] . '</td>';
											echo '<td>' . $com['Comment'] . '</td>';
											echo '<td>' . $com['Item_name'] . '</td>';
											echo '<td>' . $com['Member_name'] . '</td>';
											echo '<td>' . $com['Com_date'] . '</td>';
											echo '<td>
													<a href="comments.php?do=Edit&comid=' . $com['Com_ID'] . '" class="btn btn-info"><i class="fa fa-pencil-square-o"></i> Edit</a>
													<a href="comments.php?do=Delete&comid=' . $com['Com_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>';
													if ($com['Status'] == 0) {

														echo ' <a href="comments.php?do=Approve&comid=' . $com['Com_ID'] . '" class="btn btn-success"><i class="fa fa-check"></i> Activate</a>';
													}
											echo '</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
<?php		
			} else {

				echo '<div class="container">';
					echo '<div class="page-message text-center">';
						echo '<i class="fa fa-exclamation-triangle"></i>';
						echo '<p>There Is No Comments To Show</p>';
					echo'</div>';
				echo '</div>';
			}

		} elseif ($do == 'Edit') {

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$stmt = $con->prepare("SELECT * FROM comments WHERE Com_ID = ? LIMIT 1");
			$stmt->execute(array($comid));
			$com = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {
?>
				<h1 class="text-center">Edit Comment</h1>
				<div class="container">
					<form action="?do=Update" method="POST">

						<input type="hidden" name="comid" value="<?php echo $comid ?>">

						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Comment</label>
							<div class="col-sm-10 col-md-4">
								<textarea class="form-control" name="Comment">
									<?php echo $com['Comment'] ?>
								</textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="ml-auto col-sm-10">
								<input class="btn btn-primary" type="submit" value="Save">
							</div>
						</div>
					</form>
				</div>
<?php				
			} else {
				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry Theres IS No ID</div>';
				redirect_to($theMsg);
				echo '</div>';
			}

		} elseif ($do == 'Update') {

			echo '<h1 class="text-center">Update Comment</h1>';
			echo '<div class="container">';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$comid 		= $_POST['comid'];
				$comment 	= $_POST['Comment'];

				$stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE Com_ID = ?");
				$stmt->execute(array($comment, $comid));

				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, "back");

			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
			}
			echo '</div>';

		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Comment</h1>';
			echo '<div class="container">';

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$check = checkItem('Com_ID', 'comments', $comid);

			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM comments WHERE Com_ID = :xcom");
				$stmt->bindParam(':xcom', $comid);
				$stmt->execute();
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
			}

			echo '</div>';

		} elseif ($do == 'Approve') {

			echo '<h1 class="text-center">Approve Comment</h1>';
			echo '<div class="container">';

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			$check = checkItem('Com_ID', 'comments', $comid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE Com_ID = ?");
				$stmt->execute(array($comid));
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
			}
			echo '</div>';
		}
		include $tpl . "footer.php";

	} else {

		header('location: index.php');
		exit();
	} 
	ob_end_flush();