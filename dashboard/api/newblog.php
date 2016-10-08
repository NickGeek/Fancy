<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
else {
	if (empty($_GET['name'])) { echo "Not enough data has been sent"; exit(); }
	$name = $_GET['name'];
	$handler = new DashboardHandler();
	
	$handler->newBlog($name);
	echo "done";
}