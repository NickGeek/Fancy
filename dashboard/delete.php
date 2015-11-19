<?php
session_start();
if (!isset($_SESSION['authed'])) {
	header("Location: login.php");
}

include_once('settings.php');
$con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], 'fancy');
mysqli_set_charset($con, "utf8");
$type = $_GET['type'];

if ($type == 'site' && isset($_GET['name'])) {
	$name = $_GET['name'];
	$sql = "DROP TABLE IF EXISTS `{$con->real_escape_string($name)}`;";
}
elseif ($type == 'element' && isset($_GET['name']) && isset($_GET['site'])) {
	$site = $_GET['site'];
	$name = $_GET['name'];
	$sql = "DELETE FROM `{$con->real_escape_string($site)}` WHERE `name` = '{$con->real_escape_string($name)}';";
}

$con->query($sql);

header('Location: .');
?>