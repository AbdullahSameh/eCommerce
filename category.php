<?php
	ob_start();
	session_start();
	$pageTitle = 'Category';
	include "init.php"; 
?>

	<div class="container">
		<h1 class="text-center">Category Items</h1>
		<div class="row">
			<?php
				$items = getAll("*", "items", "WHERE Cat_ID = {$_GET['pageid']}", "AND Approve = 1", "Item_ID");
				foreach ($items as $item) { ?>
					<div class="col-sm-6 col-lg-3">
						<div class="card">
							<span class="price-box">$<?php echo $item['Price'] ?></span>
							<img class="card-img-top img-fluid" src="user.jpg" alt="Card-image">
							<div class="card-body">
								<h3><a class="item-link" href="item.php?itemid=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h3>
								<p><?php echo $item['Description'] ?></p>
								<div class="item-date"><?php echo $item['Add_Date'] ?></div>
							</div>
						</div>
					</div>
			<?php }
			?>
		</div>
	</div>

<?php 
	include $tpl . "footer.php";
	ob_end_flush();
?>