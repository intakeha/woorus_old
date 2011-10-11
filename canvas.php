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
    <script type="text/javascript" src="js/jquery.SWFObject.js"></script> 
	<script type="text/javascript" src="js/jquery.crop.js"></script> 
	<script type="text/javascript" src="js/woorus.js"></script>

	<?php 
		$page = $_REQUEST['page'];
		$pages = array("home", "mosaic", "search", "contacts", "lounge", "mail", "trends", "external", "chat", "settings", "recover");
		if (!in_array($page, $pages)) header("location: canvas.php?page=home");
	?>
</head>
<body>
	<div id="modal" class="popup_block">
    This is a modal popup.
	</div>
	<div class="globalContainer">
    <a href="#?w=500" rel="modal" class="poplight">Click for Modal</a>
		<?php
			include('templates/_header.php');
			include('templates/_'.$page.'.php');
			include('templates/_footer.php');
		?>
	</div>
    <script type="text/javascript">
		$(document).ready(function(){	
			//When you click on a link with class of poplight and the href starts with a # 
			$('a.poplight[href^=#]').click(function() {
				var popID = $(this).attr('rel'); //Get Popup Name
				var popURL = $(this).attr('href'); //Get Popup href to define size
			
				//Pull Query & Variables from href URL
				var query= popURL.split('?');
				var dim= query[1].split('&');
				var popWidth = dim[0].split('=')[1]; //Gets the first query string value
			
				//Fade in the Popup and add close button
				$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="/images/global/close_modal.png" class="btn_close" title="Close Window" alt="Close" /></a>');
			
				//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
				var popMargTop = ($('#' + popID).height() + 80) / 2;
				var popMargLeft = ($('#' + popID).width() + 80) / 2;
			
				//Apply Margin to Popup
				$('#' + popID).css({
					'margin-top' : -popMargTop,
					'margin-left' : -popMargLeft
				});
			
				//Fade in Background
				$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
				$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies 
			
				return false;
			});
			
			//Close Popups and Fade Layer
			$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
				$('#fade , .popup_block').fadeOut(function() {
					$('#fade, a.close').remove();  //fade them both out
				});
				return false;
			});
			
		});
	</script>
</body>
</html>