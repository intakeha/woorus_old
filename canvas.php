<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
<head>
    <title>Woorus - Connecting you to the world through interests</title>
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
    <script src="http://staging.tokbox.com/v0.91/js/TB.min.js"></script>
	<?php 
		$page = $_REQUEST['page'];
		$pages = array("home", "mosaic", "search", "contacts", "lounge", "mail", "trends", "external", "chat", "settings", "recover");
		if (!in_array($page, $pages)) header("location: canvas.php?page=home");
	?>
</head>
<body>
	<div class="globalContainer">
    <div onclick="modal('#modal_add','300','200');" style="height: 10px;">Add to Contacts</div>
		<?php
			include('templates/_modal.php');
			include('templates/_header.php');
			include('templates/_'.$page.'.php');
			include('templates/_footer.php');
		?>
	</div>
</body>
</html>