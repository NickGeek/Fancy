<?php
include_once('settings.php');
$con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], 'fancy');
mysqli_set_charset($con, "utf8");
// var_dump($con);

function fancy($name) {
	global $con;
	global $site;
	
	$section = $con->query("SELECT `html` FROM `{$con->real_escape_string($site)}` WHERE `name` = '{$con->real_escape_string($name)}';");
	$row = $section->fetch_array(MYSQLI_ASSOC);
	echo '<!-- FANCY CODE BLOCK START -->'.$row['html'].'<!-- FANCY CODE BLOCK END -->';
	return;
}
?>