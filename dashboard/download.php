<?php
include_once('api/DashboardHandler.php');
session_start();
$handler = new DashboardHandler();
function download($file) {
	if ($handler->fancyVars['apiVersion'] >= 2000) {
		$file = "api/".$file;
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
		download('FancyConnector.php');
	}
}

?>