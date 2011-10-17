<div id="mail">
	<div id="mail_panel">
    	<div id="mailbox">
        	<div id="inbox"></div>
            <div id="sent" class="sent_0"></div>
        </div>
        <ul id="messages">
        </ul>
        <div>
        	<div class="pagination_mail" id="mail_left"><div>Prev</div><a class="arrows pagination_left" href="#"></a></div>
            <div id="mail_current_page"></div>
        	<div class="pagination_mail" id="mail_right"><a class="arrows pagination_right float_right" href="#"></a><div>Next</div></div>
        </div>
    </div>
    <div id="message_panel">
    	<div id="message_container">
            <div id="mail_profile"></div>
            <div id="message"></div>
            <div id="response">
            	<form id="message_reply_form" action="../actions/sendMessage.php" method="POST">
                    <input type="hidden" name="user_id_mailee" value="" />
                    <textarea id="response_box" name="mail_message" cols="62" rows="7"></textarea>
                    <input class="buttons float_right" id="reply_button" type="submit" name="reply" value="Reply">
                </form>
                <div id="reply_error"></div>
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
		
		// Remove pagination arrows
		$('.pagination_mail').hide();
		$('input[name=offset]').val('0');
		$('#mail_current_page').empty();
		showMailbox();

		// Load mailbox
		function showMailbox(){
			$.post(
				"actions/showMessages.php",
				$('#message_form').serialize(),
				function(data){
					$('#messages').empty();
					$.each(data, function(i, field){
						if (i == 0){
							if (field.message_count == 0){
								$('#message_container').hide();
								if ($('input[name=inbox_or_sent]').val()== "inbox"){
									$('#messages').append('<li><img src="images/global/mailbox_empty.png" id="empty_mailbox"><div id="top_message" class="no_messages">There are no messages in your inbox.</div></li>');
								} else {
									$('#messages').append('<li><img src="images/global/mailbox_empty.png" id="empty_mailbox"><div id="top_message" class="no_messages">There are no messages in your sent mail.</div></li>');
								}
							}
							var mailPages = Math.ceil(field.message_count/5);	
							var currentOffset = $('input[name=offset]').val();
							var currentPage = (currentOffset/5)+1;
							if (mailPages > 1){
								$('#mail_current_page').empty().append('Page '+currentPage+' of '+mailPages)
							}
							if (currentPage < mailPages) {
								$("#mail_right").show();
							} else {
								$("#mail_right").hide();
							}
							if (currentPage > 1) {
								$("#mail_left").show();
							} else {
								$("#mail_left").hide();
							}
						}else{
							if (field.first_name){firstName = field.first_name} else {firstName = "Unknown"};
							if (field.profile_filename_small){	
								profilePic = "images/users/small/"+field.profile_filename_small;			
							} else { 
								profilePic = "images/global/silhouette_sm.png";
							}
							if (i == 1){
								top_message = "top_message";
								showMessage(field.message_id);
							} else {top_message = "";}
							if (field.message_read == 0){readFont = "message_container message_new"; envelope = "envelope mail_new"} else {readFont = "message_container"; envelope = "envelope mail_read"}
							$('#messages').append('<li><div class="'+envelope+'" id="'+field.message_id+'"></div><div class="'+readFont+'" id="'+top_message+'"><div class="search_profile"><img src="'+profilePic+'"></div><div class="message_info"><div class="float_right">'+field.sent_time+'</div>'+firstName+'</div><div class="message_preview"><div class="float_right"><div class="mail_archive" id="'+field.message_id+'"></div></div>'+field.message_text+'</div></div></li>');
						}
					});
					$('#messages li:first-child').attr("id","selected_message");
				}, "json"
			);
		}
		
		// Inbox tab
		$('#inbox').click(function() {
			$('input[name=inbox_or_sent]').val('inbox');
			$('#inbox').removeClass();
			$('#sent').addClass('sent_0');
			showMailbox();
		});
		
		// Sent mail tab
		$('#sent').click(function() {
			$('input[name=inbox_or_sent]').val('sent');
			$('#sent').removeClass();
			$('#inbox').addClass('inbox_0');
			showMailbox();
		});
		
		// Click to view message
		$('div.message_container').live('click', function(event) {
			$('#messages').find('li#selected_message').find('div.envelope').removeClass('mail_new').addClass('mail_read');
			$('#messages').find('li#selected_message').find('div.message_container').removeClass('message_new');
			messageID = $('#messages').find('li#selected_message').find('div.mail_archive').attr('id');
			if (messageID){markAsRead(messageID, 'read');}
			$('li').removeAttr('id');
			$(this).parent('li').attr("id","selected_message");
			messageID = $(this).find('div.mail_archive').attr('id');
			if (messageID){showMessage(messageID);}
		});
		
		// Click to mark messages unread
		$('div.envelope').live('click', function(event) {
			messageID = $(this).attr('id');
			if (messageID){markAsRead(messageID, 'unread');}
			$(this).removeClass('mail_read').addClass('mail_new');
			$(this).next().addClass('message_new');
		});
		
		// Bind right pagination with mailbox
		$("#mail_right").click(function() {
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+5;
			$('input[name=offset]').val(nextOffset);
			$('#messages').empty();
			showMailbox();
		});
		
		// Bind left pagination with mailbox
		$("#mail_left").click(function() {
			var currentOffset = $('input[name=offset]').val();
			var prevOffset = parseInt(currentOffset)-5;
			$('input[name=offset]').val(prevOffset);
			$('#messages').empty();
			showMailbox();
		});
		
		// Marking messages as read
		function markAsRead(messageID, flag){
			$.ajax({
				type: "POST",
				url: "actions/markMessageAsRead.php",
				data: "message_id="+messageID+"&read_flag="+flag,
				success: function(){
			 	}
			});
		}
		
		// Show messages
		function showMessage(messageID){
			$.ajax({
				type: "POST",
				url: "actions/readMessage.php",
				data: "message_id="+messageID+"&inbox_or_sent="+$('input[name=inbox_or_sent]').val(), dataType: "json",
				success: function(data){
					$('#mail_profile').empty();
					$('#message').empty();
					$('#reply_error').empty().removeClass();
					$('textarea[name=mail_message]').val('');
					$('#message_container').show();
					$('#message_reply_form').show();
					$('input[name=user_id_mailee]').val(data.other_user_id);				
					if (data.first_name){firstName = data.first_name} else {firstName = "Unknown"};
					if (data.profile_filename_small){	
						profilePic = "images/users/small/"+data.profile_filename_small;			
					} else { 
						profilePic = "images/global/silhouette_sm.png";
					}
					switch (data.online_status){
						case "online":
							statusClass = "online_status"
							break
						case "offline":
							statusClass = "online_status offline_status"
							break
						case "away":
							statusClass = "online_status away_status"
							break
						case "busy":
							statusClass = "online_status busy_status"
							break
					};
					$('#mail_profile').append('<a class="search_profile" href="#"><img src="'+profilePic+'"></a><div id="message_user_info"><div class="social_status float_right"></div><div class="social_status warning_status float_right"></div>'+firstName+'<br /><span>'+data.sent_time+'</span><div id="message_online_status" class="'+statusClass+'"></div></div><div class="action_buttons"><a class="add_button_sm" href="#"></a><a class="write_button_sm" href="#"></a><a class="talk_button_sm" href="#"></a></div>');
					$('#message').append(data.message_text);
				}
			});
		}
		
		// Hover bar on mailbox message
		$("li").live("mouseover mouseout",
		function (event) {
		  if (event.type == "mouseover") $(this).addClass("hover_message");
		  else $(this).removeClass("hover_message");
		});
		
		// Archive messages
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
		
		// Validate form and send reply to messages
		$("#message_reply_form").validate({
			onsubmit: true,
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					$('#reply_error').empty().removeClass().addClass('error_text');
					$('#reply_error').append(validator.errorList[0].message);
				}
			},
			submitHandler: function(form) {
				$.post(
					"actions/sendMessage.php",
					$('#message_reply_form').serialize(),
					function(data){
						$('#reply_error').empty().removeClass();
						if (data.success == 0){
							$('#reply_error').addClass('error_text').append(data.message);
						} else {
							$('#reply_error').addClass('success_text').append(data.message);
							$('#message_reply_form').hide();
						}
							
					}, "json"
				);
			},
			errorPlacement: function(error, element) {
				// Override error placement to not show error messages beside elements //
			},
			rules: {						// Adding validation rules for each input //
				mail_message: {
					required: true
				}
			},
			messages: {						// Customized error messages for each error //
				mail_message: {
					required: "Please enter a reply message."
				}
			}
		});	
			
	})
		
</script>