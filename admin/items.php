<?php
	/*
	*************************************
  	******** Manage Items Page *********
	** For Add | Edit | Delete Items **
	*************************************
	*/
	ob_start();

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { 
			
			$stmt = $con->prepare("SELECT items.*, category.Name AS Cat_Name, users.Username FROM items 
									INNER JOIN category ON category.ID = items.Cat_ID 
									INNER JOIN users ON users.UserID = items.Member_ID
									ORDER BY Item_ID DESC");
			$stmt->execute();
			$items = $stmt->fetchAll();

			if (!empty($items)) {
?>
				<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="table text-center table-bordered">
							<thead class="thead-dark">
								<tr>
									<th>#ID</th>
									<th>Name</th>
									<th>Description</th>
									<th>Price</th>
									<th>Adding Date</th>
									<th>Category</th>
									<th>Username</th>
									<th>Control</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($items as $item) {
										echo '<tr>';
											echo '<td>' . $item['Item_ID'] . '</td>';
											echo '<td>' . $item['Name'] . '</td>';
											echo '<td>' . $item['Description'] . '</td>';
											echo '<td>' . $item['Price'] . '</td>';
											echo '<td>' . $item['Add_Date'] . '</td>';
											echo '<td>' . $item['Cat_Name'] . '</td>';
											echo '<td>' . $item['Username'] . '</td>';
											echo '<td>
													<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" class="btn btn-info"><i class="fa fa-pencil-square-o"></i> Edit</a>
													<a href="items.php?do=Delete&itemid=' . $item['Item_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>';
													if ($item['Approve'] == 0) {

														echo ' <a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-success"><i class="fa fa-check"></i> Activate</a>';
													}
											echo '</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
					<a class="btn btn-primary add" href="items.php?do=Add"><i class="fa fa-plus"></i> Add New Item</a>
				</div>
<?php
			} else {

				echo '<div class="container">';
					echo '<div class="page-message text-center">';
						echo '<i class="fa fa-exclamation-triangle"></i>';
						echo '<p>There Is No Items To Show</p>';
					echo'</div>';
					echo '<a class="btn btn-primary add" href="items.php?do=Add"><i class="fa fa-plus"></i> Add New Item</a>';
				echo '</div>';
			}

		} elseif ($do == 'Add') {
?>			
			<h1 class="text-center">Add Item</h1>
			<div class="container">
				<form action="?do=Insert" method="POST">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Item Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" placeholder="Item Name" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Description</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="description" placeholder="Description Your Item" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Price</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="price" placeholder="Item Price" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Country Made</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="country" placeholder="Made in" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Status</label>
						<div class="col-sm-10 col-md-4">
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
						<label class="col-sm-2 col-form-label">Member</label>
						<div class="col-sm-10 col-md-4">
							<select class="form-control" name="member">
								<option value="0">...</option>
								<?php
									$stmt = $con->prepare("SELECT * FROM users");
									$stmt->execute();
									$users = $stmt->fetchAll();
									foreach ($users as $user) {

										echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Category</label>
						<div class="col-sm-10 col-md-4">
							<select class="form-control" name="category">
								<option value="0">...</option>
								<?php
									$stmt = $con->prepare("SELECT * FROM category");
									$stmt->execute();
									$cats = $stmt->fetchAll();
									foreach ($cats as $cat) {

										echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="ml-auto col-sm-10">
							<input class="btn btn-primary" type="submit" value="Add Item">
						</div>
					</div>
				</form>
			</div>
<?php			
		} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == "POST") {

				echo '<h1 class="text-center">Insert Item</h1>';
				echo '<div class="container">';

				$name 	= $_POST['name'];
				$desc 	= $_POST['description'];
				$price 	= $_POST['price'];
				$made 	= $_POST['country'];
				$stat 	= $_POST['status'];
				$member = $_POST['member'];
				$categ 	= $_POST['category'];

				$errorForm = array();

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
				if ($member == 0) {
					$errorForm[] = 'Choose <strong>Member</strong>';
				}
				if ($categ == 0) {
					$errorForm[] = 'Choose <strong>Category</strong>';
				}
				foreach ($errorForm as $error) {

					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				if (empty($errorForm)) {

					// Insert Members To Database

					$stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, Approve)
												VALUES(:zname, :zdesc, :zprice, :zcountry, :zstat, now(), :zcat, :zmember, 1)");
					$stmt->execute(array(

						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zprice' 	=> $price,
						'zcountry' 	=> $made,
						'zstat' 	=> $stat,
						'zcat' 		=> $categ,
						'zmember' 	=> $member
					));

					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
					redirect_to($theMsg, 'back');
					echo '</div>';
				}
			} else {

				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
		} elseif ($do == 'Edit') {

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			$stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? LIMIT 1");
			$stmt->execute(array($itemid));
			$item = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {
?>
				<h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form action="?do=Update" method="POST">

						<input type="hidden" name="itemid" value="<?php echo $itemid ?>">

						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Item Name</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="name" placeholder="Item Name" required="required" value="<?php echo $item['Name'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Description</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="description" placeholder="Description Your Item" required="required" value="<?php echo $item['Description'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Price</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="price" placeholder="Item Price" required="required" value="<?php echo $item['Price'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Country Made</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="country" placeholder="Made in" value="<?php echo $item['Country_Made'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Status</label>
							<div class="col-sm-10 col-md-4">
								<select class="form-control" name="status">
									<option value="1" <?php if ($item['Status'] == 1) {echo 'selected';} ?> >New</option>
									<option value="2" <?php if ($item['Status'] == 2) {echo 'selected';} ?> >Like New</option>
									<option value="3" <?php if ($item['Status'] == 3) {echo 'selected';} ?> >Used</option>
									<option value="4" <?php if ($item['Status'] == 4) {echo 'selected';} ?> >Very Old</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Member</label>
							<div class="col-sm-10 col-md-4">
								<select class="form-control" name="member">
									<?php
										$stmt = $con->prepare("SELECT * FROM users");
										$stmt->execute();
										$users = $stmt->fetchAll();
										foreach ($users as $user) {

											echo '<option value="' . $user['UserID'] . '"';
											if ($item['Member_ID'] == $user['UserID']) {echo 'selected';}
											echo '>' . $user['Username'] . '</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Category</label>
							<div class="col-sm-10 col-md-4">
								<select class="form-control" name="category">
									<?php
										$stmt = $con->prepare("SELECT * FROM category");
										$stmt->execute();
										$cats = $stmt->fetchAll();
										foreach ($cats as $cat) {

											echo '<option value="' . $cat['ID'] . '"';
											if ($item['Cat_ID'] == $cat['ID']) {echo 'selected';}
											echo '>' . $cat['Name'] . '</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="ml-auto col-sm-10">
								<input class="btn btn-primary" type="submit" value="Edit Item">
							</div>
						</div>
					</form>
<?php					
					$stmt = $con->prepare("SELECT comments.*, users.Username AS Member_name FROM comments
									INNER JOIN users ON users.UserID = comments.Member_id WHERE Item_id = ?");
					$stmt->execute(array($itemid));
					$coms = $stmt->fetchAll();

					if (!empty($coms)) {
?>
						<h1 class="text-center">Manage [<?php echo $item['Name'] ?>] Comments</h1>					
						<div class="table-responsive">
							<table class="table text-center table-bordered">
								<thead class="thead-dark">
									<tr>
										<th>Comment</th>
										<th>Member Name</th>
										<th>Adding Date</th>
										<th>Control</th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($coms as $com) {
											echo '<tr>';
												echo '<td>' . $com['Comment'] . '</td>';
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
			  <?php } ?>					
				</div>
<?php
			} else {

				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry Theres IS No ID</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
		} elseif ($do == 'Update') {

			echo '<h1 class="text-center">Update Item</h1>';
			echo '<div class="container">';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				//Get Variable From Form
				$itemid = $_POST['itemid'];
				$name 	= $_POST['name'];
				$desc 	= $_POST['description'];
				$price 	= $_POST['price'];
				$made 	= $_POST['country'];
				$stat 	= $_POST['status'];
				$categ 	= $_POST['category'];
				$member = $_POST['member'];

				$errorForm = array(); //Validate Form

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
				if ($member == 0) {
					$errorForm[] = 'Choose <strong>Member</strong>';
				}
				if ($categ == 0) {
					$errorForm[] = 'Choose <strong>Category</strong>';
				}
				foreach ($errorForm as $error) {

					echo '<div class="alert alert-danger">' . $error . '</div>'; 
				}
				if (empty($errorForm)) {

					$stmt = $con->prepare("UPDATE items SET 
													Name = ?, 
													Description = ?, 
													Price = ?, 
													Country_Made = ?, 
													Status = ?, 
													Cat_ID = ?, 
													Member_ID = ? 
													WHERE Item_ID = ?");
					$stmt->execute(array($name, $desc, $price, $made, $stat, $categ, $member, $itemid));

					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
					redirect_to($theMsg, "back");
				}
			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
			}
			echo '</div>';

		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete Member</h1>';
			echo '<div class="container">';

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			$check =  checkItem('Item_ID' , 'items', $itemid);

			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitem");
				$stmt->bindParam(":zitem", $itemid);
				$stmt->execute();
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
			}
			echo '</div>';

		} elseif ($do == 'Approve') {

			echo '<h1 class="text-center">Approve Item</h1>';
			echo '<div class="container">';

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			$check = checkItem('Item_ID', 'items', $itemid);

			if ($check > 0) {

				$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
				$stmt->execute(array($itemid));
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