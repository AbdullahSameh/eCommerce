<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php getTitle() ?></title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css"/>
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css"/>
		<link rel="stylesheet" href="<?php echo $css; ?>front-style.css"/>
	</head>
	<body>

		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<a class="navbar-brand" href="index.php">PC Store</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="adminNav">
					<ul class="navbar-nav ml-auto">
						<?php
							$allCat = getAll("*", "category", "WHERE Parent = 0", "", "ID", "ASC");
							foreach ($allCat as $cat) {

								echo '<li class="nav-item">
										<a class="nav-link" href="category.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a>
									</li>';
							}
							if (isset($_SESSION['user'])) { ?>
								<ul class="navbar-nav">
									<li class="nav-item dropdown open">
										<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<img class="img-fluid" src="com_user.png" style="width: 30px">
										<?php echo $sessionUser ?>
										</a>
										<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
											<a class="dropdown-item" href="profile.php">Profile</a>
											<a class="dropdown-item" href="newad.php">Create Item</a>
											<a class="dropdown-item" href="logout.php">logout</a>
										</div>
									</li>
								</ul>
						<?php } else { ?>
								<ul class="navbar-nav">
									<li class="nav-item">
										<a class="nav-link" href="login.php">Sign In</a>
									</li>
								</ul>
						<?php } ?>
					</ul>	
				</div>
			</div>
		</nav>
		