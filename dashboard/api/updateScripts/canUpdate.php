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

if ($currVersion < VersionNumberHolder::$version && file_exists(realpath(realpath(__DIR__).'/'+$currVersion+'to'+VersionNumberHolder::$version+'.php'))) {
	echo "1";
}
echo {
	echo "0"
}
