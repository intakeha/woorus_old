// Using jQuery to validate forms, display slide shows, and set recatpcha settings
$(document).ready(function(){
	
	// Slide show function with slidesjs
	$("#slide_show").slides({
			preload: true,
			preloadImage: 'images/global/loading.gif',
			play: 8000,
			pause: 2500,
			hoverPause: true
	});
	
	// Validate login form
	$("#login_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#auth_error").text(validator.errorList[0].message);
			}
		},
		submitHandler: function(form) {
			$.post(
				"actions/login.php",
				$('#login_form').serialize(),
				function(data){
					if (data.success == 0){
						if ($('#auth_error').hasClass('success_text')){
							$('#auth_error').removeClass('success_text').addClass('error_text');
						}
						$('#auth_error').text(data.message); 
					}else{
						window.location.href = "canvas.php";			
					}
				}, "json"
			);
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {						// Adding validation rules for each input //

		},
		messages: {						// Customized error messages for each error //

		}
	});	
		
	// Validate forgot password form
	$("#recover_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#auth_error").text(validator.errorList[0].message); 
			}
		},
		submitHandler: function(form) {

			$.post(
				"actions/forgotPassword.php",
				$('#recover_form').serialize(),
				function(data){
					if (data.success == 0){
						if ($('#auth_error').hasClass('success_text')){
							$('#auth_error').removeClass('success_text').addClass('error_text');
						}
						$('#auth_error').text(data.message); 
					}else{
						if ($('#auth_error').hasClass('error_text')){
							$('#auth_error').removeClass('error_text').addClass('success_text');
						}
						$('#auth_error').text(data.message); 			
					}
				}, "json"
			);

/*			jQuery(form).ajaxSubmit({
				target: "#auth_error"
			});
*/

		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {

		},
		messages: {

		}
	});	
	
	// Validate registration form
	$("#registration_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: true,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#registration_error").text(validator.errorList[0].message); 
			}
		},
		submitHandler: function(form) {
			$.post(
				"actions/register.php",
				$('#registration_form').serialize(),
				function(data){
					if (data.success == 0){
						$('#registration_error').text(data.message); 
					}else{
						$('#facebook_login').hide();
						$('#userInfo').hide();
						$('#reg_error_container').hide();
						$('#captcha').show();
						$('#reg_error_captcha').show();				
					}
				}, "json"
			);
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {

		},
		messages: {
		}
	});	
	
	// Submit registration form via ajax if captcha	passes
	$('#join_button').click(function(){
		$.post(
			"actions/register_submit.php",
			$('#registration_form').serialize(),
			function(data){
				if (data.success == 0){
					$('#reg_error_captcha').html(data.message); 
				}else{
					$('#reg_error_captcha').html("<span>Welcome to Woorus!</span><br>Please check your email to activate your account.");
					$('#captcha').hide();
				}
			}, "json"
		);
		return false;
	});
	
	// Validate forgot password form in the _recover.php
	
	$("#forgot_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#forgot_form_error").text(validator.errorList[0].message); 
			}
		},
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				target: "#forgot_form_error"
			});
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {
		},
		messages: {
		}
	});		
	
	// Validate settings form in the _settings.php
	$("#settings_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: true,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$('#settings_success').hide();
				$('#settings_error').show();
				$("#settings_error").text(validator.errorList[0].message); 
			}
		},
		submitHandler: function(form) {
			$.post(
				"actions/changeSettings.php",
				$('#settings_form').serialize(),
				function(data){
					$('#settings_error').hide();
					$('#settings_success').hide();
					if (data.success == 0){
						$('#settings_error').show();
						$('#settings_error').text(data.message);
					}else{
						$('#settings_success').show();
						$('#settings_success').text(data.message);
					}
				},
				"json"
			);
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {
		},
		messages: {
		}
	});	
	
	// Validate settings form in the _settings.php for those who have not created a password
	$("#settings_form_c").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#settings_error").text(validator.errorList[0].message); 
			}
		},
		submitHandler: function(form) {
			$.post(
				"actions/changeSettings.php",
				$('#settings_form_c').serialize(),
				function(data){
					$('#settings_error').hide();
					$('#settings_success').hide();
					if (data.success == 0){
						$('#settings_error').show();
						$('#settings_error').text(data.message);
					}else{
						$('#settings_success').show();
						$('#settings_success').text(data.message);
					}
				},
				"json"
			);
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {
		},
		messages: {
		}
	});	
	
});

jQuery.validator.addMethod("validname", function(value, element) {
	return this.optional(element) || /^[a-zÀ-ÖØ-öø-ÿ-'\s]+$/i.test(value);
}, "The name contains invalid characters.");

jQuery.validator.addMethod("startsymbol", function(value, element) {
	return this.optional(element) || !value.match(/^[ |'|-]/);
}, "The field should not start or end with a symbol.");

jQuery.validator.addMethod("endsymbol", function(value, element) {
	return this.optional(element) || !value.match(/[ |'|-]$/);
}, "The field should not start or end with a symbol.");

jQuery.validator.addMethod("selectfield", function(value, element) {
	if(element.value == -1){return false;} else return true;
}, "Please select an option.");

jQuery.validator.addMethod("checkgender", function(value, element) {
	if(!((element.value == "F") || (element.value == "M"))){return false;} else return true;
}, "Please select an option.");

jQuery.validator.addMethod("validcity", function(value, element) {
	return this.optional(element) || /^[a-zÀ-ÖØ-öø-ÿ-',\s]+$/i.test(value);
}, "The name contains invalid characters.");

// Captcha Theme Settings //
var RecaptchaOptions = { 
	theme : 'clean'
};