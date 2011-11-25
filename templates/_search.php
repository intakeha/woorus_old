<div id="search_container" class="fluid" style="background: url(../images/ads/prius.jpg) no-repeat scroll 50% 20% transparent;">
    <div id="search">
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
            <div class="pagination_search"><div id="search_left" class="arrows pagination_left"></div></div>
            <div class="result_column center_column">
                <ul id="result_entries_left">
                </ul>
            </div>
            <div class="result_column">
                <ul id="result_entries_right">
                </ul>
            </div>
            <div class="pagination_search"><div id="search_right" class="arrows pagination_right"></div></div>
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
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}).result( function (){
			if($("#search_form").valid()) { 
				$('input[name=offset]').val(0);
				search_term = $('input[name=user_search]').val();
				$('input[name=user_search]').val(decodeHTML(search_term));
				userSearch();
			}
		});
		
		// Validate user search form
		$("#search_form").validate({
			onsubmit: true,
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					searchReset();
					$("#search_error").html(validator.errorList[0].message); 
				}
			},
			submitHandler: function(form) {
				$('input[name=offset]').val(0);
				userSearch();
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
					required: "We did not find any matches for your search. Please try another interest or meet someone in the lounge.",
					minlength: "We did not find any matches for your search. Please try another interest or meet someone in the lounge.",
					maxlength: "We did not find any matches for your search. Please try another interest or meet someone in the lounge."
				}
			}
		});
		
		// Clear error messages upon blur in the search field
		$("#user_search_field").blur(function() {
			$("#search_error").html('');
		});
		
		$("#search_right").click(function() {
			var searchTerm = $('#user_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+10;
			$('input[name=offset]').val(nextOffset);
			if($("#search_form").valid()) { 
				userSearch();
			}
		});
		
		$("#search_left").click(function() {
			var searchTerm = $('#user_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var prevOffset = parseInt(currentOffset)-10;
			$('input[name=offset]').val(prevOffset);
			if($("#search_form").valid()) { 
				userSearch();
			}
		});
		
		function userSearch(){
			$.post(
				"actions/userSearch.php",
				$('#search_form').serialize(),
				function(data){
					searchReset();
					if (data.success == 0){
						$("#search_error").html(data.message);
					} else {
						showResults();
						$.each(data, function(i, field){
							var resultEntryCSS = "result_entry";
							switch (i){
								case 0:
									var userSearchPages = Math.ceil(field.user_count/10);
									var currentOffset = $('input[name=offset]').val();
									var currentPage = (currentOffset/10)+1;
									if (currentPage < userSearchPages) {
										$("#search_right").show();
									} else {
										$("#search_right").hide();
									};
									if (currentPage > 1) {
										$("#search_left").show();
									} else {
										$("#search_left").hide();
									};
									break;
								case 1:
									resultEntryCSS = "result_entry_first";
									break;
								case 6:
									resultEntryCSS = "result_entry_first";
									break;
							};
							if (i > 0){
								if (field.profile_filename_small){	
									profilePic = "images/users/small/"+field.profile_filename_small;			
								} else { 
									profilePic = "images/global/silhouette_sm.png";
								};
								switch (field.online_status){
									case "online":
										onlineStatus = "online_status_sm";
										break;
									case "offline":
										onlineStatus = "online_status_sm offline_status_sm";
										break;
									case "away":
										onlineStatus = "online_status_sm away_status_sm";
										break;
									case "busy":
										onlineStatus = "online_status_sm busy_status_sm";
										break;
								};
								if (i < 6) {
									$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'"+profilePic+"\' /></a><div><div class=\'user_info\'><div class=\'social_status float_right\'></div><div class=\'social_status warning_status float_right\'></div><div class=\'"+onlineStatus+"\'><a href=\'#\'>"+field.first_name+"</a></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
								}else{
									$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'"+profilePic+"\' /></a><div><div class=\'user_info\'><div class=\'social_status float_right\'></div><div class=\'social_status warning_status float_right\'></div><div class=\'"+onlineStatus+"\'><a href=\'#\'>"+field.first_name+"</a></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
								};
							};
						});
					}
				}, "json"
			);
		};
		
		function searchReset(){
			$('#search_left, #search_right').hide();
			$("#search_error").html('');
			$('#result_entries_left, #result_entries_right').empty();
		};
		
		function showResults(){
			$('#search_container').css('background','');
			$('#search_slide').hide();
			$('#search').css('background','none');
			$('#search_results').show();
		};
			
	});
</script>
