<?php
	
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);
	include "admin/connect.php";

	$sessionUser = '';
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	// Routes

	$tpl 	= "includes/tamplates/";
	$lang 	= "includes/languages/";
	$func 	= "includes/functions/";
	$css 	= "layout/css/";
	$js 	= "layout/js/";
	

	// Include The Important Files

	include $func . "function.php";
	include $lang . "english.php";
	include $tpl . "header.php";




