<?php 
	ob_start();
	session_start();
	$pageTitle = 'Create New Itme';
	include "init.php";

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	$stmt = $con->prepare("SELECT items.*, category.Name AS Cat_Name, users.Username FROM items 
							INNER JOIN category ON category.ID = items.Cat_ID
							INNER JOIN users ON users.UserID = items.Member_ID
							WHERE Item_ID = ? AND Approve = 1");
	$stmt->execute(array($itemid));
	$count = $stmt->rowCount();

	if ($count > 0) {

		$item = $stmt->fetch();			
?>	
	<h1 class="text-center"><?php echo $item['Name'] ?></h1>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<img class="img-thumbnail" src="user.jpg">
			</div>
			<div class="offset-1 col-md-8 item-info">
				<h2><?php echo $item['Name'] ?></h2>
				<p><?php echo $item['Description'] ?></p>
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Added Date</span> : <?php echo $item['Add_Date'] ?>
					</li>
					<li>
						<i class="fa fa-money fa-fw"></i>
						<span>Price</span> : <?php echo $item['Price'] ?>
					</li>
					<li>
						<i class="fa fa-map-marker fa-fw"></i>
						<span>Made in</span> : <?php echo $item['Country_Made'] ?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Category</span> : <a href="category.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Cat_Name'] ?></a>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Added By</span> : <a href=""><?php echo $item['Username'] ?></a>
					</li>
				</ul>
			</div>
		</div>
		<?php if (isset($_SESSION['user'])) { ?>
		<div class="row">
			<div class="offset-4 col-md-8">
				<div class="add-comment">
					<h3>Add Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
						<textarea name="comment" required></textarea>
						<input class="btn btn-primary" type="submit" value="Add Comment">
					</form>
					<?php
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {

							$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
							$itemCom = $item['Item_ID'];
							$userCom = $_SESSION['userid'];

							if (!empty($comment)) {
								$stmt = $con->prepare("INSERT INTO 
														comments(Comment, Status, Com_date, Item_id, Member_id)
														VALUES(:ccomment, 0, now(), :citem, :cuser)");
								$stmt->execute(array(
									'ccomment' 	=> $comment,
									'citem' 	=> $itemCom,
									'cuser' 	=> $userCom
								));
								if ($stmt) {
									echo '<div class="alert alert-success">Comment Added</div>';
								}
							}
						}
					?>
				</div>
			</div>
		</div>
		<?php } else {
			echo '<div class="text-center">';
				echo '<a href="login.php">Sign In</a> or <a href="login.php">Sign Up</a> To Add Comment';
			echo '</div>';
		} ?>
		<hr>
		<?php
			$comStmt = $con->prepare("SELECT comments.*, users.Username AS Member_name FROM comments 
										INNER JOIN users ON users.UserID = comments.Member_id
										WHERE Item_id = ? 
										ORDER BY Com_ID DESC");
			$comStmt->execute(array($item['Item_ID']));
			$coms = $comStmt->fetchAll();

			foreach ($coms as $com) { ?>
				<div class="comment">
					<div class="row">
						<div class="col-md-3 text-center">
							<img class="img-thumbnail" src="com_user.png">
							<?php echo $com['Member_name'] ?>
						</div>
						<div class="col-md-9">
							<div class="com-box">
							<?php echo '<p>' . $com['Comment'] . '</p>' ?>
							</div>
						</div>
					</div>
				</div>
				<hr>
		<?php } ?>
	</div>	
<?php
	} else {
		echo '<div class="container">';
			echo '<div class="page-message text-center">';
				echo '<i class="fa fa-exclamation-triangle"></i>';
				echo '<p>There Is No Item To Show</p>';
			echo'</div>';
		echo '</div>';		
	}
	include $tpl . "footer.php";
	ob_end_flush();
?>