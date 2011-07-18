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
        	<ul id="result_entries_left">
            </ul>
        </div>
        <div class="result_column">
        	<ul id="result_entries_right">
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
		}).result( function (){
			if($("#search_form").valid()) { 
				$('input[name=offset]').val(0);
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
										var lastEntry = field.user_count;
										var userSearchPages = Math.ceil(field.user_count/10);
										if (userSearchPages > 1){
											$('#search_right').show();
										}
										break
									case 1:
										resultEntryCSS = "result_entry_first"
										break
									case 6:
										resultEntryCSS = "result_entry_first"
										break										
								};
								if (i > 0){
									if (i < 6) {
										$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}else{
										$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}
								}									
							});
						}
					}, "json"
				);
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
										var lastEntry = field.user_count;
										var userSearchPages = Math.ceil(field.user_count/10);
										if (userSearchPages > 1){
											$('#search_right').show();
										}
										break
									case 1:
										resultEntryCSS = "result_entry_first"
										break
									case 6:
										resultEntryCSS = "result_entry_first"
										break
								};
								if (i > 0){
									if (i < 6) {
										$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}else{
										$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}
								}
							});
						}
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
					required: "We found no matches for your interest. Please search by a new interest or meet someone in the lounge.",
					minlength: "We found no matches for your interest. Please search by a new interest or meet someone in the lounge.",
					maxlength: "We found no matches for your interest. Please search by a new interest or meet someone in the lounge."
				}
			}
		});
		
		// Clear error messages upon blur on search field
		$("#user_search_field").blur(function() {
			$("#search_error").html('');
		});
		
		$("#search_right").click(function() {
			var searchTerm = $('#user_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+10;
			$('input[name=offset]').val(nextOffset);
			if($("#search_form").valid()) { 
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
										var lastEntry = field.user_count;
										var userSearchPages = Math.ceil(field.user_count/10);
										var offsetPage = ((nextOffset)/10)+1;
										if (offsetPage == userSearchPages){
											$('#search_right').hide();
										}
										if (nextOffset > 0){
											$('#search_left').show();
										}
										break
									case 1:
										resultEntryCSS = "result_entry_first"
										break
									case 6:
										resultEntryCSS = "result_entry_first"
										break
								};
								if (i > 0){
									if (i < 6) {
										$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'feed_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}else{
										$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'feed_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}
								}
							});
						}
					}, "json"
				);
			}
		});
		
		$("#search_left").click(function() {
			var searchTerm = $('#user_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var prevOffset = parseInt(currentOffset)-10;
			$('input[name=offset]').val(prevOffset);
			if($("#search_form").valid()) { 
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
										var lastEntry = field.user_count;
										var userSearchPages = Math.ceil(field.user_count/10);
										var offsetPage = ((prevOffset)/10)+1;
										if (offsetPage < userSearchPages){
											$('#search_right').show();
										}
										if (prevOffset == 0){
											$('#search_left').hide();
										}
										break
									case 1:
										resultEntryCSS = "result_entry_first"
										break
									case 6:
										resultEntryCSS = "result_entry_first"
										break
								};
								if (i > 0){
									if (i < 6) {
										$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}else{
										$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'#\'><img src=\'images/users/james.png\' /></a><div><div class=\'user_info\'><div class=\'online_status\'><a href=\'#\'>"+field.first_name+"</a></div> <div class=\'social_status float_right\'></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'#\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><a class=\'add_button_sm\' href=\'#\'></a> <a class=\'write_button_sm\' href=\'#\'></a><a class=\'talk_button_sm\' href=\'#\'></a></div></div></div></li>");
									}
								}
							});
						}
					}, "json"
				);
			}
		});
		
		function searchReset(){
			$('#search_left').hide();
			$('#search_right').hide();
			$("#search_error").html('');
			$('#result_entries_left').empty();
			$('#result_entries_right').empty();
		}
		
		function showResults(){
			$('#search_slide').hide();
			$('#search').css('background','none');
			$('#search_results').show();
		}
			
	});
</script>
