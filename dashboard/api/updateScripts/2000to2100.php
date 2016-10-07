<?php
include_once(realpath(realpath(__DIR__).'/../FancyConnector.php'));
session_start();
if (!isset($_SESSION['authed'])) {
	echo "Authentication Error";
	exit();
}

class UpdateScript extends FancyConnector {
	public function init() {
		if (empty($this->apiVersion)) {
			$this->apiVersion = "1000";
		}

		if ($this->apiVersion != "2000") {
			echo "This update script is only for API v2000";
			exit();
		}
		$this->apiVersion = "2100";
	}

	public function UpdateSettings() {
		//Generate the new settings.php
		$fancyVarsStr = htmlspecialchars('<?php').' $fancyVars = array("fancy_password" => \''.$this->fancyVars["fancy_password"].'\',"dbaddr" => \''.$this->fancyVars["dbaddr"].'\',"dbuser" => \''.$this->fancyVars["dbuser"].'\',"dbpass" => \''.$this->fancyVars["dbpass"].'\', "dbname" => \''.$this->fancyVars['dbname'].'\', "apiVersion" => \''.$this->apiVersion.'\'); '.htmlspecialchars('?>');

		//Save the new settings.php
		if (file_exists(realpath(realpath(__DIR__).'/settings.php'))) {
			$settingsLocation = realpath(realpath(__DIR__).'/settings.php');
		}
		else if (file_exists(realpath(realpath(__DIR__).'/../settings.php'))) {
			$settingsLocation = realpath(realpath(__DIR__).'/../settings.php');
		}
		else {
			echo "Fancy has not been setup";
			exit();
		}

		$file = fopen($settingsLocation, "w");
		if (!is_resource($file)) {
			echo "<p>Error editing settings.php. Does PHP have filesystem access?</p>";
			echo "<p>If you can't give filesystem access to Fancy; copy and paste this into your settings.php file (located at $settingsLocation):</p>";
			echo "<code>$fancyVarsStr</code>";
		}
		else {
			fwrite($file, htmlspecialchars_decode($fancyVarsStr));
			fclose($file);
			echo "<p>Settings.php has been updated</p>";
		}
	}

	public function UpdateDatabase() {
		$this->con->query("CREATE TABLE IF NOT EXISTS `blogs` ( `id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
		$this->con->query("ALTER TABLE `blogs` ADD PRIMARY KEY (`id`);");
		$this->con->query("ALTER TABLE `blogs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		$this->con->query("CREATE TABLE IF NOT EXISTS `blog_posts` ( `id` int(11) NOT NULL, `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL, `site` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

		echo "<p>The database has been updated</p>";
	}
}

$us = new UpdateScript();
$us->UpdateDatabase();
$us->UpdateSettings();
