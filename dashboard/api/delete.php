<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
else {
	$type = $_GET['type'];

	if ($type == 'site' && !empty($_GET['name'])) {
		$handler = new DashboardHandler();
		$handler->deleteSite($_GET['name']);
	}
	elseif ($type == 'blog' && !empty($_GET['name'])) {
		$handler = new DashboardHandler();
		$handler->deleteBlog($_GET['name']);
	}
	elseif ($type == 'element' && !empty($_GET['name']) && !empty($_GET['site'])) {
		$handler = new DashboardHandler($_GET['site']);
		$handler->deleteElement($_GET['name']);
	}
	elseif ($type == 'post' && !empty($_GET['name']) && !empty($_GET['blog'])) {
		$handler = new DashboardHandler();
		$handler->deletePost($_GET['name'], $_GET['blog']);
	}
	else {
		echo "Not enough data has been sent";
	}

	echo "done";
	exit();
}