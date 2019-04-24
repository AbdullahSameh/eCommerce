<?php 
	/*
	** Get All Function v2.0
	** Function To Get All Records From Database Tables
	*/
	function getAll($select, $table, $where = NULL, $and = NULL, $order, $ordering = "DESC") {
		global $con;

		$getAll = $con->prepare("SELECT $select FROM $table $where $and ORDER BY $order $ordering");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
	}
	/*
	** Get Category Function (To Category Items In Database)
	*/
	function getCat() {
		global $con;

		$getCat = $con->prepare("SELECT * FROM category ORDER BY ID");
		$getCat->execute();
		$cats = $getCat->fetchAll();
		return $cats;
	}
	/*
	** Get AD Items Function (To Category Items In Database)
	*/
	function getItem($where, $value, $accept = NULL) {
		global $con;

		$sql = $accept == NULL ? 'AND Approve = 1' : '';
		
		$getItem = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");
		$getItem->execute(array($value));
		$items = $getItem->fetchAll();
		return $items;
	}
	// Page Title Function 
	function getTitle() {

		global $pageTitle;

		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Default';
		}
	}
	/*
	** Check Items Function (To Check Items In Database)
	** $select = to select items from the tables
	** $from = to select the table from database
	** $value = it is a value of $select
	*/
	function checkItem($select, $from, $value) {
		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		$statement->execute(array($value));
		$count = $statement->rowCount();
		return $count;
	}
	////////////////////////////////////////////////////////////////
	/* 
	** Redirect To Home Page Function v2.0
	** $theMsg = iIt is the error message
	** $url = The link where you redirect to
	** $seconds = Time to redirect
	*/
	function redirect_to($theMsg, $url = null, $seconds = 3) {

		if ($url === null) {
			$url = 'index.php';
			$link = 'Homepage';
		} else {
			$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $url = $_SERVER['HTTP_REFERER'] : $url = 'index.php';
			$link = 'Previous';
		}
		echo $theMsg;
		echo "<div class='alert alert-info'>You Will Redirect To $link After $seconds Seconds</div>";

		header("refresh:$seconds;url=$url");
		exit();
	}
	/*
	** Count Items Function (To Count Items In Database)
	** $item = to select items from the tables to count
	** $table = to select the table from database
	*/
	function countItem($item, $table) {
		global $con;

		$stmt = $con->prepare("SELECT COUNT($item) FROM $table");
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	/*
	** Get Items Function (To Get Items In Database)
	** $select = to select items from the tables
	** $table = to select the table from database
	** $limit = The items limit to get
	*/
	function getLatest($select, $table, $order, $limit = 5) {
		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;
	}