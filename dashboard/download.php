<?php
include_once('api/DashboardHandler.php');
session_start();
function download($file) {
	$handler = new DashboardHandler();
	if ($file === "settings.php" && $handler->apiVersion >= 2000) {
		$file = realpath(realpath(__DIR__).'/api/settings.php');
	}
	else if ($file === "settings.php") {
		$file = realpath(realpath(__DIR__).'/settings.php');
	}
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	readfile($file);
	exit;
}

if (!isset($_SESSION['authed'])) {
	header("Location: login.html");
	exit();
}
else {
	if ($_GET['id'] == "settings") {
		download('settings.php');
	}
	elseif ($_GET['id'] == "connector") {
		download(realpath(realpath(__DIR__).'/api/FancyConnector.php'));
	}
	elseif ($_GET['id'] == "blog") {
		download(realpath(realpath(__DIR__).'/fancy_blog.min.js'));
	}
}

?>