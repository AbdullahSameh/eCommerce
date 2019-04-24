<?php 
	
	/*
	**************************************
  	*********** Category Page ************
	** For Add | Edit | Delete Category **
	**************************************
	*/
	ob_start();

	session_start();

	$pageTitle = 'Category';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$sort = 'ASC';
			$sortArray = array('ASC' ,'DESC');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)) {

				$sort = $_GET['sort'];
			}

			$stmt = $con->prepare("SELECT * FROM category WHERE Parent = 0 ORDER BY Ordering $sort");
			$stmt->execute();
			$categs = $stmt->fetchAll();

			if (!empty($categs)) {
?>
				<h1 class="text-center">Manage Categories</h1>
				<div class="container categs">
					<div class="card">
						<div class="card-header">
							Categories
							<div class="order float-right">Orber By: 
								<a href="?sort=ASC">Asc</a> |
								<a href="?sort=DESC">Desc</a>
							</div>
						</div>
						<div class="card-body">
							<?php
								foreach ($categs as $categ) {
									// Get Parents Categories
									echo '<div class="cat">';
										echo '<div class="hidden-butoon">';
											echo '<a href="category.php?do=Edit&catid=' . $categ['ID'] . '" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>';
											echo '<a href="category.php?do=Delete&catid=' . $categ['ID'] . '" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i> Delete</a>';
										echo '</div>';
										echo '<h3>' . $categ['Name'] . '</h3>';
										echo '<p>'; if ($categ['Description'] == '') {echo 'There is no description for this category';} else {echo $categ['Description'];} echo'</p>';
										if($categ['Visibility'] == 1) {echo '<span class="badge badge-danger">Hidden</span>';}
										if($categ['Allow_Comment'] == 1) {echo '<span class="badge badge-warning">Comment is disable</span>';}
										if($categ['Allow_Ads'] == 1) {echo '<span class="badge badge-dark">Ads is disable</span>';}
									echo '</div>';
									// Get Children Categories
									$childCats =  getAll("*", "category", "WHERE Parent = {$categ['ID']}", "", "ID", "ASC");
									if (!empty($childCats)) {
										echo '<h5 class="child-head">Child Category</h5>';
										echo '<ul class="list-unstyled child-cat">';
										foreach ($childCats as $child) {
											echo '<li><a href="category.php?do=Edit&catid=' . $child['ID'] . '">' . $child['Name'] . '</a></li>';
										}
										echo '</ul>';
									}
									echo '<hr>';
								}
							?>
						</div>
					</div>
					<a class="btn btn-primary add" href="category.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
				</div>
<?php
			} else {

				echo '<div class="container">';
					echo '<div class="page-message text-center">';
						echo '<i class="fa fa-exclamation-triangle"></i>';
						echo '<p>There Is No Category To Show</p>';
					echo'</div>';
					echo '<a class="btn btn-primary add" href="category.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>';
				echo '</div>';
			}
		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add Category</h1>
			<div class="container">
				<form action="?do=Insert" method="POST">

					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" placeholder="Category Name" autocomplete="off" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Description</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="description" placeholder="Description Your Category">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Order</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="order" placeholder="Insert Your Order">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Parent</label>
						<div class="col-sm-10 col-md-4">
							<select class="form-control" name="parent">
								<option value="0">...</option>
								<?php
									$parents = getAll("*", "category","WHERE Parent = 0", "", "ID", "ASC");

									foreach ($parents as $parent) {

										echo '<option value="' . $parent['ID'] . '">' . $parent['Name'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Visible</label>
						<div class="col-sm-10 col-md-4">
							<div>
								<input id="vis-yes" type="radio" name="visible" value="0" checked/>
								<label for="vis-yes">Yes</label>
							</div>
							<div>
								<input id="vis-no" type="radio" name="visible" value="1"/>
								<label for="vis-no">No</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">	Allow Comments</label>
						<div class="col-sm-10 col-md-4">
							<div>
								<input id="com-yes" type="radio" name="comment" value="0" checked/>
								<label for="com-yes">Yes</label>
							</div>
							<div>
								<input id="com-no" type="radio" name="comment" value="1"/>
								<label for="com-no">No</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Allow Ads</label>
						<div class="col-sm-10 col-md-4">
							<div>
								<input id="ad-yes" type="radio" name="ads" value="0" checked/>
								<label for="ad-yes">Yes</label>
							</div>
							<div>
								<input id="ad-no" type="radio" name="ads" value="1"/>
								<label for="ad-no">No</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="ml-auto col-sm-10">
							<input class="btn btn-primary" type="submit" value="Add Category">
						</div>
					</div>
				</form>
			</div>
<?php	} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo '<h1 class="text-center">Insert Category</h1>';
				echo '<div class="container">';

				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$parent 	= $_POST['parent'];
				$order 		= $_POST['order'];
				$visible 	= $_POST['visible'];
				$comment 	= $_POST['comment'];
				$ads 		= $_POST['ads'];

				// Check if the username in database 

				$check = checkItem("Name", "category", $name);

				if ($check == 1) {

					$theMsg = '<div class="alert alert-danger">Sorry This Category is Exist</div>';
					redirect_to($theMsg, 'back');

				} else {
					// Insert Memebers To Database
					$stmt = $con->prepare("INSERT INTO category(Name, Description, Parent, Ordering, Visibility, Allow_Comment, Allow_Ads) 
											VALUES(:zname, :zdesc, :zparent, :zorder, :zvisib, :zcom, :zads)");

					$stmt->execute(array(

						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zparent' 	=> $parent,
						'zorder' 	=> $order,
						'zvisib' 	=> $visible,
						'zcom' 		=> $comment,
						'zads' 		=> $ads
					));
					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Inserted</div>';
					redirect_to($theMsg, 'back');
				} echo '</div>';
			} else  {

				echo '<div class="container">';
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
				echo '</div>';
			}
		} elseif ($do == 'Edit') {

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			$stmt = $con->prepare("SELECT * FROM category WHERE ID = ?");
			$stmt->execute(array($catid));
			$categ = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) {
?>
				<h1 class="text-center">Edit Category</h1>
				<div class="container">
					<form action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Name</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="name" placeholder="Category Name" required="required" value="<?php echo $categ['Name']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Description</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="description" placeholder="Description Your Category" value="<?php echo $categ['Description']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Order</label>
							<div class="col-sm-10 col-md-4">
								<input class="form-control" type="text" name="order" placeholder="Insert Your Order" value="<?php echo $categ['Ordering']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Visible</label>
							<div class="col-sm-10 col-md-4">
								<div>
									<input id="vis-yes" type="radio" name="visible" value="0" <?php if ($categ['Visibility'] == 0) {echo 'checked';}?>/>
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visible" value="1" <?php if ($categ['Visibility'] == 1) {echo 'checked';}?>/>
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">	Allow Comments</label>
							<div class="col-sm-10 col-md-4">
								<div>
									<input id="com-yes" type="radio" name="comment" value="0" <?php if ($categ['Allow_Comment'] == 0) {echo 'checked';}?>/>
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="comment" value="1" <?php if ($categ['Allow_Comment'] == 1) {echo 'checked';}?>/>
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Allow Ads</label>
							<div class="col-sm-10 col-md-4">
								<div>
									<input id="ad-yes" type="radio" name="ads" value="0" <?php if ($categ['Allow_Ads'] == 0) {echo 'checked';}?>/>
									<label for="ad-yes">Yes</label>
								</div>
								<div>
									<input id="ad-no" type="radio" name="ads" value="1" <?php if ($categ['Allow_Ads'] == 1) {echo 'checked';}?>/>
									<label for="ad-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="ml-auto col-sm-10">
								<input class="btn btn-primary" type="submit" value="Edit Category">
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
			
			echo '<h1 class="text-center">Update Member</h1>';
			echo '<div class="container">';

			if ($_SERVER["REQUEST_METHOD"] == 'POST') {
				//Get Variable From Form
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['order'];

				$visible 	= $_POST['visible'];
				$comment 	= $_POST['comment'];
				$ads 		= $_POST['ads'];

				$stmt = $con->prepare("UPDATE category SET 
													Name = ?, 
													Description =?, 
													Ordering =?, 
													Visibility =?, 
													Allow_Comment = ?,
													Allow_Ads = ? 
													WHERE ID = ?");
				$stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, "back");

			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Can See This Field</div>';
				redirect_to($theMsg);
			}
			echo '</div>';

		} elseif ($do == 'Delete') {

			echo '<h1 class="text-center">Delete category</h1>';
			echo '<div class="container">';

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			$check = checkItem('ID', 'category', $catid);

			if ($check > 0) {

				$stmt = $con->prepare("DELETE FROM category WHERE ID = :zid");
				$stmt->bindParam(":zid", $catid);
				$stmt->execute();
				$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Record Update</div>';
				redirect_to($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry Theres Is No ID</div>';
				redirect_to($theMsg);
			}
			echo '</div>';
		}

		include $tpl . 'footer.php';

	} else {

		header('location: index.php');
		exit();
	}
	ob_end_flush();