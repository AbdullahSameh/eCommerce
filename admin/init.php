<?php

	include "connect.php";

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
	
	// Include navbar if there is no noNavbar Variable in pages
	if (!isset($noNavbar)) { include $tpl . "navbar.php"; }




