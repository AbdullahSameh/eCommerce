<?php 

	/*
	*************************************
  	******** Manage Member Page *********
	** For Add | Edit | Delete Members **
	*************************************
	*/
	ob_start();

	session_start();

	$pageTitle = 'Members';

	if (isset($_SESSION['Username'])) {
		
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		
		if ($do == 'Manage') { // Manage Members Page

			$query ='';

			if (isset($_GET['page']) && $_GET['page'] = 'Pending') {

				$query = 'AND RegStatus = 0';
			}

			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
			$stmt->execute();
			$rows = $stmt->fetchAll();

			if (!empty($rows)) {
?>			
				<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="table text-center table-bordered">
							<thead class="thead-dark">
								<tr>
									<th>#ID</th>
									<th>Username</th>
									<th>Email</th>
									<th>Full Name</th>
									<th>Registerd Date</th>
									<th>Control</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($rows as $row) {
										echo '<tr>';
											echo '<td>' . $row['UserID'] . '</td>';
											echo '<td>' . $row['Username'] . '</td>';
											echo '<td>' . $row['Email'] . '</td>';
											echo '<td>' . $row['FullName'] . '</td>';
											echo '<td>' . $row['Date'] . '</td>';
											echo '<td>
													<a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class="btn btn-info"><i class="fa fa-pencil-square-o"></i> Edit</a>
													<a href="members.php?do=Delete&userid=' . $row['UserID'] . '" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>';
													if ($row['RegStatus'] == 0) {

														echo ' <a href="members.php?do=Activate&userid=' . $row['UserID'] . '" class="btn btn-success"><i class="fa fa-check"></i> Activate</a>';
													}
											echo '</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
					<a class="btn btn-primary add" href="members.php?do=Add"><i class="fa fa-plus"></i> Add New Member</a>
				</div>
<?php	
			} else {

				echo '<div class="container">';
					echo '<div class="page-message text-center">';
						echo '<i class="fa fa-exclamation-triangle"></i>';
						echo '<p>There Is No Users To Show</p>';
					echo'</div>';
					echo '<a class="btn btn-primary add" href="members.php?do=Add"><i class="fa fa-plus"></i> Add New Member</a>';
				echo '</div>';
			}
		} elseif ($do == 'Add') { // Add Page
?>
			<h1 class="text-center">Add Member</h1>
			<div class="container">
				<form action="?do=Insert" method="POST">

					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10 col-md-4">
							<input class="password form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" required="required">
							<i class="fa fa-eye show-pass"></i>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="email" placeholder="Email" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Full Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="full" placeholder="Your Name" required="required">
						</div>
					</div>
					<div class="form-group row">
						<div class="ml-auto col-sm-10">
							<input class="btn btn-primary" type="submit" value="Add Member">
						</div>
					</div>
				</form>
			</div>
<?php	} elseif ($do == 'Insert') { // Insert Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo '<h1 class="text-center">Insert Member</h1>';
				echo '<div class="container">';

				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				$hashPass = sha1($pass);

				//Validate Form
				$errorForm = array();

				if (empty($user)) {
					$errorForm[] = 'Username Is <strong>Empty</strong>';
				}
				if (empty($pass)) {
					$errorForm[] = 'Password Is <strong>Empty</strong>';
				}
				if (strlen($pass) < 3) {
					$errorForm[] = 'Password Must Be More Than 6 <strong>Character</strong>';
				}
				if (empty($email)) {
					$errorForm[] = 'Email Is <strong>Empty</strong>';
				}
				if (empty($name)) {
					$errorForm[] = 'Full Name Is <strong>Empty</strong>';
				}
				foreach ($errorForm as $error) {

					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($errorForm)) {

					// Check if the username in database 
					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger">Sorry This Username is Exist</div>';
						redirect_to($theMsg, 'back');
					} else {

						// Insert Memebers To Database
						$stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date)
												VALUES(:suser, :spass, :smail, :sname, 1, now()) ");
						$stmt->execute(array(

							'suser' => $user,
							'spass' => $hashPass,
							'smail' => $email,
							'sname' => $name
						));
						
						$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Inserted</div>';
						redirect_to($theMsg, 'back');
						echo '</div>';
					}
				}
			} else {
				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
		} elseif ($do == 'Edit') { // Edit Page 

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ( $count > 0) {
?>
				<h1 class="text-center">Edit Member</h1>
				<div class="container">
					<form action="?do=Update" method="POST">

						<input type="hidden" name="userid" value="<?php echo $userid ?>">

						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Username</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="username" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Password</label>
							<div class="col-sm-10 col-md-4">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
								<input class="form-control" type="password" name="newpassword" autocomplete="new-password">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Email</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="email" value="<?php echo $row['Email'] ?>" required="required">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Full Name</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="full" value="<?php echo $row['FullName'] ?>" required="required">
							</div>
						</div>
						<div class="form-group row">
							<div class="ml-auto col-sm-10">
								<input class="btn btn-primary" type="submit" value="Save">
							</div>
						</div>
					</form>
				</div>
<?php		} else {
				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry Theres IS No ID</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
		} elseif ($do == 'Update') { // Update Page

			echo '<h1 class="text-center">Update Member</h1>';
			echo '<div class="container">';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				//Get Variable From Form
				$id 	=$_POST['userid'];
				$user 	=$_POST['username'];
				$email 	=$_POST['email'];
				$name 	=$_POST['full'];

				//Password Trick
				$pass 	= empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				//Validate Form
				$errorForm = array();

				if (empty($user)) {
					$errorForm[] = 'Username Is <strong>Empty</strong>';
				}
				if (empty($email)) {
					$errorForm[] = 'Email Is <strong>Empty</strong>';
				}
				if (empty($name)) {
					$errorForm[] = 'Full Name Is <strong>Empty</strong>';
				}
				foreach ($errorForm as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($errorForm)) {

					$stmt = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
					$stmt->execute(array($user, $id));
					$count = $stmt->rowCount();

					if ($count == 1) {

						$theMsg = '<div class="alert alert-danger">Sorry This Username is Exist</div>';
						redirect_to($theMsg, 'back');

					} else {

						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
						$stmt->execute(array($user, $email, $name, $pass, $id));
						
						$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
						redirect_to($theMsg, 'back');
					}
				}
			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
			}
			echo '</div>';
			
		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Member</h1>';
			echo '<div class="container">';

			$userid = $_GET['userid'] && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			$check = checkItem('UserID', 'users', $userid);

			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
				$stmt->bindParam(":zuser", $userid);
				$stmt->execute();
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');

			} else {
				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
			echo '</div>';

		} elseif ($do == 'Activate') {

			echo '<h1 class="text-center">Activate Member</h1>';
			echo '<div class="container">';

			$userid = $_GET['userid'] && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			$check = checkItem('UserID', 'users', $userid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
				$stmt->execute(array($userid));
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');
			} else {
				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
			echo '</div>';
		}

		include $tpl . "footer.php";
		
	} else {

		header('location: index.php');
		exit();
	} 
	ob_end_flush();