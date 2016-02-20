<?php
if (!empty($_POST['password'])) {
	if (file_exists(realpath(realpath(__DIR__).'/settings.php'))) {
		include_once(realpath(realpath(__DIR__).'/settings.php'));
	}
	else if (file_exists(realpath(realpath(__DIR__).'/../settings.php'))) {
		include_once(realpath(realpath(__DIR__).'/../settings.php'));
	}
	else {
		echo "Fancy has not been setup";
		exit();
	}
	if (password_verify($_POST['password'], $fancyVars['fancy_password'])) {
		session_start();
		$_SESSION['authed'] = true;
		echo "done";
		exit();
	}
	else {
		echo "Incorrect Password";
	}
}
else {
	echo "You must type in a password";
}