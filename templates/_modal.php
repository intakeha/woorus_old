<div id="modal_talk" class="popup_block">
	
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
</script>