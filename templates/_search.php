<div id="search">
	<div id="search_slide"></div>
    <div>
        <form id="search_form" action="../actions/userSearch.php" method="POST">
                <input type="text" class="text_form ac_input" id="user_search_field" name="user_search" maxlength="60">
                <input type="hidden" name="offset" value="0" />
                <input class="buttons" id="user_search_submit" type="submit" name="user_search_submit" value="Search">
        </form>
        <div id="search_error" style="width: 400px; margin-left: 360px;  font-weight: bold;">
            Your search did not return any results. Please try another interest.
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
	
		// Activate autocomplete for tile search field
		$("#user_search_field").autocomplete("actions/interestList.php",{
			dataType: 'json',
			parse: function(data) {
				return $.map(data, function(item) {
					return {
						data: item,
						value: item.interest_name,
						result: item.interest_name
					}
				}); 
			}, 
			formatItem: function(item) {
				return item.interest_name;
			},
			formatMatch: function(item) {
				return item.interest_name;
			},
			formatResult: function(item) {
				return item.interest_name;
			},
			autoFill: true,
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}); 
		
		// Validate user search form
		$("#search_form").validate({
			onsubmit: true,
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					$("#search_error").show().text(validator.errorList[0].message); 
				}
			},
			submitHandler: function(form) {
				$('#search_slide').slideUp('fast');
				$.post(
					"actions/userSearch.php",
					$('#search_form').serialize(),
					function(data){
						$.each(data, function(i, field){
							switch (field.tile_type){
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
						alert (data.interest_name);
						});
					}, "json"
				);
			},
			errorPlacement: function(error, element) {
				// Override error placement to not show error messages beside elements //
			},
			rules: {
				tile_search: {
					required: true,
					minlength: 2,
					maxlength: 60	
				}
			},
			messages: {
				tile_search: {			
					required: "Your search did not return any results. Please try another interest.",
					minlength: "Your search did not return any results. Please try another interest.",
					maxlength: "Your search did not return any results. Please try another interest."
				}
			}
		});
		
	});
</script>
