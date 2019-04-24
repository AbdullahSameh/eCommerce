<?php 
	ob_start();
	session_start();
	$pageTitle = 'Create New Itme';
	include "init.php";
	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$errorForm = array();

			$name	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 	= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 	= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$made 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$stat 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$categ 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

			if (empty($name)) {
				$errorForm[] = 'Item Name Is <strong>Empty</strong>';
			}
			if (empty($desc)) {
				$errorForm[] = 'Description Is <strong>Empty</strong>';
			}
			if (empty($price)) {
				$errorForm[] = 'Price Is <strong>Empty</strong>';
			}
			if (empty($made)) {
				$errorForm[] = 'Email Is <strong>Empty</strong>';
			}
			if ($stat == 0) {
				$errorForm[] = 'Choose The <strong>Status</strong>';
			}
			if ($categ == 0) {
				$errorForm[] = 'Choose <strong>Category</strong>';
			}

			if (empty($errorForm)) {
				//Insert Item Into Database
				$stmt = $con->prepare("INSERT INTO 
										items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID) 
										VALUES(:zname, :zdesc, :zprice, :zcountry, :zstat, now(), :zcat, :zmember)");
				$stmt->execute(array(

					'zname' 	=> $name,
					'zdesc' 	=> $desc,
					'zprice' 	=> $price,
					'zcountry' 	=> $made,
					'zstat' 	=> $stat,
					'zcat' 		=> $categ,
					'zmember' 	=> $_SESSION['userid']
				));
			}

		}
?>	
		<h1 class="text-center"><?php echo $pageTitle ?></h1>

		<div class="info">
			<div class="container">
				<div class="card box">
					<div class="card-header bg-primary"><?php echo $pageTitle ?></div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-8">
								<!-- Start Create Item Form -->
								<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Item Name</label>
										<div class="col-sm-9 col-md-8">
											<input class="form-control live" data-class="title" type="text" name="name" placeholder="Item Name" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Description</label>
										<div class="col-sm-9 col-md-8">
											<input class="form-control live" data-class="desc" type="text" name="description" placeholder="Description Your Item" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Price</label>
										<div class="col-sm-9 col-md-8">
											<input class="form-control live" data-class="price" type="text" name="price" placeholder="Item Price" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Country Made</label>
										<div class="col-sm-9 col-md-8">
											<input class="form-control" type="text" name="country" placeholder="Made in" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Status</label>
										<div class="col-sm-9 col-md-8">
											<select class="form-control" name="status">
												<option value="0">...</option>
												<option value="1">New</option>
												<option value="2">Like New</option>
												<option value="3">Used</option>
												<option value="4">Very Old</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Category</label>
										<div class="col-sm-9 col-md-8">
											<select class="form-control" name="category">
												<option value="0">...</option>
												<?php
													$cats = getAll('*', 'category', '', '', 'ID', 'ASC');
													foreach ($cats as $cat) {

														echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<div class="offset-3 col-9">
											<input class="btn btn-primary" type="submit" value="Create Item">
										</div>
									</div>
								</form>
								<!-- End Create Item Form -->
							</div>
							<div class="col-md-4">
								<!-- Satrt Craete Item Live -->
								<div class="card live-item">
									<span class="price-box">
										$<span class="price">000</span>
									</span>
									<img class="card-img-top" src="user.jpg" alt="Card">
									<div class="card-body">
										<h4 class="title">Title</h4>
										<p class="desc">Description</p>
									</div>
								</div>
								<!-- End Create Item Live -->
							</div>
						</div>
						<?php
							if (!empty($errorForm)) {
								foreach ($errorForm as $error) {
									echo '<div class="alert alert-danger">' . $error . '</div>';
								}
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