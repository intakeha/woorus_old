<!DOCTYPE HTML>
<html>
<head>
	<title>Woorus - The place to share your interests</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-language" content="en"/>
	<meta name="keywords" content="video chats">
	<meta name="description" content="Connecting people through interests">
	<link href="css/woorus.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script> 
	<script type="text/javascript" src="js/slides.min.jquery.js"></script> 
   	<script type="text/javascript" src="js/jquery.idle-timer.js"></script> 
	<script type="text/javascript" src="js/jquery.crop.js"></script> 
	<script type="text/javascript" src="js/woorus.js"></script>

	<?php 
		$page = $_REQUEST['page'];
		$pages = array("home", "mosaic", "search", "contacts", "lounge", "mail", "trends", "settings", "recover");
		if (!in_array($page, $pages)) header("location: canvas.php?page=home");
	?>
</head>
<body>
	<div class="globalContainer">
		<?php
			include('templates/_header.php');
			include('templates/_'.$page.'.php');
			include('templates/_footer.php');
		?>
	</div>
</body>
</html>