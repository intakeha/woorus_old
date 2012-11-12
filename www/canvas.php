<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">	
<head>
    <title>Woorus - Connecting you to the world through interests</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-language" content="en"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
	<script type="text/javascript" src="js/jquery.SWFObject.js"></script> 
    <script type="text/javascript" src="js/jstz.min.js"></script> 
    <script type="text/javascript" src="js/woorus.js"></script>
    <script type="text/javascript" src="http://staging.tokbox.com/v0.91/js/TB.min.js"></script>

	<?php 
	    include_once('actions/opentok/createSession.php');

		$page = $_REQUEST['page'];
		session_start();  
		if(isset($_SESSION['id'])){
				$pages = array("home", "mosaic", "search", "contacts", "lounge", "mail", "trends", "external", "settings", "recover");
				if (!in_array($page, $pages)) header("location: canvas.php?page=home");
		} else {
			 header("location: http://pup.woorus.com");
		}
	?>
    <script type="text/javascript" charset="utf-8">
        var apiKey = <?php print API_Config::API_KEY?>;
        var sessionId = '<?php print $session_id; ?>';
        var token = '<?php print $apiObj->generate_token($session_id); ?>';
        
        TB.setLogLevel(TB.DEBUG);
        
        var session = TB.initSession(sessionId);   
        session.connect(apiKey, token);
        
        session.addEventListener('sessionConnected', sessionConnectedHandlerSelf);
		session.addEventListener('signalReceived', signalHandler);
		session.addEventListener('streamCreated', streamCreatedHandler);
	 
        function sessionConnectedHandlerSelf(event) {
            return;
        };
        
        function sessionConnectedHandlerOther(event) {
            session.signal();
        };
        
		function streamCreatedHandler(event) {
			subscribeToStreams(event.streams);
		}
		
        function signalHandler(event) {
			var subscriberProps = {width: 220, height: 165};
			if ($('input[name=caller]').val() == 1){
				if (event.fromConnection.connectionId != session.connection.connectionId) {
					$('#modal_calling').fadeOut();
					$('.ring_tone').flash().remove();				
					modal('#modal_talk','810','200');
					session.publish('stream_caller', subscriberProps);
				};
			} else {
				if (event.fromConnection.connectionId != session.connection.connectionId) {
					$('.ring_tone').flash({swf:'media/ringtone.swf',height:1,width:1});
					modal_message('#modal_answer','300','200');
				} else {
					$('#modal_answer').fadeOut();
					$('.ring_tone').flash().remove();
					modal('#modal_talk','810','200');
					session.publish('stream_caller', subscriberProps);
				};
			};
        };
		
		function subscribeToStreams(streams) {
			if (streams.length > 0){
				for (var i = 0; i < streams.length; i++) {
					var subscriberProps = {width: 220, height: 165};
					if (streams[i].connection.connectionId != session.connection.connectionId) {
						session.subscribe(streams[i], 'stream_callee', subscriberProps);
					};
				};
			};
		}
        
        function disconnect(){
            session.disconnect();
        };
         
    </script>    
    
</head>
<body>
	<input type="hidden" name="caller" value="0" />
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '113603915367848', // App ID
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true, // parse XFBML
          oauth      : true
        });
    
        // Additional initialization code here
        FB.Event.subscribe('auth.login', function () {
            //window.location = "actions/facebookSession.php";
            window.location.reload();
        });
        FB.getLoginStatus(function(response) {
          if (response.status === 'connected') {
            $('#facebook_login').hide();
          }
         });
      };
    
      // Load the SDK Asynchronously
      (function(d){
         var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         d.getElementsByTagName('head')[0].appendChild(js);
       }(document));
    </script>
	<div class="globalContainer">
    	<div onclick="modal('#modal_talk','810','200');" style="height: 10px;">Click to Talk</div>
		<?php
			include('templates/_modal.php');
			include('templates/_header.php');
			include('templates/_'.$page.'.php');
			include('templates/_footer.php');
		?>
	</div>
</body>
</html>