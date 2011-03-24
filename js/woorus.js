// Validate forms via jQuery
$(document).ready(function(){
	
	// Validate settings form
	$("#settings_form").validate({
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
			new_email: {
				email: true
			},
			old_password: {
				rangelength: [6,20]
			},
			new_password: {
				rangelength: [6,20]
			},
			confirm_password: {
				rangelength: [6,20],
				equalTo: "#new_password"
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
			new_email: {
				email: "Please enter a valid email address."
			},
			old_password: {
				rangelength: "Your password must be between 6 and 20 characters long."
			},
			new_password: {
				rangelength: "Your password must be between 6 and 20 characters long.",
			},
			confirm_password: {
				equalTo: "Your new passwords do not match.",
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
			first_name: "first_name last_name new_email old_password new_password confirm_password gender birthday_month birthday_day birthday_year city"
		},
		errorLabelContainer: $("#settings_error")
	});	
	
});

jQuery.validator.addMethod("validname", function(value, element) {
	return this.optional(element) || /^[a-z-'\s]+$/i.test(value);
}, "The name contains invalid characters.");

jQuery.validator.addMethod("selectfield", function(value, element) {
	if(element.value == -1){return false;} else return true;
}, "Please select an option.");