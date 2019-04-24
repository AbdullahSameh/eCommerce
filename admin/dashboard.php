<?php 
	
	ob_start();

	session_start();

	if (isset($_SESSION['Username'])) {

		$pageTitle = 'Dashboard';
		
		include 'init.php';
?>
		<h1 class="text-center">Dashboard</h1>
		<div class="container text-center main-status">
			<div class="row">
				<div class="col-md-3">
					<div class="status" style="background-color: #3498db;">
						Total Members
						<span>
							<i class="fa fa-users fa-fw"></i>
							<a href="members.php"> <?php echo countItem('UserID', 'users') ?> </a>
						</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="status" style="background-color: #e67e22;">
						Pending Members
						<span>
							<i class="fa fa-user-plus fa-fw"></i>
							<a href="members.php?do=Manage&&page=Pending"> <?php echo checkItem('RegStatus', 'users', 0) ?> </a>
						</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="status" style="background-color: #9b59b6;">
						Total Items
						<span>
							<i class="fa fa-tags fa-fw"></i>
							<a href="items.php"> <?php echo countItem('Item_ID', 'items') ?> </a>
						</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="status" style="background-color: #e74c3c;">
						Total Comments
						<span>
							<i class="fa fa-comments fa-fw"></i>
							<a href="comments.php"> <?php echo countItem('Com_ID', 'comments') ?> </a>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="container latest">
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-group"></i> Latest Registerd Users
							<span class="add-toggle float-right">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="card-body">
							<ul class="list-unstyled latest-card">
								<?php
									$latestUser = getLatest('*', 'users','UserID', 4);

									if (!empty($latestUser)) {

										foreach ($latestUser as $user) {

											echo '<li>';
												echo $user['Username'];
												echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '" class="btn btn-info float-right"><i class="fa fa-pencil-square-o"></i> Edit</a>';
												if ($user['RegStatus'] == 0) {

													echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class="btn btn-success float-right"><i class="fa fa-check"></i> Activate</a>';
												}
											echo '</li>';
										}
									} else {

										echo 'There is no <strong>Users</strong> to show';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-tag"></i> Latest Items
							<span class="add-toggle float-right">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="card-body">
							<ul class="list-unstyled latest-card">
								<?php
									$latestItem = getLatest('*', 'items', 'Item_ID');

									if (!empty($latestItem)) {

										foreach ($latestItem as $item) {
											echo '<li>';
												echo $item['Name'];
													echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" class="btn btn-info float-right"><i class="fa fa-pencil-square-o"></i> Edit</a>';
													if ($item['Approve'] == 0) {

														echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-success float-right"><i class="fa fa-check"></i> Activate</a>';
													}
											echo '</li>';
										}
									} else {

										echo 'There is no <strong>Items</strong> to show';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-comments fa-fw"></i> Latest Comments
							<span class="add-toggle float-right">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="card-body">
							
							<?php
								$stmt = $con->prepare("SELECT comments.*, users.Username AS Member_name FROM comments
								INNER JOIN users ON users.UserID = comments.Member_id
								ORDER BY Com_ID DESC");
								$stmt->execute();
								$comments = $stmt->fetchAll();

								if (!empty($comments)) {

									foreach ($comments as $comment) {

										echo '<div class="comment">';
											echo '<span class="member-n">' .$comment['Member_name'] . '</span>';
											echo '<p class="member-c">' .$comment['Comment'] . '</p>';
											/*echo '<a href="comments.php?do=Edit&comid=' . $comment['Com_ID'] . '" class="btn btn-info float-right"><i class="fa fa-pencil-square-o"></i> Edit</a>';
											if ($comment['Status'] == 0) {

												echo '<a href="comments.php?do=Approve&comid=' . $comment['Com_ID'] . '" class="btn btn-success float-right"><i class="fa fa-check"></i> Activate</a>';
											}*/
										echo '</div>';
									}
								} else {

									echo 'There is no <strong>Comments</strong> to show';
								}
							?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
		include $tpl . "footer.php";
		
	} else {

		header('location: index.php');
		exit();
	}

	ob_end_flush();
