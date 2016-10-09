<?php
include_once(realpath(realpath(__DIR__).'/../VersionNumberHolder.php'));
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}

//Get the current API version
ob_start();
include_once(realpath(realpath(__DIR__).'/../getAPIVersion.php'));
$currVersion = ob_get_clean();

$updateScriptName = $currVersion.'to'.VersionNumberHolder::$version.'.php';

if ($currVersion < VersionNumberHolder::$version && file_exists(realpath(realpath(__DIR__).'/'.$updateScriptName))) {
	echo "api/UpdateScripts/".$updateScriptName;
}
else {
	echo "0";
}
