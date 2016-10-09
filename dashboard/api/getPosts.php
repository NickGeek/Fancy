<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
if (empty($_GET['blog'])) { echo "Not enough data has been sent"; exit(); }
$handler = new DashboardHandler();

echo $handler->getPosts($_GET['blog']);