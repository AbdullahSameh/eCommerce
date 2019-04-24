<?php 
	ob_start();
	session_start();
	$pageTitle = 'Home Page';
	
	include "init.php";

	$items = getAll('*', 'items', 'WHERE Approve = 1', '', 'Item_ID');
?>
	<div class="container">
		<div class="row">
			<?php
				foreach ($items as $item) { ?>
					<div class="col-sm-6 col-lg-3">
						<div class="card ad">
							<span class="price-box">$<?php echo $item['Price'] ?></span>
							<img class="card-img-top img-fluid" src="user.jpg" alt="Card-image">
							<div class="card-body">
								<h4><a class="item-link" href="item.php?itemid=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h4>
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