<?php
include_once('FancyConnector.php');
class DashboardHandler extends FancyConnector {
	protected function init() {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->prepareStatements();
		}
		else {
			$this->oldPrepareStatements();
		}
	}

	public function prepareStatements() {
		//Prepare Statements using the new system
		parent::prepareStatements();

		$this->preparedStatements['getElementByID'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `name`, `html` FROM `elements` WHERE `site` = ? AND `id` = ?;");
		$this->preparedStatements['getElements'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `name` FROM `elements` WHERE `site` = ?;");
		$this->preparedStatements['deleteElement'] = $this->con->prepare("DELETE FROM `elements` WHERE `name` = ? AND `site` = ?;");
		$this->preparedStatements['newElement'] = $this->con->prepare("INSERT INTO `elements` (`name`, `html`, `site`) VALUES (?, ?, ?);");
		$this->preparedStatements['updateElement'] = $this->con->prepare("UPDATE `elements` SET `name`=?, `html`=? WHERE `id` = ?;");

		$this->preparedStatements['getSites'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `name` FROM `sites` WHERE 1;");
		$this->preparedStatements['newSite'] = $this->con->prepare("INSERT INTO `sites` (`name`) VALUES (?);");
		$this->preparedStatements['deleteSite'] = $this->con->prepare("DELETE FROM `sites` WHERE `name` = ?;");

		$this->preparedStatements['getBlogs'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `name` FROM `blogs` WHERE 1;");
		$this->preparedStatements['newBlog'] = $this->con->prepare("INSERT INTO `blogs` (`name`) VALUES (?);");
		$this->preparedStatements['deleteBlog'] = $this->con->prepare("DELETE FROM `blogs` WHERE `name` = ?;");
		
		$this->preparedStatements['deletePost'] = $this->con->prepare("DELETE FROM `blog_posts` WHERE `title` = ? AND `blog` = ?;");
		$this->preparedStatements['newPost'] = $this->con->prepare("INSERT INTO `blog_posts` (`title`, `html`, `blog`) VALUES (?, ?, ?);");
		$this->preparedStatements['updatePost'] = $this->con->prepare("UPDATE `blog_posts` SET `title`=?, `html`=? WHERE `id` = ?;");
		return;
	}

	public function oldPrepareStatements() {
		if ($this->site) {
			$this->preparedStatements['getElementByID'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `name`, `html` FROM `{$this->con->real_escape_string($this->site)}` WHERE `id` = ?;");
			$this->preparedStatements['getElements'] = $this->con->prepare("/*".MYSQLND_QC_ENABLE_SWITCH."*/ SELECT `id`, `name` FROM `{$this->con->real_escape_string($this->site)}` WHERE 1;");
			$this->preparedStatements['deleteElement'] = $this->con->prepare("DELETE FROM `{$this->con->real_escape_string($this->site)}` WHERE `name` = ?;");
			$this->preparedStatements['newElement'] = $this->con->prepare("INSERT INTO `{$this->con->real_escape_string($this->site)}` (`name`, `html`) VALUES (?, ?);");
			$this->preparedStatements['updateElement'] = $this->con->prepare("UPDATE `{$this->con->real_escape_string($this->site)}` SET `name`=?, `html`=? WHERE `id` = ?;");
		}
		return;
	}

	/** Elements */
	public function getElement($id) {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['getElementByID']->bind_param('ss', $this->site, $id);
		}
		else {
			$this->preparedStatements['getElementByID']->bind_param('s', $id);
		}
		$this->preparedStatements['getElementByID']->execute();
		$this->preparedStatements['getElementByID']->bind_result($name, $element);
		while ($this->preparedStatements['getElementByID']->fetch()) { return json_encode(array('name' => $name, 'html' => $element)); }
	}

	public function getElements() {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['getElements']->bind_param('s', $this->site);
		}
		$this->preparedStatements['getElements']->execute();
		$this->preparedStatements['getElements']->bind_result($id, $name);
		$elementList = array();
		while ($this->preparedStatements['getElements']->fetch()) { $elementList[] = array('id' => $id, 'name' => $name); }
		return json_encode($elementList);
	}

	public function deleteElement($name) {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['deleteElement']->bind_param('ss', $name, $this->site);
		}
		else {
			$this->preparedStatements['deleteElement']->bind_param('s', $name);
		}
		$this->preparedStatements['deleteElement']->execute();
		return;
	}

	public function newElement($name, $html) {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['newElement']->bind_param('sss', $name, $html, $this->site);
		}
		else {
			$this->preparedStatements['newElement']->bind_param('ss', $name, $html);
		}
		$this->preparedStatements['newElement']->execute();
		return;
	}

	public function updateElement($name, $html, $id) {
		$this->preparedStatements['updateElement']->bind_param('ssi', $name, $html, $id);
		$this->preparedStatements['updateElement']->execute();
		return;
	}

	/** Sites */
	public function getSites() {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['getSites']->execute();
			$this->preparedStatements['getSites']->bind_result($name);
			$sites = array();
			while ($this->preparedStatements['getSites']->fetch()) { $sites[] = $name; }
			return json_encode($sites);
		}
		else {
			$sql = $this->con->query("show tables;");
			$res = array();
			$sites = array();
			foreach ($sql as $row) {
				$res[] = $row;
			}
			foreach ($res as $x) {
				$sites[] = $x['Tables_in_'.$this->fancyVars['dbname']];
			}
			foreach ($sites as $key => $value) {
				$sites[$key] = stripslashes($value);
			}
			return json_encode($sites);
		}
	}

	public function newSite($name) {
		$this->con->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";");
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['newSite']->bind_param('s', $name);
			$this->preparedStatements['newSite']->execute();
		}
		else {
			$this->con->query("CREATE TABLE `{$this->con->real_escape_string($name)}` (`id` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
			$this->con->query("ALTER TABLE `{$this->con->real_escape_string($name)}` ADD PRIMARY KEY (`id`);");
			$this->con->query("ALTER TABLE `{$this->con->real_escape_string($name)}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
		}
		return;
	}

	public function deleteSite($name) {
		if ($this->fancyVars['apiVersion'] >= 2000) {
			$this->preparedStatements['deleteSite']->bind_param('s', $name);
			$this->preparedStatements['deleteSite']->execute();
		}
		else {
			$this->con->query("DROP TABLE IF EXISTS `{$this->con->real_escape_string($name)}`;");
		}
		return;
	}

	/** Blog Posts */
	public function getPost($id, $blog) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['getPostByID']->bind_param('ss', $blog, $id);
			$this->preparedStatements['getPostByID']->execute();
			$this->preparedStatements['getPostByID']->bind_result($id, $title, $timestamp, $post);
			while ($this->preparedStatements['getPostByID']->fetch()) { return json_encode(array('name' => $title, 'html' => $post)); }
		}
		else {
			return "Feature not in API";
		}
	}

	public function getPosts($blog) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['getPosts']->bind_param('s', $blog);
			$this->preparedStatements['getPosts']->execute();
			$this->preparedStatements['getPosts']->bind_result($id, $title, $timestamp, $html);
			$postList = array();
			while ($this->preparedStatements['getPosts']->fetch()) { $postList[] = array('id' => $id, 'timestamp' => date('F j, Y', strtotime($timestamp)), 'title' => $title); }
			return json_encode($postList);
		}
		else {
			return "Feature not in API";
		}
	}

	public function deletePost($title, $blog) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['deletePost']->bind_param('ss', $title, $blog);
			$this->preparedStatements['deletePost']->execute();
		}
		else {
			return "Feature not in API";
		}
	}

	public function newPost($title, $html, $blog) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['newPost']->bind_param('sss', $title, $html, $blog);
			$this->preparedStatements['newPost']->execute();
		}
		else {
			return "Feature not in API";
		}
	}

	public function updatePost($title, $html, $id) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['updatePost']->bind_param('ssi', $title, $html, $id);
			$this->preparedStatements['updatePost']->execute();
			return;
		}
		else {
			return "Feature not in API";
		}
	}

	/** Blogs */
	public function getBlogs() {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['getBlogs']->execute();
			$this->preparedStatements['getBlogs']->bind_result($name);
			$blogs = array();
			while ($this->preparedStatements['getBlogs']->fetch()) { $blogs[] = $name; }
			return json_encode($blogs);
		}
		else {
			return "Feature not in API";
		}
	}

	public function newBlog($name) {
		$this->con->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";");
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['newBlog']->bind_param('s', $name);
			$this->preparedStatements['newBlog']->execute();
		}
		else {
			return "Feature not in API";
		}
		return;
	}

	public function deleteBlog($name) {
		if ($this->fancyVars['apiVersion'] >= 2100) {
			$this->preparedStatements['deleteBlog']->bind_param('s', $name);
			$this->preparedStatements['deleteBlog']->execute();
		}
		else {
			return "Feature not in API";
		}
		return;
	}
}