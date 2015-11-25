<?php
if (!isset($_GET['id']) || !isset($_GET['site'])) { header('Location: .'); exit(); }
$id = $_GET['id'];
$site = $_GET['site'];
include_once('settings.php');
$con = new mysqli($fancyVars['dbaddr'], $fancyVars['dbuser'], $fancyVars['dbpass'], $fancyVars['dbname']);
mysqli_set_charset($con, "utf8");

if ($id == 0) {
	$html = '';
	$name = 'Enter name here';
}
else {
	$section = $con->query("SELECT * FROM `{$con->real_escape_string($site)}` WHERE `id` = '{$con->real_escape_string($id)}';");
	$row = $section->fetch_array(MYSQLI_ASSOC);
	$html = $row['html'];
	$name = $row['name'];
}

if (isset($_POST['html'])) { $html = urldecode($_POST['html']); }
if (isset($_POST['name'])) { $name = urldecode($_POST['name']); }

$site = $_GET['site'];

//Get elements
$sql = $con->query("SELECT `id`, `name` FROM `{$con->real_escape_string($site)}` WHERE 1;");
$elements = array();
foreach ($sql as $row) {
	$elements[] = $row;
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

	<title><?php echo $site; ?> - Fancy Dashboard</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="css/medium-editor.min.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="css/editor-theme.css" type="text/css" media="screen" charset="utf-8">

	<!-- My CSS -->
	<link href="css/style.css" rel="stylesheet">

	<script src="js/jquery.js"></script>
	<script src="js/markdown.min.js"></script>
	<script src="js/mammoth.browser.min.js"></script>
	<script src="js/emmet.min.js"></script>
	<script src="js/medium-editor.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<script>
		$(document).ready(function() {
			var result = {
				value: <?php echo json_encode($html); ?>
			}
			displayResult(result);

			var editor = new MediumEditor('#visualEditor', {
				placeholder: {
					<?php
					if ($html == '') {
						echo "text: 'Click here to start editing'";
					}
					else {
						echo "text: ''";
					}
					?>
				},
				buttons: ["bold", "italic", "underline", "header1", "header2", "anchor", "image", "quote", "removeFormat"],
				firstHeader: 'h1',
				secondHeader: 'h2'
			});

			if (localStorage.getItem('defaultEditor') == 'simple') $('#defaulter').hide(); 
		});


		function noscript(strCode){
			var html = $(strCode.bold()); 
			html.find('script').remove();
			return html.html();
		}

		function displayResult(result) {
			$('#visualEditor').html(noscript(result.value));
		}

		function save(publish) {
			var raw = $('#name').text();
			var formatted = $('<textarea />').html(raw).val();
			if (publish) {
				$('<form action="<?php echo 'update.php?site='.$site.'&id='.$id; ?>" method="POST">' +
				  '<input type="hidden" name="html" value="'+encodeURIComponent($('#visualEditor').html())+'">' +
				  '<input type="hidden" name="name" value="'+encodeURIComponent(formatted)+'">' +
				  '<input type="hidden" name="id" value="<?php echo $id; ?>">' +
				  '<input type="hidden" name="site" value="<?php echo $site; ?>">' +
				  '</form>').appendTo("body").submit();
			}
			else {
				$('<form action="<?php echo 'edit.php?site='.$site.'&id='.$id; ?>" method="POST">' +
				  '<input type="hidden" name="html" value="'+encodeURIComponent($('#visualEditor').html())+'">' +
				  '<input type="hidden" name="name" value="'+encodeURIComponent(formatted)+'">' +
				  '<input type="hidden" name="id" value="<?php echo $id; ?>">' +
				  '<input type="hidden" name="site" value="<?php echo $site; ?>">' +
				  '</form>').appendTo("body").submit();
			}
		}
	</script>

	<style>
		.well, .navbar, .popover, .btn, .tooltip, input, select, textarea, pre, .progress, .modal, .add-on, .alert, .table-bordered, .nav>.active>a, .dropdown-menu, .tooltip-inner, .badge, .label, .img-polaroid {
		 	-moz-box-shadow: none !important;
			-webkit-box-shadow: none !important;
			box-shadow: none !important;
			-webkit-border-radius: 0px !important;
			-moz-border-radius: 0px !important;
			border-radius: 0px !important;
			border-collapse: collapse !important;
			background-image: none !important;
		}

		body {
			background-color: #fff;
			height: 100%;
		}

		#visualEditor {
			background-color: #fff;
			width: 50%;
			margin: auto;
		}

		@media (max-width: 768px) {
			#visualEditor {
				width: 100%;
			}
		}
	</style>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<span id="name" class="navbar-brand" contenteditable="true"><?php echo $name; ?></span>
		</div>
		<div class="navbar-collapse collapse navbar-responsive-collapse">
			<ul class="nav navbar-nav">
				<!-- Save -->
				<li><a href="javascript:void(0);" onclick="save(true);">Save</a></li>
				<li><a id="defaulter" href="javascript:void(0);" onclick="localStorage.setItem('defaultEditor', 'simple'); if (localStorage.getItem('defaultEditor') == 'simple') $('#defaulter').hide();">Set this as the default editor</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="javascript:void(0);" onclick="save(false);"><< Back to Power Editor</a></li>
			</ul>
		</div>
	</div>

	<div id="visualEditor"></div>
</body>
</html>