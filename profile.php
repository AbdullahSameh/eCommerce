<?php 
	ob_start();
	session_start();
	$pageTitle = 'My Profile';
	include "init.php";

	if (isset($_SESSION['user'])) {

		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
?>
	
	<h1 class="text-center">My Profile</h1>

	<div class="profile">
		<div class="container">
			<div class="card box">
				<div class="card-header bg-primary">My Information</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-9">
							<ul class="list-unstyled pro-box">
								<li>
									<i class="fa fa-user fa-fw"></i>
									<span>Name</span> : <?php echo $info['Username']; ?>
								</li>
								<li>
									<i class="fa fa-envelope fa-fw"></i>
									<span>Email</span> : <?php echo $info['Email']; ?></li>
								<li>
									<i class="fa fa-id-card fa-fw"></i>
									<span>Full Name</span> : <?php echo $info['FullName']; ?>
								</li>
								<li>
									<i class="fa fa-calendar fa-fw"></i>
									<span>Register Date</span> : <?php echo $info['Date']; ?>
								</li>
								<li>
									<i class="fa fa-tags fa-fw"></i>
									<span>Favourite Category</span> : <?php //echo $info['']; ?>
								</li>
							</ul>
						</div>
						<div class="col-md-3">
							<img src="user.jpg" alt="" class="img-thumbnail pro-img">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="profile">
		<div class="container">
			<div class="card box">
				<div class="card-header bg-primary">My Ads</div>
				<div class="card-body">
					<?php
						$items = getAll("*", "items", "WHERE Member_ID = {$info['UserID']}", "", "Item_ID");
						if (!empty($items)) {
							echo '<div class="row">';
								foreach ($items as $item) { ?>
									<div class="col-sm-6 col-lg-3">
										<div class="card ad">
											<?php
											if ($item['Approve'] == 0) {
												echo '<span class="accept">Waiting Approval</span>';
											}
											?>
											<span class="price-box">$<?php echo $item['Price'] ?></span>
											<img class="card-img-top img-fluid" src="user.jpg" alt="Card-image">
											<div class="card-body">
												<h4><a class="item-link" href="item.php?itemid=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h4>
												<p><?php echo $item['Description'] ?></p>
												<div class="item-date"><?php echo $item['Add_Date'] ?></div>
											</div>
										</div>
									</div>	
						<?php   }
							echo '</div>';
						} else {
							echo '<p>There Is No Items To Show</p>';
							echo '<a class="btn btn-primary" href="newad.php">Create New AD</a>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="profile">
		<div class="container">
			<div class="card box">
				<div class="card-header bg-primary">Latest Comments</div>
				<div class="card-body">
					<?php
						$coms = getAll("Comment", "comments", "WHERE Member_id = {$info['UserID']}", "AND Status = 1", "Com_ID");
						if (!empty($coms)) {
							foreach ($coms as $com) {

								echo '<p>' . $com['Comment'] . '</p>';
							}
						} else {
							echo '<p>There Is No Comments To Show</p>';
						}
					?>
				</div>
			</div>
		</div>
	</div>

<?php
	} else {

		header('location: login.php');
		exit();
	}
	include $tpl . "footer.php";
	ob_end_flush();
?>