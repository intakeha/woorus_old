// Validate forms via jQuery
$(document).ready(function(){
	// Validate login form
	$("#login_form").validate({
		rules: {						// Adding validation rules for each input
			email: "email",
			password: "required"			
		},
		messages: {						// Customized error messages for each error
			email: "Please enter a valid email address.",
			password: "Please enter a password."	
		},
		groups: {						// Group form fields to get on error message
			email: "email password"
		},
		errorLabelContainer: $("#auth_error")	// Assign error output div
	});	
	
	// Validate login form
	$("#recover_form").validate({
		rules: {
			email: "email"
		},
		messages: {
			email: "Please enter a valid email address."
		},
		errorLabelContainer: $("#auth_error")
	});	
	
	// Validate registration form
	$("#registration_form").validate({
		rules: {
			first_name: {
				required: true,
				validname: true,
				minlength: 2
			},
			last_name: {
				required: true,
				validname: true,
				minlength: 2			
			},
			email: {
				required: true,
				email: true
			},
			confirm_email: {
				required: true,
				equalTo: "#email"
			},
			password: {
				required: true,
				rangelength: [6,20]
			},
			gender: {
				selectfield: true
			},
			birthday_month: {
				selectfield: true
			},
			birthday_day: {
				selectfield: true
			},
			birthday_year: {
				selectfield: true
			},
			city: {
				required: true
			}
		},
		messages: {
			first_name: {
				required: "Please provide your first name.",
				validname: "First name contains invalid characters.",
				minlength: "Please provide your real first name."
			},
			last_name: {
				required: "Please fill in your last name.",
				validname: "Last name contains invalid characters.",
				minlength: "Please provide your real last name."			
			},
			email: {
				required: "Please fill in your email address.",
				email: "Please enter a valid email address."
			},
			confirm_email: {
				required: "Please fill in your email address.",
				equalTo: "Your emails do not match."
			},
			password: {
				required: "Please fill in your password.",
				rangelength: "Your password must be between 6 and 20 characters long."
			},
			gender: {
				selectfield: "Please select your gender."
			},
			birthday_month: {
				selectfield: "Please select birthday month." 
			},
			birthday_day: {
				selectfield: "Please select birthday date."
			},
			birthday_year: {
				selectfield: "Please select birthday year."
			},
			city: {
				required: "Please fill in your city."
			}
		},
		groups: {						// Group form fields to get on error message
			first_name: "first_name last_name email confirm_email password gender birthday_month birthday_day birthday_year city"
		},
		errorLabelContainer: $("#registration_error")
	});	
	
});

jQuery.validator.addMethod("validname", function(value, element) {
	return this.optional(element) || /^[a-z-'\s]+$/i.test(value);
}, "The name contains invalid characters.");

jQuery.validator.addMethod("selectfield", function(value, element) {
	if(element.value == -1){return false;} else return true;
}, "Please select an option.");