<?php
include_once('DashboardHandler.php');
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}
else {
	if (empty($_POST['title']) || !isset($_POST['id']) || empty($_POST['blog']) || empty($_POST['html'])) { echo "Not enough data has been sent"; exit(); }

	$title = $_POST['title'];
	$html = $_POST['html'];
	$blog = $_POST['blog'];
	$id = $_POST['id'];

	$handler = new DashboardHandler();

	if ($id == 0) {
		//New post
		$handler->newPost($title, $html, $blog);
	}
	else {
		//Updating
		$handler->updatePost($title, $html, $id);
	}

	echo "done";
	exit();
}