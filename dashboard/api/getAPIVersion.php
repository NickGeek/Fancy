<?php
include_once('FancyConnector.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
$connector = new FancyConnector();
$apiVersion = "1000";
if (!empty($connector->apiVersion)) {
	$apiVersion = $connector->apiVersion;
}
echo $apiVersion;