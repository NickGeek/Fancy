<?php
include_once('DashboardHandler.php');
// session_start();
// if (!isset($_SESSION['authed'])) {
// 	echo "Authentication Error";
// 	exit();
// }
if (empty($_GET['id']) || empty($_GET['site'])) { echo "Not enough data has been sent"; exit(); }
$handler = new DashboardHandler($_GET['site']);

echo $handler->getElement($_GET['id']);