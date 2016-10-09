<?php require_once('FancyConnector.php'); $fancyConnector = new FancyConnector('demo', 'Demo Blog'); ?>
<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Blog - Fancy Demo</title>
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
					<li><a href="about.php">About</a></li>
					<li class="selected"><a href="blog.php">Blog</a></li>
				</ul>
			</div>
			<div ftemplate="container" class="body">
				<article ftemplate="post">
					<h1 ftemplate="title"></h1>
					<em ftemplate="timestamp"></em>
					<p ftemplate="content"></p>
				</article>
			</div>
			<div ftemplate="comments" id="disqus_thread" class="body"></div>
			<script>				
			(function() { // DON'T EDIT BELOW THIS LINE
			    var d = document, s = d.createElement('script');
			    s.src = '//fancy-demo.disqus.com/embed.js';
			    s.setAttribute('data-timestamp', +new Date());
			    (d.head || d.body).appendChild(s);
			})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
			<div class="footer">
				<ul>
					<li><a href=".">Home</a></li>
					<li><a href="about.php">About</a></li>
					<li><a href="blog.php">Blog</a></li>
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
		<script src="fancy_blog.min.js"></script>
	</body>
</html>  