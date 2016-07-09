<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
$connector = new DashboardHandler($_GET['site']);
$apiVersion = "1000";
if (!empty($connector->apiVersion)) {
	$apiVersion = $connector->apiVersion;
}
echo $apiVersion;