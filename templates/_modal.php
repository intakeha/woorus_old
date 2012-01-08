<div id="modal_talk" class="popup_block">
	<div id="caller_info">
    	<div id="caller_profile">
            <div><img src="images/users/large/2_profile_1324364107.jpg"></div>
            <div><span>John</span><br />San Francisco, CA</div>
        </div>
        <div id="talk_stream">
            <div class="streams"><div id="stream_callee"></div></div>
            <div class="streams"><div id="stream_caller"></div></div>
        </div>
        <div id="talk_actions">
            <div>Time Remaining<br /><span>5:00</span><div id="talk_error">John is blocked.</div></div>
			<div id="talk_buttons" class="action_buttons">
                <div class="action_button_sm block_button_sm"></div>
                <div class="action_button_sm add_button_sm"></div>
                <div class="action_button_sm end_button_sm"></div>
			</div>
        </div>
    </div>
	<div id="caller_interests">
    	<ul class="talk_tiles"><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Water&quot;)"><img src="images/interests/119_1306217416.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;David Beckham&quot;)"><img src="images/interests/facebook_84218631570.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Dock&quot;)"><img src="images/interests/119_1306217128.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Dock&quot;)"><img src="images/interests/142_1322134480.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Face&quot;)"><img src="images/interests/119_1306465015.jpg"></li></ul>
        <ul class="talk_tiles" style="margin-left: 20px;"><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Water&quot;)"><img src="images/interests/119_1306217416.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;David Beckham&quot;)"><img src="images/interests/facebook_84218631570.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Dock&quot;)"><img src="images/interests/119_1306217128.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Dock&quot;)"><img src="images/interests/142_1322134480.jpg"></li><li onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), &quot;Face&quot;)"><img src="images/interests/119_1306465015.jpg"></li></ul>
	</div>
</div>
<div id="modal_calling" class="popup_block">
	<p id="calling_message">Calling...</p><div class="ring_tone"></div>
</div>
<div id="modal_answer" class="popup_block">
	<p id="calling_message">You have an incoming call.</p>
    <button id="answer" class="buttons modal_buttons">Answer</button>
    <button id="ignore" class="buttons modal_buttons cancel">Ignore</button>
    <div class="ring_tone"></div>
</div>
<div id="modal_write" class="popup_block">
	<div id="modal_write_header">
    	<img>
        <div id="modal_write_to">To:</div>
		<span></span><br /><p></p>
    </div>
    <form id="send_message_form" action="actions/sendMessage.php" method="POST">
        <textarea id="message_box" name="mail_message"></textarea>
        <input type="hidden" name="user_id_mailee" value="" />
        <input class="buttons cancel float_right modal_buttons" id="modal_cancel" type="button" name="cancel" value="Cancel"/>
        <input class="buttons float_right modal_buttons" id="send_button" type="submit" name="send" value="Send"/>
    </form>
    <div id="message_error"></div>
</div>
<div id="modal_add" class="popup_block">
	<p id="add_message"></p><br />
    <button id="ok" class="buttons">Ok</button>
</div>

<script type="text/javascript">
	// Validate form and send reply to messages
	$("#send_message_form").validate({
		onsubmit: true,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$('#message_error').empty().removeClass().addClass('error_text');
				$('#message_error').append(validator.errorList[0].message);
			};
		},
		submitHandler: function(form) {
			$.post(
				"actions/sendMessage.php",
				$('#send_message_form').serialize(),
				function(data){
					$('#message_error').empty().removeClass();
					if (data.success == 0){
						$('#message_error').addClass('error_text').append(data.message);
					} else {
						$('#message_error').addClass('success_text').append(data.message);
						$('#send_message_form').hide();
					};
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
	
	// Modal popup function
	function modal(modalID, modalWidth, topMargin){
	
		//Fade in the Popup and add close button
		$(modalID).fadeIn().css({ 'width': modalWidth }).prepend('<a href="#" class="close"><img src="/images/global/close_modal.png" class="btn_close" title="Close Window" alt="Close" /></a>');
	
		//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
		//var popMargTop = ($(modalID).height() + 80) / 2;
		var popMargLeft = ($(modalID).width() + 80) / 2;
	
		//Apply Margin to Popup
		$(modalID).css({
			//'margin-top' : -popMargTop,
			'margin-top' : topMargin+'px',
			'margin-left' : -popMargLeft
		});
	
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies 
	
		return false;
	
	};
	
	// Modal popup function
	function modal_message(modalID, modalWidth, topMargin){
	
		//Fade in the Popup
		$(modalID).fadeIn().css({ 'width': modalWidth });
	
		//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
		//var popMargTop = ($(modalID).height() + 80) / 2;
		var popMargLeft = ($(modalID).width() + 80) / 2;
	
		//Apply Margin to Popup
		$(modalID).css({
			//'margin-top' : -popMargTop,
			'margin-top' : topMargin+'px',
			'margin-left' : -popMargLeft
		});
	
		return false;
	
	};	
	
	// Function to clear messages modal prior to loading receipient's info
	function clearModalMessages(){
		$('#message_box, input[name=user_id_mailee]').val('');
		$('#message_error').text('');
	};
	
</script>