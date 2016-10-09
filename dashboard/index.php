<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	if (file_exists(realpath(getcwd().'/createConfig.php')) && !(empty($_GET['site']) && empty($_GET['blog']))) {
		echo "<script>alert('You need to delete createConfig.php');</script>";
	}
	?>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Fancy Dashboard</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="css/sb-admin.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- My CSS -->
	<link href="css/style.css" rel="stylesheet">

	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/get.js"></script>
	<script src="js/misc.js"></script>
	<script src="js/sharedDashboard.js"></script>
	<script src="js/index.js"></script>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script>
		
	</script>
</head>

<body>

	<div id="wrapper">

		<!-- Navigation -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href=".">Fancy Dashboard</a>
			</div>
			<!-- Top Menu Items -->
			<ul class="nav navbar-right top-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Logged In <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="javascript:void(0);" onclick="changePassword()"><i class="fa fa-fw fa-key"></i> Change Password</a>
						</li>
						<li>
							<a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Logout</a>
						</li>
						<li id="menu-dropdown-divider" class="divider"></li>
						<li><span id="fancyVersion" class="dropdown-hint">Loading...</span></li>
						<li><span id="apiVersion" class="dropdown-hint">Loading...</span></li>
					</ul>
				</li>
			</ul>
			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul id="siteSidebar" class="nav navbar-nav side-nav">
					<li>
						<a href="javascript:void(0);" onclick="newsite();"><i class="fa fa-fw fa-plus"></i> Add Site</a>
					</li>
					
					<li id="addBlogButton">
						<a href="javascript:void(0);" onclick="newblog();"><i class="fa fa-fw fa-plus"></i> Add Blog</a>
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</nav>

		<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<h1 id="pageTitle" class="page-header"></h1>
						
						<!-- Page content -->
						<div id="elementList" class="list-group index-list">
							<a id="newElement" class="list-group-item"><i class="fa fa-fw fa-plus"></i> Add a new Fancy element</a>
						</div>

						<div id="postList" class="list-group index-list" style="display: none;">
							<a id="newPost" class="list-group-item"><i class="fa fa-fw fa-pencil"></i> Write a new post</a>
						</div>
						
						<div id="websiteInstructions" style="display: none;">
							<h3>How to enable Fancy on your webpages:</h3>
							<ol>
								<li>Download these two files (<a href="download.php?id=settings" target="_blank">settings.php</a> and <a href="download.php?id=connector" target="_blank">FancyConnector.php</a>)</li>
								<li>Put the files in the folder of the site.</li>
								<li>
									Paste the following code at the very top of any page you wish to add Fancy elements to:<br />
									<code>
										<?php
											echo htmlspecialchars('<?php');
											echo ' require_once(\'FancyConnector.php\');';
											echo ' $f = new FancyConnector(\'<script>document.write(getTitle());</script>\');';
											echo ' ?>';
										?>
									</code>
								</li>
							</ol>
						</div>

						<div id="blogInstructions" style="display: none;">
							<h3>How to enable Fancy on your blog:</h3>
							<ol>
								<li>Download these three files (<a href="download.php?id=settings" target="_blank">settings.php</a>, <a href="download.php?id=connector" target="_blank">FancyConnector.php</a>, and <a href="download.php?id=blog" target="_blank">fancy_blog.min.js</a>)</li>
								<li>Put the files in the folder of your blog.</li>
								<li>
									Paste the following code at the very top of your blog page:<br />
									<code>
										<?php
											echo htmlspecialchars('<?php');
											echo ' require_once(\'FancyConnector.php\');';
											echo ' $f = new FancyConnector(false, \'<script>document.write(getTitle());</script>\');';
											echo ' ?>';
										?>
									</code>
									<ul>
										<li><em>Note: If you want to use Fancy elements from a site on your page, put the site's name where it says "false".</em></li>
									</ul>
								</li>
								<li>
									Paste the following code right above your last <code>&lt;/body&gt;</code> tag:<br />
									<code>&lt;script src="fancy_blog.min.js"&gt;&lt;/script&gt;</code>
								</li>
								<li>
									Create a template for what a post looks like and put it where you want the posts to load. An example is the following:<br />
									<code>
										&lt;div ftemplate="container"&gt;<br />
										&nbsp;&nbsp;&lt;article ftemplate="post"&gt;<br />
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;h1 ftemplate="title"&gt;&lt;/h1&gt;<br />
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;em ftemplate="timestamp"&gt;&lt;/em&gt;<br />
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;p ftemplate="content"&gt;&lt;/p&gt;<br />
										&nbsp;&nbsp;&lt;/article&gt;<br />
										&lt;/div&gt;<br />
										&lt;div ftemplate="comments"&gt;&lt;/div&gt;
									</code>
									<ul>
										<li><em>Note: All of the fields are optional (except for the container and the post). You don't need to include the timestamp, comments, etc.</em></li>
										<li><em>Note: The comments will only show on a full post page, not on the list</em></li>
										<li><em>Note: If you want an example, look at the code for the 'Demo Blog' <a target="_blank" href="https://github.com/NickGeek/Fancy/blob/master/demo/blog.php">here</a>.</em></li>
									</ul>
								</li>
							</ol>
						</div>

						<div id="modal" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title"></h4>
									</div>
									<div class="modal-body">
										<p id="modal-text"></p>
									</div>
									<div class="modal-footer">
										<button class="btn btn-primary" data-dismiss="modal">Dismiss</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->
</body>

</html>