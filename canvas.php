<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
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
    <script type="text/javascript" src="actions/swfobject.js"></script>
    <script type="text/javascript" src="js/jquery.crop.js"></script> 
    <script type="text/javascript" src="js/woorus.js"></script>

	<?php 
		$page = $_REQUEST['page'];
		$pages = array("home", "mosaic", "search", "contacts", "lounge", "mail", "trends", "external", "chat", "settings", "recover");
		if (!in_array($page, $pages)) header("location: canvas.php?page=home");
	?>
</head>
<body>
	<div id="incomingCall" class="popup_block">
    	<div>Incoming call from</div>
        <button id="answer" class="buttons">Answer</button><button id="decline" class="buttons">Decline</button>
        <div id="ring"></div>
	</div>
    <div id="videoPhone" class="popup_block">
	</div>
    
	<div class="globalContainer">
    <div id="testCall" class="buttons">Click to call</div>
		<?php
			include('templates/_header.php');
			include('templates/_'.$page.'.php');
			include('templates/_footer.php');
		?>
	</div>
    <script type="text/javascript">
		$(document).ready(function(){			
			// Send status to backend for last active
			$.post("actions/updateOnlineStatus.php", {onlineStatus: "1" } );

			// Test Call Button
			$('#testCall').click(function() {
				$.post("actions/callUser.php", 
					function(data) {
					}, 
					"json"
				);
			});
			
			// Listening for calls
			window.setInterval(callListen, 3000);
			function callListen() {
				$.post("actions/incomingCallSearch.php", 
					function(data) {
						alert(data.conversation_id);
						if (data){
							modal('#incomingCall','300');
							$('#ring').flash({swf:'media/ringtone.swf', height:1, width:1});
							$('#videoPhone').flash({
								swf:'actions/VideoPhoneCaller.swf',
								height: 630,
								width: 530,
								quality: 'high',
								bgcolor: '#ffffff',
								allowScriptAccess: 'sameDomain',
								allowFullScreen: true,
								movie: 'VideoPhoneCaller.swf',
								classid: 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000',
								flashvars: {
									userIdHTML: data.callee_id,
									friendIdHTML: data.caller_id,
									conversationIdHTML: data.conversation_id
								},
								attributes: {
									id: 'VideoPhoneCaller',
									name: 'VideoPhoneCaller',
									align: 'middle'
								}
							});
						}
					},
					"json"
				);
			}
			
			// Listening for answered calls
			window.setInterval(answeredCallListen, 3000);
			function answeredCallListen() {
				$.post("actions/answeredCallSearch.php", 
					function(data) {
						if (data){
							$('#videoPhone').flash({
								swf:'actions/VideoPhoneCallee.swf',
								height: 630,
								width: 530,
								quality: 'high',
								bgcolor: '#ffffff',
								allowScriptAccess: 'sameDomain',
								allowFullScreen: true,
								flashvars: {
									userIdHTML: data.callee_id,
									friendIdHTML: data.caller_id,
									conversationIdHTML: data.conversation_id
								}
							});
							modal('#videoPhone','600');
						}
					}, 
					"json"
				);
			}
			
			$('#answer').click(function() {
				$('#fade , .popup_block').fadeOut(function() {
					$('#fade, a.close').remove();
				});
				modal('#videoPhone','600');
			});
			
			// Modal popup
			function modal(modalID, modalWidth){
		
				//Fade in the Popup and add close button
				$(modalID).fadeIn().css({ 'width': Number( modalWidth ) }).prepend('<a href="#" class="close"><img src="/images/global/close_modal.png" class="btn_close" title="Close Window" alt="Close" /></a>');
			
				//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
				var popMargTop = ($(modalID).height() + 80) / 2;
				var popMargLeft = ($(modalID).width() + 80) / 2;
			
				//Apply Margin to Popup
				$(modalID).css({
					'margin-top' : -popMargTop,
					'margin-left' : -popMargLeft
				});
			
				//Fade in Background
				$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
				$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies 
			
				return false;
			
			}
			
			//Close Popups and Fade Layer
			$('a.close, #fade').live('click', function() {
				$('#fade , .popup_block').fadeOut(function() {
					$('#fade, a.close').remove();
				});
				return false;
			});
		});
	</script>
</body>
</html>