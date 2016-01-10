<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
else {
	if (empty($_POST['name']) || !isset($_POST['id']) || empty($_POST['site']) || empty($_POST['html'])) { echo "Not enough data has been sent"; exit(); }

	$name = $_POST['name'];
	$html = $_POST['html'];
	$site = $_POST['site'];
	$id = $_POST['id'];

	$handler = new DashboardHandler($site);

	if ($id == 0) {
		//New section
		$handler->newElement($name, $html);
	}
	else {
		//Updating
		$handler->updateElement($name, $html, $id);
	}

	echo "done";
	exit();
}