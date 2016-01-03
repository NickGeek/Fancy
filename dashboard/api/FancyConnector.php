<?php
class FancyConnector {
	protected $con;
	protected $fancyVars;
	public $preparedStatements = array();

	final public function __construct($site = false, $isDashboard = true) {
		//Get the user settings
		include_once('settings.php');
		$this->fancyVars = $fancyVars;

		//Connect to the database. In the future I might create a system to allow for this to work with custom connections
		$this->con = new mysqli($this->fancyVars['dbaddr'], $this->fancyVars['dbuser'], $this->fancyVars['dbpass'], $this->fancyVars['dbname']);
		if ($this->con->connect_errno) { echo "<script>alert('Error connecting to Fancy database');</script>"; }
		mysqli_set_charset($this->con, "utf8");

		//Prepare common statements
		if ($this->fancyVars['apiVersion'] < 2000) {
			//This is running on the old system
			
		}
		else {
			$this->prepareStatements($site, $isDashboard);
		}
	}

	public function fancy($name) {
		$this->preparedStatements['getElement']->bind_param('s', $name);
		$this->preparedStatements['getElement']->execute();
		$this->preparedStatements['getElement']->bind_result($id, $name, $element);
		while ($this->preparedStatements['getElement']->fetch()) { echo '<div class="fancyElement" fancyname="'.$name.'">'.$element.'</div>'; }
		return;
	}

	public function prepareStatements($site, $isDashboard) {
		//Prepare Statements using the new system
		return;
	}

	public function oldPrepareStatements($site, $isDashboard) {
		if ($site) {
			$this->preparedStatements['getElement'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `name`, `html` FROM `{$this->con->real_escape_string($site)}` WHERE `name` = ?;");
		}

		if ($site && $isDashboard) {
			$this->preparedStatements['getElements'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `name` FROM `{$con->real_escape_string($site)}` WHERE 1;");
			$this->preparedStatements['deleteElement'] = $this->con->prepare("DELETE FROM `{$con->real_escape_string($site)}` WHERE `name` = ?:");
			$this->preparedStatements['newElement'] = $this->con->prepare("INSERT INTO `{$con->real_escape_string($site)}` (`name`, `html`) VALUES (?, ?);");
			$this->preparedStatements['updateElement'] = $this->con->prepare("UPDATE `{$con->real_escape_string($site)}` SET `name`=?, `html`=? WHERE `id` = ?;");
		}

		$this->preparedStatements['getSites'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ show tables;");
	}
}