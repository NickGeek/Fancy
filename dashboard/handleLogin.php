<?php
if (isset($_POST['password'])) {
	include_once('settings.php');
	if (password_verify($_POST['password'], $fancyVars['fancy_password'])) {
		session_start();
		$_SESSION['authed'] = true;
		header('Location: .');
	}
	else {
		echo "Incorrect Password";
	}
}
else {
	echo "You must type in a password";
}
?>