<?php
$site = 'demo';
require_once('fancyConnector.php');
?>
<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Fancy Demo</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<!--[if IE 7]>
			<link rel="stylesheet" href="css/ie7.css" type="text/css" />
		<![endif]-->
	</head>
	<body>
		<div class="page">
			<div class="header">
				<a href="." id="logo"><img src="images/logo.png" alt=""/></a>
				<ul>
					<li class="selected"><a href=".">Home</a></li>
					<li><a href="about.php">About</a></li>
					<li><a href="javascript:void(0);">Blog</a></li>
				</ul>
			</div>
			<div class="body">
				<div id="featured">
					<h3>This page is very <a href="http://fancyxht.ml" target="_blank"><em>Fancy</em></a></h3>
					<p>This website has dynamic content all throughout it using Fancy. This entire website took next to no time to class it up and make it <em>fancy</em>! Have a look around and see how this website has no CMS junk; just simple HTML, CSS, and a little bit of fanciness. Once you're done here condider saving yourself time and hassle by putting Fancy in your website.</p>
					<input type="button" value="Read more" onClick="parent.location='about.php'"/>
				</div>
				<ul class="featuredItems">
					<li>
						<div>
							<?php fancy('Feature 1'); ?>
							<a href="javascript:void(0);"></a>
						</div>
					</li>
					<li>
						<div>
							<?php fancy('Feature 2'); ?>
							<a href="javascript:void(0);"></a>
						</div>
					</li>
					<li>
						<div>
							<?php fancy('Feature 3'); ?>
							<a href="javascript:void(0);"></a>
						</div>
					</li>
				</ul>
			</div>
			<div class="footer">
				<ul>
					<li><a href=".">Home</a></li>
					<li><a href="about.php">About</a></li>
					<li><a href="javascript:void(0);">Blog</a></li>
				</ul>
				<p class="fancya" style="text-align: center;">
					Copyright info could go here.
					<br /><br />Polygonal graphic by <a href="http://www.freepik.com/">Freepik</a><br /> from <a href="http://www.flaticon.com/">Flaticon</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a>
				</p>
				<div class="footerLeft fancya">
					<p>This website is <a target="_blank" href="http://fancyxht.ml">Fancy</a></p>
				</div>
			</div>
		</div>
	</body>
</html>  