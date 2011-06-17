<div id="search" style="background: url(../images/ads/prius.jpg) no-repeat;">
	<div id="search_slide"></div>
    <div>
        <form id="search_form" action="../actions/userSearch.php" method="POST">
                <input type="text" class="text_form ac_input" id="user_search_field" name="user_search" maxlength="60">
                <input type="hidden" name="offset" value="0" />
                <input class="buttons" id="user_search_submit" type="submit" name="user_search_submit" value="Search">
        </form>
        <div id="search_error">
        </div>
    </div>
    <div id="search_results" style="display: none;">
    	<div class="pagination_search"><a id="search_left" class="arrows pagination_left"></a></div>
    	<div class="result_column center_column">
        	<ul>
				<li class="result_entry">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                            	<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
                </li>     
				<li class="result_entry">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                           		<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
                </li>
				<li class="result_entry">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                           		<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
                </li>   
				<li class="result_entry">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                           		<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
                </li>   
				<li class="result_entry_last">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                           		<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
                </li>   

            </ul>
        </div>
        <div class="result_column">
        	<ul>
				<li class="result_entry">
                	<div class="list_users">
                    	 <a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
                         <div>
                         	<div class="user_info">
                            	<div class="online_status"><a href="#">Melanie</a></div> 
                                <div class="social_status float_right"></div>
                            </div>
                            <div class="action_buttons">
                           		<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
                            	<a class="add_button_sm" href="#"></a>
                                <a class="write_button_sm" href="#"></a>
                                <a class="talk_button_sm" href="#"></a>
                            </div>
                         </div>
                    </div>
					
                </li>
            </ul>
        </div>
        <div class="pagination_search"><a id="search_right" class="arrows pagination_right"></a></div>
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
					$("#search_error").html(validator.errorList[0].message); 
				}
			},
			submitHandler: function(form) {
				$('#search_slide').hide();
				$('#search').css('background','none');
				$('#search_results').show();
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
						});
					}, "json"
				);
			},
			errorPlacement: function(error, element) {
				// Override error placement to not show error messages beside elements //
			},
			rules: {
				user_search: {
					required: true,
					minlength: 2,
					maxlength: 60	
				}
			},
			messages: {
				user_search: {			
					required: "Your search did not return any results. Please try another interest.",
					minlength: "Your search did not return any results. Please try another interest.",
					maxlength: "Your search did not return any results. Please try another interest."
				}
			}
		});
		
		$("#user_search_field").blur(function() {
			$("#search_error").html('');
		});
		
	});
</script>
