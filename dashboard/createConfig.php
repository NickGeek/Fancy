<?php
if (empty($_POST['site']) || empty($_POST['password']) || empty($_POST['dbname']) || empty($_POST['address']) || empty($_POST['dbuser']) || empty($_POST['dbpass'])) {
	echo "You must fill out all the text boxes.";
	exit();
}

include_once('api/VersionNumberHolder.php');
$fancyVarsStr = htmlspecialchars('<?php').' $fancyVars = array("fancy_password" => \''.password_hash($_POST["password"], PASSWORD_BCRYPT).'\',"dbaddr" => \''.addslashes($_POST["address"]).'\',"dbuser" => \''.addslashes($_POST["dbuser"]).'\',"dbpass" => \''.addslashes($_POST["dbpass"]).'\', "dbname" => \''.addslashes($_POST['dbname']).'\', "apiVersion" => \''.VersionNumberHolder::$version.'\'); '.htmlspecialchars('?>');

$name = $_POST['site'];

// Create an settings file
$file = fopen("api/settings.php", "w");
if (!is_resource($file)) {
	echo "Error creating settings.php. Does PHP have filesystem access?";
	echo "<p>If you can't give filesystem access to Fancy; make a file called settings.php in ".realpath(__DIR__)."/api and copy/paste this into it:<br />";
	echo "<code>$fancyVarsStr</code></p><br /><br />";
	echo "<p>After pasting the code into settings.php <a href='javascript:void(0);' onclick='newsite(\"$name\")'>click here</a> to finish the setup.";
	?>
	<script src="js/jquery.js"></script>
	<script>
		function newsite(name) {
			$.get("api/newsite.php", {name: name}).done(function(data) {
				window.location.href="index.php?site="+name;
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
	</script>
	<?php
}
else {
	fwrite($file, htmlspecialchars_decode($fancyVarsStr));
}

session_start();
$_SESSION['authed'] = true;

//Add the site
$con = new mysqli($_POST['address'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);

$con->query("CREATE TABLE IF NOT EXISTS `elements` ( `id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL, `site` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
$con->query("CREATE TABLE IF NOT EXISTS `sites` ( `id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
$con->query("CREATE TABLE IF NOT EXISTS `blogs` ( `id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
$con->query("CREATE TABLE IF NOT EXISTS `blog_posts` ( `id` int(11) NOT NULL, `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL, `blog` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$con->query("ALTER TABLE `elements` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `sites` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `elements` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$con->query("ALTER TABLE `sites` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$con->query("ALTER TABLE `blogs` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `blogs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$con->query("ALTER TABLE `blog_posts` ADD PRIMARY KEY (`id`);");
$con->query("ALTER TABLE `blog_posts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$con->query("ALTER TABLE `blog_posts` ADD `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `title`;");


if (is_resource($file)) {
	include_once('api/DashboardHandler.php');
	$handler = new DashboardHandler();
	$handler->newSite($name);

	header('Location: index.php?site='.$name);
}
