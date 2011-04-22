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
						return false; 
                    } else {
						$.post(
							"actions/forgotPassword.php",
							$('#recover_form').serialize(),
							function(data){
								$('#auth_error').text(data); 
							}
						);
						return false;
					}
                },
		errorPlacement: function(error, element) {
                    // Override error placement to not show error messages beside elements //
                },
		rules: {						// Adding validation rules for each input //
			email: {
				required: true,
				email: true
			},
			password: "required"
		},
		messages: {						// Customized error messages for each error //
			email: {
				required: "Please enter your email address.",
				email: "Please enter a valid email address."
			},
			password: "Please enter a password."	
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
		errorPlacement: function(error, element) {
                    // Override error placement to not show error messages beside elements //
                },
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: "Please enter your email address.",
				email: "Please enter a valid email address."
			}
		}
	});	
	
	// Validate registration form
	$("#registration_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                        $("#registration_error").text(validator.errorList[0].message); 
                    }
                },
		errorPlacement: function(error, element) {
                    // Override error placement to not show error messages beside elements //
                },
		rules: {
			first_name: {
				required: true,
				validname: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 30
			},
			last_name: {
				required: true,
				validname: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,		
				maxlength: 60
			},
			email: {
				required: true,
				email: true,
				maxlength: 254
			},
			confirm_email: {
				required: true,
				equalTo: "#email",
				maxlength: 254
			},
			password: {
				required: true,
				rangelength: [6,20]
			},
			gender: {
				selectfield: true,
				checkgender: true
			},
			birthday_month: {
				selectfield: true,
				range: [1, 12]
			},
			birthday_day: {
				selectfield: true,
				range: [1, 31]
			},
			birthday_year: {
				selectfield: true,
				range: [1905, 2011]
			},
			city: {
				required: true,
				validcity: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 255
			}
		},
		messages: {
			first_name: {
				required: "Please fill in all fields.",
				validname: "First name contains invalid characters.",
				startsymbol: "First name should not start or end with a symbol.",
				endsymbol: "First name should not start or end with a symbol.",
				minlength: "Please provide your real first name.",
				maxlength: "Please enter no more than 30 characters for your first name."
			},
			last_name: {
				required: "Please fill in all fields.",
				validname: "Last name contains invalid characters.",
				startsymbol: "Last name should not start or end with a symbol.",
				endsymbol: "Last name should not start or end with a symbol.",
				minlength: "Please provide your real last name.",
				maxlength: "Please enter no more than 60 characters for your last name."		
			},
			email: {
				required: "Please fill in all fields.",
				email: "Please enter a valid email address.",
				maxlength: "Please enter no more than 254 characters for your email."
			},
			confirm_email: {
				required: "Please fill in all fields.",
				equalTo: "Please provide matching emails.",
				maxlength: "Please enter no more than 254 characters for your email confirmation."
			},
			password: {
				required: "Please fill in all fields.",
				rangelength: "Your password must be between 6 and 20 characters long."
			},
			gender: {
				selectfield: "Please select your gender.",
				checkgender: "Please select your gender."
			},
			birthday_month: {
				selectfield: "Please select your birthday month.",
				range: "Please select your birthday month."
			},
			birthday_day: {
				selectfield: "Please select your birthday date.",
				range: "Please select your birthday date."
			},
			birthday_year: {
				selectfield: "Please select your birthday year.",
				range: "Please select your birthday year."
			},
			city: {
				required: "Please fill in all fields.",
				validcity: "City contains invalid characters.",
				startsymbol: "City should not start or end with a symbol.",
				endsymbol: "City should not start or end with a symbol.",
				minlength: "Please provide your current city.",
				maxlength: "Please enter no more than 255 characters for your city."
			}
		}
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
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {
			new_password: {
				required: true,
				rangelength: [6,20]
			},
			confirm_password: {
				required: true,
				equalTo: "#new_password"
			}			
		},
		messages: {
			new_password: {
				required: "Please enter your new password.",
				rangelength: "Your new password must be between 6 and 20 characters long."
			},
			confirm_password: {
				required: "Please confirm your new password.",
				equalTo: "Your new passwords do not match."
			}
		}
	});		
	
	// Validate settings form in the _settings.php
	$("#settings_form").validate({
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
		errorPlacement: function(error, element) {
                    // Override error placement to not show error messages beside elements //
                },
		rules: {
			first_name: {
				required: true,
				validname: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 30
			},
			last_name: {
				required: true,
				validname: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 60	
			},
			gender: {
				selectfield: true,
				checkgender: true
			},
			birthday_month: {
				selectfield: true,
				range: [1, 12]
			},
			birthday_day: {
				selectfield: true,
				range: [1, 31]
			},
			birthday_year: {
				selectfield: true,
				range: [1905, 2011]
			},
			city: {
				required: true,
				validcity: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 255
			},
			new_email: {
				email: true,
				maxlength: 254
			},
			old_password: {
				required: function(element){
					return $('#new_password').val() != ''
				},
				rangelength: [6,20]
			},
			new_password: {
				required: function(element){
					return $('#old_password').val() != ''
				},
				rangelength: [6,20]
			},
			confirm_password: {
				required: function(element){
					return $('#new_password').val() != ''
				},
				equalTo: "#new_password"
			}
		},
		messages: {
			first_name: {
				required: "Please provide your first name.",
				validname: "First name contains invalid characters.",
				startsymbol: "First name should not start or end with a symbol.",
				endsymbol: "First name should not start or end with a symbol.",
				minlength: "Please provide your real first name.",
				maxlength: "Please enter no more than 30 characters for your first name."
			},
			last_name: {
				required: "Please provide in your last name.",
				validname: "Last name contains invalid characters.",
				startsymbol: "Last name should not start or end with a symbol.",
				endsymbol: "Last name should not start or end with a symbol.",
				minlength: "Please provide your real last name.",
				maxlength: "Please enter no more than 60 characters for your last name."
			},
			gender: {
				selectfield: "Please select your gender.",
				checkgender: "Please select your gender."
			},
			birthday_month: {
				selectfield: "Please select birthday month." ,
				range: "Please select your birthday month."
			},
			birthday_day: {
				selectfield: "Please select birthday date.",
				range: "Please select your birthday date."
			},
			birthday_year: {
				selectfield: "Please select birthday year.",
				range: "Please select your birthday year."
			},
			city: {
				required: "Please fill in your city.",
				validcity: "City contains invalid characters.",
				startsymbol: "City should not start or end with a symbol.",
				endsymbol: "City should not start or end with a symbol.",
				minlength: "Please provide your current city.",
				maxlength: "Please enter no more than 255 characters for your city."
			},
			new_email: {
				email: "Please enter a valid email address.",
				maxlength: "Please enter no more than 254 characters for your email."
			},
			old_password: {
				required: "Please enter your old password.",
				rangelength: "Your old password must be between 6 and 20 characters long."
			},
			new_password: {
				required: "Please enter your new password.",
				rangelength: "Your new password must be between 6 and 20 characters long."
			},
			confirm_password: {
				required: "Please confirm your new password.",
				equalTo: "Your new passwords do not match."
			}
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