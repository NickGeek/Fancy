<?php
class FancyConnector {
	protected $con;
	protected $fancyVars;
	public $preparedStatements = array();
	public $site;
	public $apiVersion;

	public function __construct($site = false, $blog = false) {
		//Get the user settings
		if (file_exists(realpath(realpath(__DIR__).'/settings.php'))) {
			include_once(realpath(realpath(__DIR__).'/settings.php'));
		}
		else if (file_exists(realpath(realpath(__DIR__).'/../settings.php'))) {
			include_once(realpath(realpath(__DIR__).'/../settings.php'));
		}
		else {
			echo "Fancy has not been setup";
			exit();
		}
		$this->fancyVars = $fancyVars;
		$this->site = $site;
		$this->blog = $blog;
		$this->apiVersion = $fancyVars['apiVersion'];

		//Connect to the database. In the future I might create a system to allow for this to work with custom connections
		$this->con = new mysqli($this->fancyVars['dbaddr'], $this->fancyVars['dbuser'], $this->fancyVars['dbpass'], $this->fancyVars['dbname']);
		if ($this->con->connect_errno) { echo "<script>alert('Error connecting to Fancy database');</script>"; }
		mysqli_set_charset($this->con, "utf8mb4");

		$this->init();
	}

	protected function init() {
		//Prepare common statements
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->prepareStatements();
		}
		else {
			//This is running on the old system
			if ($this->site) $this->preparedStatements['getElement'] = $this->con->prepare("/*".""."*/ SELECT `id`, `html` FROM `{$this->con->real_escape_string($this->site)}` WHERE `name` = ?;");
		}

		if ($this->blog) {
			if ($_GET['fancy_getPosts']) {
				if ($this->fancyVars['apiVersion'] >= 2100) {
					$this->preparedStatements['getPosts']->bind_param('s', $this->blog);
					$this->preparedStatements['getPosts']->execute();
					$this->preparedStatements['getPosts']->bind_result($id, $title, $timestamp, $html);
					$postList = array();
					while ($this->preparedStatements['getPosts']->fetch()) { $postList[] = array('id' => $id, 'title' => $title, 'timestamp' => date('F j, Y', strtotime($timestamp)), 'html' => $html); }
					echo json_encode($postList); exit();
				}
				else {
					echo "Feature not in API"; exit();
				}
			}
			elseif ($_GET['fancy_getPost']) {
				if ($this->fancyVars['apiVersion'] >= 2100) {
					$this->preparedStatements['getPostByID']->bind_param('ss', $this->blog, $_GET['fancy_getPost']);
					$this->preparedStatements['getPostByID']->execute();
					$this->preparedStatements['getPostByID']->bind_result($id, $title, $timestamp, $post);
					while ($this->preparedStatements['getPostByID']->fetch()) { echo json_encode(array('id' => $id, 'title' => $title, 'timestamp' => date('F j, Y', strtotime($timestamp)), 'html' => $post)); exit();  }
				}
				else {
					echo "Feature not in API"; exit();
				}
			}
			elseif($_GET['rss']) {
				if ($this->fancyVars['apiVersion'] >= 2100) {
					$this->preparedStatements['getPostsReverse']->bind_param('s', $this->blog);
					$this->preparedStatements['getPostsReverse']->execute();
					$this->preparedStatements['getPostsReverse']->bind_result($id, $title, $timestamp, $html);

					$address = "http";
					if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') $address = "https";
					$address .= '://'.$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'], '?');

					/** Generate RSS */
					header('Content-Type: application/xml; charset=utf-8');
					$rssStr = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
					$rssStr .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
						$rssStr .= '<channel>';
							$rssStr .= "<atom:link href=\"$address?rss=1\" rel=\"self\" type=\"application/rss+xml\" />";
							$rssStr .= "<title>$this->blog</title>";
							$rssStr .= "<link>$address</link>";
							$rssStr .= "<description>A feed for '$this->blog'. Dynamically generated by Fancy.</description>";
							while ($this->preparedStatements['getPostsReverse']->fetch()) {
								$pubDate =  date(DATE_RSS, strtotime($timestamp));
								$rssStr .= "<item><title>$title</title><link>$address?view=$id</link><guid>$address?view=$id</guid><pubDate>$pubDate</pubDate><description><![CDATA[$html]]></description></item>";
							}
						$rssStr .= '</channel>';
					$rssStr .= '</rss>';
					echo $rssStr;
					exit();
				}
				else {
					echo "Feature not in API"; exit();
				}
			}
		}
	}

	protected function prepareStatements() {
		$this->preparedStatements['getElement'] = $this->con->prepare("SELECT `id`, `html` FROM `elements` WHERE `site` = ? AND `name` = ?;");

		$this->preparedStatements['getPostByID'] = $this->con->prepare("SELECT `id`, `title`, `timestamp`, `html` FROM `blog_posts` WHERE `blog` = ? AND `id` = ?;");
		$this->preparedStatements['getPosts'] = $this->con->prepare("SELECT `id`, `title`, `timestamp`, `html` FROM `blog_posts` WHERE `blog` = ?;");
		$this->preparedStatements['getPostsReverse'] = $this->con->prepare("SELECT `id`, `title`, `timestamp`, `html` FROM `blog_posts` WHERE `blog` = ? ORDER BY `timestamp` DESC;");
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
