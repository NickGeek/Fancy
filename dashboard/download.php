<?php
session_start();
if (!isset($_SESSION['authed'])) {
	header("Location: login.php");
}

function download($file) {
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

if ($_GET['id'] == "settings") {
	download('settings.php');
}
elseif ($_GET['id'] == "connector") {
	download('fancyConnector.php');
}
?>