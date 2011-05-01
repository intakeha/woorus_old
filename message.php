<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns=" http://www.w3.org/1999/xhtml ">
<head>
	<title>Woorus - The place to share your interests</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-language" content="en"/>
	<meta name="keywords" content="video chats">
	<meta name="description" content="Connecting people through interests">
	<link href="css/woorus.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script> 
	<script type="text/javascript" src="js/jquery.form.js "></script>
	<script type="text/javascript" src="js/slides.min.jquery.js "></script> 
  	<script type="text/javascript" src="js/woorus.js"></script>

	<?php 
		$messageID = $_REQUEST['messageID'];
		switch ($messageID) {
			case 1:
				$message = "Account has already been activated.";
				break;
			case 2:
				$message = "Incorrect token to activate account.";
				break;
			case 3:
				$message = "Your account has been deactivated and will be waiting for you whenever you want to come back.";
				break;
		}
	?>

</head>
<body>
    <div class="bg_canvas"></div>
	<div class="globalContainer">
        <div id="message_bg">
        	<div id=user_messages><?php echo $message ?></div>
        </div>  
        <?php include('templates/_footer.php');?>
    </div>
</body>
</html>