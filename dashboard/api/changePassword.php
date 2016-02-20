<?php
include_once('FancyConnector.php');

class PasswordChanger extends FancyConnector {
	public function changePassword() {
		if (!password_verify($_POST['password'], $this->fancyVars['fancy_password'])) {
			echo "Incorrect Password";
			exit();
		}
		else if (empty($_POST['newPassword'])) {
			echo "Not enough data has been sent";
		}
		else {
			//If the user doesn't have an API version set it to 1000
			if (empty($this->apiVersion)) {
				$this->apiVersion = 1000;
			}

			//Generate the new settings.php
			$fancyVarsStr = htmlspecialchars('<?php').' $fancyVars = array("fancy_password" => \''.password_hash($_POST["newPassword"], PASSWORD_BCRYPT).'\',"dbaddr" => \''.$this->fancyVars["dbaddr"].'\',"dbuser" => \''.$this->fancyVars["dbuser"].'\',"dbpass" => \''.$this->fancyVars["dbpass"].'\', "dbname" => \''.$this->fancyVars['dbname'].'\', "apiVersion" => \''.$this->apiVersion.'\'); '.htmlspecialchars('?>');

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
				echo "Password Changed";
			}
		}
	}
}

$pwc = new PasswordChanger();
$pwc->changePassword();