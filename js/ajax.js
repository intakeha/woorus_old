// Validate forms via jQuery
$(document).ready(function(){
		
	// Submit login form via ajax
/*	$('#login_form').submit(function(){
		$.post(
			"actions/login.php",
			$('#login_form').serialize(),
			function(data){
				$('#auth_error').text(data); 
			}
		);
		return false;
	 }); 
*/	
	// Submit recover form via ajax	
	$('#recover_form').submit(function(){
		$.post(
			"actions/forgotPassword.php",
			$('#recover_form').serialize(),
			function(data){
				$('#auth_error').text(data); 
			}
		);
		return false;
	 }); 	
	
	// Submit registration form via ajax to validate user info and show captcha	
	$('#validate_button').click(function(){
		$.post(
			"actions/register_0.php",
			$('#registration_form').serialize(),
			function(data){
				if (data){
					$('#registration_error').text(data); 
				}else{
					$('#facebook_login').hide();
					$('#userInfo').hide();
					$('#reg_error_container').hide();
					$('#captcha').show();
				}
			}
		);
		return false;
	});
	
	// Submit registration form via ajax if captcha	passes
	$('#registration_form').submit(function(){
		$.post(
			"actions/register.php",
			$('#registration_form').serialize(),
			function(data){
				if (data){
					$('#reg_error_captcha').text(data); 
				}else{
					$('#reg_error_captcha').html("<span>Welcome to Woorus!</span><br>Please check your email to activate your account.");
					$('#captcha').hide();
				}
			}
		);
		return false;
	}); 	
});