<?php
session_start();
if (!isset($_SESSION['authed'])) {
	header("Location: login.php");
}
include_once('settings.php');
$con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], 'fancy');
mysqli_set_charset($con, "utf8");

//Get sites
$sql = $con->query("show tables;");
$res = array();
$sites = array();
foreach ($sql as $row) {
	$res[] = $row;
}
foreach ($res as $x) {
	$sites[] = $x['Tables_in_fancy'];
}

if (isset($_GET['site'])) {
	$site = $_GET['site'];

	//Get elements
	$sql = $con->query("SELECT `id`, `name` FROM `{$con->real_escape_string($site)}` WHERE 1;");
	$elements = array();
	foreach ($sql as $row) {
		$elements[] = $row;
	}
}
else {
	header('Location: index.php?site='.$sites[0]);
}

if (file_exists(realpath(getcwd().'/setup.php'))) {
	echo "<script>alert('You need to delete setup.php');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

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

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script>
		function del(name) {
			if (confirm('Are you sure you want to delete this?')) {
				window.location.href="delete.php?type=site&name="+name;
			} else {
				return;
			}
		}

		function newsite() {
			var name = prompt("Name of site:", "");
			if (name.length == 0) return;
			window.location.href="newsite.php?name="+name;
		}
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
							<a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Logout</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav side-nav">
					<li>
						<a href="javascript:void(0);" onclick="newsite();"><i class="fa fa-fw fa-plus"></i> Add Site</a>
					</li>
					<?php
					foreach ($sites as $x) {
						$code = '<li>';
						if ($site == $x) {
							$code = '<li class="active">';
						}
						$code .= '<a href="index.php?site='.$x.'"><i class="fa fa-fw fa-file"></i> '.$x.'</a></li>';
						echo $code;
					}
					?>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</nav>

		<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header"><?php echo $site; ?> <a href="javascript:void(0);" class="btn btn-danger" onclick="del(<?php echo "'".$site."'"; ?>);">Delete Site</a></h1>
						
						<!-- Page content -->
						<div id="elementList" class="list-group">
							<?php
							echo '<a href="edit.php?site='.$site.'&id=0"class="list-group-item"><i class="fa fa-fw fa-plus"></i> Add a new Fancy element</a>';

							foreach ($elements as $element) {
								echo '<a href="edit.php?site='.$site.'&id='.$element['id'].'"class="list-group-item">'.$element['name'].'</a>';
							}
							?>
						</div>
						
						<h3>How to enable Fancy on your webpages:</h3>
						<ol>
							<li>Download these two files (<a href="download.php?id=settings" target="_blank">settings.php</a> and <a href="download.php?id=connector" target="_blank">fancyConnector.php</a>)</li>
							<li>Put the files in the folder of the site.</li>
							<li>
								Paste the following code at the very top of any page you wish to add Fancy elements to:<br />
								<code>
									<?php
										echo htmlspecialchars('<?php');
										echo ' $site = \''.$site.'\';';
										echo ' require_once(\'fancyConnector.php\');';
										echo ' ?>';
									?>
								</code>
							</li>
						</ol>
					</div>
				</div>
				<!-- /.row -->

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- jQuery -->
	<script src="js/jquery.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

</body>

</html>