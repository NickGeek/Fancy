<?php
class FancyConnector {
	protected $con;
	protected $fancyVars;
	public $preparedStatements = array();
	public $site;

	public function __construct($site = false) {
		//Get the user settings
		include_once('settings.php');
		$this->fancyVars = $fancyVars;
		$this->site = $site;

		//Connect to the database. In the future I might create a system to allow for this to work with custom connections
		$this->con = new mysqli($this->fancyVars['dbaddr'], $this->fancyVars['dbuser'], $this->fancyVars['dbpass'], $this->fancyVars['dbname']);
		if ($this->con->connect_errno) { echo "<script>alert('Error connecting to Fancy database');</script>"; }
		mysqli_set_charset($this->con, "utf8mb4");

		//Prepare common statements
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['getElement'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `html` FROM `elements` WHERE `site` = ? AND `name` = ?;");
		}
		else {
			//This is running on the old system
			if ($this->site) $this->preparedStatements['getElement'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `html` FROM `{$this->con->real_escape_string($this->site)}` WHERE `name` = ?;");
		}

		$this->init();
	}

	protected function init() {
		return;
	}

	public function fancy($name) {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['getElement']->bind_param('ss', $this->site, $name);
		}
		else {
			$this->preparedStatements['getElement']->bind_param('s', $name);
		}
		$this->preparedStatements['getElement']->execute();
		$this->preparedStatements['getElement']->bind_result($id, $element);
		while ($this->preparedStatements['getElement']->fetch()) { echo '<div class="fancyElement" fancyname="'.$name.'">'.$element.'</div>'; }
		return;
	}
}