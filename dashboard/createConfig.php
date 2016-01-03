<?php
if (empty($_POST['site']) || empty($_POST['password']) || empty($_POST['dbname']) || empty($_POST['address']) || empty($_POST['dbuser']) || empty($_POST['dbpass'])) {
	echo "You must fill out all the text boxes.";
	exit();
}

$fancyVarsStr = htmlspecialchars('<?php').' $fancyVars = array("fancy_password" => \''.password_hash($_POST["password"], PASSWORD_BCRYPT).'\',"dbaddr" => \''.addslashes($_POST["address"]).'\',"dbuser" => \''.addslashes($_POST["dbuser"]).'\',"dbpass" => \''.addslashes($_POST["dbpass"]).'\', "dbname" => \''.addslashes($_POST['dbname']).'\', "apiVersion" => 2); '.htmlspecialchars('?>');

// Create an settings file
$file = fopen("api/settings.php", "w");
if (!is_resource($file)) {
	echo "Error creating settings.php. Does PHP have filesystem access?";
	exit();
}

fwrite($file, htmlspecialchars_decode($fancyVarsStr));
fclose($file);

session_start();
$_SESSION['authed'] = true;

//Add the site
$con = new mysqli($_POST['address'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
$name = $_POST['site'];

$con->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";");
$con->query("CREATE TABLE `{$con->real_escape_string($name)}` (`id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
$con->query("ALTER TABLE `{$con->real_escape_string($name)}` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `{$con->real_escape_string($name)}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

header('Location: index.php?site='.$name);
?>