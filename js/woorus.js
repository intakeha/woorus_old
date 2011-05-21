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
	
	// Submit registration form via ajax if captcha	passes
	$('#join_button').click(function(){
		$.post(
			"actions/register_submit.php",
			$('#registration_form').serialize(),
			function(data){
				if (data.success == 0){
					$('#reg_error_captcha').addClass('error_text');
					$('#reg_error_captcha').html(data.message); 
				}else{
					if ($('#reg_error_captcha').hasClass('error_text')) {$('#reg_error_captcha').removeClass('error_text');}
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
			$.post(
				"actions/savePassword.php",
				$('#forgot_form').serialize(),
				function(data){
					$('#forgot_form_error').hide();
					$('#forgot_form_success').hide();
					if (data.success == 0){
						$('#forgot_form_error').show();
						$('#forgot_form_error').text(data.message);
					}else{
						$('#forgot_form_success').show();
						$('#forgot_form_success').text(data.message);
						setTimeout('window.location.href="canvas.php"', 500);
					}
				},
				"json"
			);
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
						$('#settings_error').html(data.message);
					}else{
						$('#settings_success').show();
						$('#settings_success').html(data.message);
						$('#new_email').val('');
						$('#old_password').val('');
						$('#new_password').val('');
						$('#confirm_password').val('');
					}
				},
				"json"
			);
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
						$('#settings_error').html(data.message);
					}else{
						$('#settings_success').show();
						$('#settings_success').html(data.message);
						$('#new_email').val('');
						$('#new_password').val('');
						$('#confirm_password').val('');
					}
				},
				"json"
			);
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
			new_password: {
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
	
	// Upload picture file for tile crop
	$('#tile_pic_upload').click(function(){
		$.ajaxFileUpload({
			url: 'actions/upload_file.php',
			secureuri: false,
            fileElementId:'file',
            dataType: 'json',
			success: function(data){
				if (data.success == 0){
					$('#tile_upload_success').hide();
					$('#tile_upload_error').show();
					$('#tile_upload_error').html(data.message); 
				} else {
					$('.pagination_mosaic').hide();
					$('#tiles').hide();
					$('#tile_crop').show();
					$('input[name=assign_tag]').val('');
					$('.tile_pic').attr('src','images/temporary/'+data.message);
					$('input[name=cropFile]').val(data.message);
				}
			}
		})
		
		return false;
	});
	
	// Call imgAreaSelect to crop picture and associated coordinates
	$('.tile_pic').imgAreaSelect({
        handles: true,
		aspectRatio: "1:1",
		onSelectChange: previewTile,
		onSelectEnd: function (img, selection) {				
            $('input[name=x1]').val(selection.x1);
            $('input[name=y1]').val(selection.y1);
            $('input[name=x2]').val(selection.x2);
            $('input[name=y2]').val(selection.y2); 
	        $('input[name=w]').val(selection.width);
            $('input[name=h]').val(selection.height);
        }		
    });

	// Function used by imgAreaSelect to preview thumbnail	
	function previewTile(img, selection) {
		if (!selection.width || !selection.height)
		return;
		
		var scaleX = 75 / selection.width;
		var scaleY = 75 / selection.height;
		
		$('#preview img').css({
		width: Math.round(scaleX*img.width),
		height: Math.round(scaleY*img.height),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1), 
		});
			
	} 
	
	// Validate Tile_Crop_Form and send data to backend
	$("#tile_crop_form").validate({
		onsubmit: true,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("#crop_error").text(validator.errorList[0].message);
			}
		},
		submitHandler: function(form) {
			var tile_type = "community";
			$.post(
				"actions/crop.php",
				$('#tile_crop_form').serialize(),
				function(data){
					if (data.success == 0){
						$('#crop_error').html(data.message); 
					}else{
						$('#crop_error').empty();
						$('#tile_crop').hide();
						$('.pagination_mosaic').show();
						$('#tiles').show();
						$('#tile_upload_error').hide();
						switch (data.tile_type){
							case "S":
								tile_type = "sponsored"
								break
							case "U":
								tile_type = "uploaded"
								break
							case "C":
								tile_type = "community"
								break
						};
						$('#tile_display').append("<li class=\'"+tile_type+"\' style=\'background-image:url(images/interests/"+data.filename+");\' onmouseout=\'hideInterest($(this))\' onmouseover=\"showInterest($(this), \'"+data.tag+"\')\"></li> ");
							
						$('.tile_pic').imgAreaSelect({
							hide: true
						});
						$('#tile_upload_success').show().html(data.message);
					}
				}, "json"
			);
		},
		errorPlacement: function(error, element) {
			// Override error placement to not show error messages beside elements //
		},
		rules: {						// Adding validation rules for each input //
			assign_tag: {
				required: true,
				validtag: true,
				startsymbol: true,
				endsymbol: true,
				minlength: 2,
				maxlength: 30			
			}
		},
		messages: {						// Customized error messages for each error //
			assign_tag: {			
				required: "Please provide a tag for your tile.",
				validtag: "Tag contains invalid characters.",
				startsymbol: "Tag should not start or end with a symbol.",
				endsymbol: "Tag should not start or end with a symbol.",
				minlength: "Tag should be at least 2 characters.",
				maxlength: "Please enter no more than 30 characters for your tag."
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

jQuery.validator.addMethod("validtag", function(value, element) {
	return this.optional(element) || /^[a-zÀ-ÖØ-öø-ÿ0-9-'\s]+$/i.test(value);
}, "The name contains invalid characters.");

// Captcha Theme Settings //
var RecaptchaOptions = { 
	theme : 'clean'
};

function showInterest(obj, tag){	
	obj.addClass('tile_tag');
	obj.html(tag);
};

function hideInterest(obj){
	obj.removeClass('tile_tag');
	obj.html('');
};