<?php
session_start();
if (!isset($_SESSION['authed'])) {
	header("Location: login.php");
}

if (!isset($_GET['name'])) { header('Location: .'); }
include_once('settings.php');
$con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], 'fancy');
mysqli_set_charset($con, "utf8");
$name = $_GET['name'];

$con->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";");
$con->query("CREATE TABLE `{$con->real_escape_string($name)}` (`id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
$con->query("ALTER TABLE `{$con->real_escape_string($name)}` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `{$con->real_escape_string($name)}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
header('Location: index.php?site='.$name);
?>