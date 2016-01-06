<?php
if (!empty($_POST['password'])) {
	if (file_exists(realpath(getcwd().'/settings.php'))) {
		include_once('settings.php');
	}
	else {
		include_once('../settings.php');
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