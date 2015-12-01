<?php require_once('FancyConnector.php'); $fancyConnector = new FancyConnector('demo'); ?>
<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>About - Fancy Demo</title>
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
					<li><a href=".">Home</a></li>
					<li class="selected"><a href="about.php">About</a></li>
					<li><a href="javascript:void(0);">Blog</a></li>
				</ul>
			</div>
			<div class="body">
				
				<h3>The power of Fancy</h3>
				<p>This part of the page is static, however note the three boxes below. They are from the homepage. I can update these featured products from the Fancy Dashboard and it will directly feed into the boxes on the homepage as well as this page. No duplication of code for duplication of stuff!</p>
				<p>Also note the 'Coming soon to Fancy' section. Just the text under that heading is dynamic. This means you don't have to go into the code to edit that section.</p>

				<h3>Coming soon to Fancy</h3>
				<?php $fancyConnector->fancy('Coming Soon') ?>

				<h3>What makes Fancy different from a CMS?</h3>
				<p>Fancy is a drop-in solution for existing static pages. It doesn't require the entire page to be built around it.</p>

				<h3>Why PHP? Are you stupid?</h3>
				<p>I could write Fancy for NodeJS, Python, Rails, etc. The reason I have chosen PHP (even with all of its flaws) is because of the wide support it has. Fancy will even work on shared hosting where user access is very limited.</p>
					
				<ul class="featuredItems">
					<li>
						<div>
							<?php $fancyConnector->fancy('Feature 1'); ?>
							<a href="javascript:void(0);"></a>
						</div>
					</li>
					<li>
						<div>
							<?php $fancyConnector->fancy('Feature 2'); ?>
							<a href="javascript:void(0);"></a>
						</div>
					</li>
					<li>
						<div>
							<?php $fancyConnector->fancy('Feature 3'); ?>
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