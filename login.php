<?php
	ob_start();
	session_start();
	$pageTitle = 'log In';

	if (isset($_SESSION['user'])) {
		header('location:index.php');
	}
	include "init.php";

	// Check Request 

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['signin'])) {

			$userMember = $_POST['username'];
			$passMember = $_POST['password'];

			$hashPass = sha1($passMember);

			// Chwck if the user exist or not

			$stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ?");
			$stmt->execute(array($userMember, $hashPass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {

				$_SESSION['user'] = $userMember;
				$_SESSION['userid'] = $get['UserID'];
				header('location:index.php');
				exit();
			}
		} else {

			$newUser 	= $_POST['user'];
			$emailUser 	= $_POST['email'];
			$passOne 	= $_POST['passone'];
			$passTwo 	= $_POST['passtwo'];

			$formErrors = array();

			if (isset($newUser)) {
				
				$filterUser = filter_var($newUser, FILTER_SANITIZE_STRING);
				if (strlen($filterUser) < 3) {
					$formErrors[] = 'Username Must Be More Than 3 Letters';
				}
			}
			if (isset($emailUser)) {
				
				$filterEmail = filter_var($emailUser, FILTER_SANITIZE_EMAIL);
				if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) {
					$formErrors[] = 'This Email IS Not Valid';
				}
			}
			if (isset($passOne) && isset($passTwo)) {

				if (empty($passOne)) {
					$formErrors[] = 'Password Is Empty';
				}
				$hashPassOne = sha1($passOne);
				$hashPassTwo = sha1($passTwo);

				if ($hashPassOne !== $hashPassTwo) {
					$formErrors[] = 'Password Must Be Similar';
				}
			}
			if (empty($formErrors)) {

				// Check If New Username Is Exist Or Not
				$check = checkItem('Username', 'users', $newUser);

				if ($check == 1 ) {

					$formErrors[] = 'Sorry This User Already Exist';

				} else {

					// Insert Memebers To Database
					$insertUser = $con->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Date)
											VALUES(:suser, :spass, :smail, 0, now())");
					$insertUser->execute(array(

						'suser' => $newUser,
						'spass' => sha1($passOne),
						'smail' => $emailUser
					));
					$successEnter = 'Congratulations You Are A New Member Now';
				}
			}
		}
	}
?>
	<!--Start Log In Form-->
	<div class="container">
		<div class="login-card text-center">
			<div class="login-head">
				<div data-class="signup">Sign Up</div>
				<div data-class="signin" class="selected">Sign In</div>
			</div>
			<div class="login-body">

				<form class="signin" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
					<h4>Log Into Your Account</h4>
					<input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" required>
					<input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" required>
					<input class="btn btn-primary btn-block" type="submit" name="signin" value="Sign In">
				</form>

				<form class="signup" action="<?php //echo $_SERVER['PHP_SELF'] ?>" method="POST">
					<h4>Create Your Account</h4>
					<input class="form-control" type="text" name="user" placeholder="Create Your Username" autocomplete="off" required>
					<input class="form-control" type="email" name="email" placeholder="Write Your Email" required>
					<input class="form-control" type="password" name="passone" placeholder="Create Your Password" autocomplete="new-password" required>
					<input class="form-control" type="password" name="passtwo" placeholder="Confirm Your Password" autocomplete="new-password" required>
					<input class="btn btn-primary btn-block" type="submit" name="signup" value="Sign Up">
				</form>
				
			</div>
		</div>
	</div>
	<!--End Log In Form-->
	<!--Start Error form-->
	<div class="container">
		<div class="error text-center">
			<?php
				if (!empty($formErrors)) {
					foreach ($formErrors as $error) {
						echo '<p class="alert alert-danger">' . $error . '</p>';
					}
				}
				if (isset($successEnter)) {
					echo '<p class="alert alert-danger">' . $successEnter . '</p>';
				}
			?>
		</div>
	</div>
	<!--End Error Form-->

<?php 
	include $tpl . "footer.php";
	ob_end_flush();
?>