<?php
class FancyConnector {
	protected $con;
	protected $fancyVars;
	public $preparedStatements = array();

	final public function __construct($site = false) {
		//Get the user settings
		include_once('settings.php');
		$this->fancyVars = $fancyVars;

		//Connect to the database. In the future I might create a system to allow for this to work with custom connections
		$this->con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], $fancyVars['dbname']);
		if ($this->con->connect_errno) { echo "<script>alert('Error connecting to Fancy database');</script>"; }
		mysqli_set_charset($this->con, "utf8");

		//Prepare common statements
		if ($site) {
			$this->preparedStatements['getElement'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `html` FROM `{$this->con->real_escape_string($site)}` WHERE `name` = ?;");
		}
	}

	public function fancy($name) {
		$this->preparedStatements['getElement']->bind_param('s', $name);
		$this->preparedStatements['getElement']->execute();
		$this->preparedStatements['getElement']->bind_result($element);
		while ($this->preparedStatements['getElement']->fetch()) { echo '<div class="fancyElement" fancyname="'.$name.'">'.$element.'</div>'; }
		return;
	}
}