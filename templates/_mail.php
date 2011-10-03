<div id="mail">
	<div id="mail_panel">
    	<div id="mailbox">
        	<div id="inbox"></div>
            <div id="sent"></div>
        </div>
        <ul id="messages">
        
        <!--
            <li><div class="message_container" id="top_message">
            	<a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div class="message_info"><div class="float_right">7:27 PM</div>Brad</div>
                <div class="message_preview message_new">
                	<div class="float_right"><div class="mail_archive"></div><div class="envelope mail_new"></div></div>
                    Hi! I see that you also like Ippudo ramen.  How about we meet up this Saturday...
                </div>
                </div>
            </li>
            <li>
            	<div class="message_container">
            	<a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div class="message_info"><div class="float_right">7:27 PM</div>Brad</div>
                <div class="message_preview message_new">
                	<div class="float_right"><div class="mail_archive"></div><div class="envelope mail_new"></div></div>
                    Hi! I see that you also like Ippudo ramen.  How about we meet up this Saturday...
                </div>
                </div>
            </li>
            <li id="selected_message">
            <div class="message_container">
            	<a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div class="message_info"><div class="float_right">7:27 PM</div>Brad</div>
                <div class="message_preview">
                	<div class="float_right"><div class="mail_archive"></div><div class="envelope mail_read"></div></div>
                    Hi! I see that you also like Ippudo ramen.  How about we meet up this Saturday...
                </div>
            </div>
            </li>
            <li>
            <div class="message_container">
            	<a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div class="message_info"><div class="float_right">7:27 PM</div>Brad</div>
                <div class="message_preview">
                	<div class="float_right"><div class="mail_archive"></div><div class="envelope mail_read"></div></div>
                    Hi! I see that you also like Ippudo ramen.  How about we meet up this Saturday...
                </div>
            </div>
            </li>
            <li>
            <div class="message_container">
            	<a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div class="message_info"><div class="float_right">7:27 PM</div>Brad</div>
                <div class="message_preview">
                	<div class="float_right"><div class="mail_archive"></div><div class="envelope mail_read"></div></div>
                    Hi! I see that you also like Ippudo ramen.  How about we meet up this Saturday...
                </div>
            </div>
            </li>
        -->
            
        </ul>
        <div>
        	<div class="pagination_mail" id="mail_left"><div>Prev</div><a class="arrows pagination_left" href="#"></a></div>
        	<div class="pagination_mail" id="mail_right"><a class="arrows pagination_right float_right" href="#"></a><div>Next</div></div>
        </div>
    </div>
    <div id="message_panel">
    	<div id="message_container">
<!--            <div id="mail_profile">
                <a class="search_profile" href="#"><img src="images/users/james.png"></a>
                <div id="message_user_info">
                    <div class="social_status float_right"></div>
                    <div class="social_status warning_status float_right"></div>
                    Brad <br /><span>February 14, 2012 8:23 PM</span>
                    <div id="message_online_status" class="online_status"></div>
				</div>
                <div class="action_buttons">
                    <a class="add_button_sm" href="#"></a>
                    <a class="write_button_sm" href="#"></a>
                    <a class="talk_button_sm" href="#"></a>
                </div>
            </div>
-->
            <div id="message">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><br /><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit it</p><br /><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><br /><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit it</p><br />
            </div>
            <div id="response">
            	<form id="message_reply_form" action="../actions/reply.php" method="POST">
                    <textarea id="response_box" name="response" cols="69" rows="7"></textarea>
                    <input class="buttons float_right" id="reply_button" type="submit" name="reply" value="Reply">
                </form>
            </div>
        </div>
    </div>
    <form id="message_form" action="../actions/showMessages.php" method="POST">
        <input type="hidden" name="offset" value="0" />
		<input type="hidden" name="inbox_or_sent" value="inbox" />
    </form>  
</div>

<script type="text/javascript">

	$(document).ready(function(){
		
		// Remove all pagination arrows
		$('.pagination_mail').hide();
		showMailbox();

		function showMailbox(){
			$.post(
				"actions/showMessages.php",
				$('#message_form').serialize(),
				function(data){
					$('#messages').empty();
					$.each(data, function(i, field){
						if (i == 0){
						}else{
							if (field.first_name){firstName = field.first_name} else {firstName = "Unknown"};
							if (field.profile_filename_small){	
								profilePic = "images/users/small/"+field.profile_filename_small;			
							} else { 
								profilePic = "images/global/silhouette_sm.png";
							}
							if (i == 1){top_message = "top_message"} else {top_message = ""}
							if (field.message_read == 0){readFont = "message_preview message_new"; envelope = "envelope mail_new"} else {readFont = "message_preview"; envelope = "envelope mail_read"}
							$('#messages').append('<li><div class="message_container" id="'+top_message+'"><div class="search_profile"><img src="'+profilePic+'"></div><div class="message_info"><div class="float_right">'+field.sent_time+'</div>'+firstName+'</div><div class="'+readFont+'"><div class="float_right"><div class="mail_archive" id="'+field.message_id+'"></div><div class="'+envelope+'"></div></div>'+field.message_text+'</div></div></li>');
						}
					});
				}, "json"
			);
		}
				
		$('#inbox').click(function() {
			$('input[name=inbox_or_sent]').val('inbox');
			showMailbox();
		});
		
		$('#sent').click(function() {
			$('input[name=inbox_or_sent]').val('sent');
			showMailbox();
		});
		
		$('li').live('click', function(event) {
			$('li').removeAttr('id');
			$(this).attr("id","selected_message").find('div.envelope').removeClass('mail_new').addClass('mail_read');
			$(this).find('div.message_preview').removeClass('message_new');
			$.ajax({
				type: "POST",
				url: "actions/readMessage.php",
				data: "message_id="+$(this).find('div.mail_archive').attr('id')+"&inbox_or_sent="+$('input[name=inbox_or_sent]').val(),
				success: function(data){
					$('#mail_profile').empty();
					alert(data);
					$('#mail_profile').append('<a class="search_profile" href="#"><img src="images/users/james.png"></a><div id="message_user_info"><div class="social_status float_right"></div><div class="social_status warning_status float_right"></div>Brad<br /><span>February 14, 2012 8:23 PM</span><div id="message_online_status" class="online_status"></div></div><div class="action_buttons"><a class="add_button_sm" href="#"></a><a class="write_button_sm" href="#"></a><a class="talk_button_sm" href="#"></a></div>');
				}
			});
		});
		
		// Hover bar on mailbox message
		$("li").live("mouseover mouseout",
		function (event) {
		  if (event.type == "mouseover") $(this).addClass("hover_message");
		  else $(this).removeClass("hover_message");
		});

		$('.mail_archive').live('click', function(event) {
			 $.ajax({
				type: "POST",
				url: "actions/deleteMessage.php",
				data: "message_id="+event.target.id+"&inbox_or_sent="+$('input[name=inbox_or_sent]').val(),
				success: function(){
					showMailbox();
				}
			 });
		});
			
	})
		
</script>