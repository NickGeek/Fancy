<?php
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}

//Get version numbers
include_once('VersionNumberHolder.php');
ob_start();
include_once('getAPIVersion.php');
$apiVersion = ob_get_clean();

//Print them
$versions = array('fancy' => VersionNumberHolder::$version, 'api' => $apiVersion);
echo json_encode($versions);
