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
									$('#result_entries_left').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'canvas.php?page=external&eid="+field.user_id+"\'><img src=\'"+profilePic+"\' /></a><div id="+field.user_id+"><div class=\'user_info\'><div class=\'social_status float_right\'></div><div class=\'social_status warning_status float_right\'></div><div class=\'"+onlineStatus+"\'><a href=\'canvas.php?page=external&eid="+field.user_id+"\'>"+field.first_name+"</a><div style=\'display: none;\'>"+field.city_name+"</div></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'canvas.php?page=external&eid="+field.user_id+"\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><div class=\'add_button_sm\'></div> <div class=\'write_button_sm\'></div><div class=\'talk_button_sm\'></div></div></div></div></li>");
								}else{
									$('#result_entries_right').append("<li class=\'"+resultEntryCSS+"\'> <div class=\'list_users\'><a class=\'search_profile\' href=\'canvas.php?page=external&eid="+field.user_id+"\'><img src=\'"+profilePic+"\' /></a><div id="+field.user_id+"><div class=\'user_info\'><div class=\'social_status float_right\'></div><div class=\'social_status warning_status float_right\'></div><div class=\'"+onlineStatus+"\'><a href=\'canvas.php?page=external&eid="+field.user_id+"\'>"+field.first_name+"</a><div style=\'display: none;\'>"+field.city_name+"</div></div></div><div class=\'action_buttons\'><a class=\'search_interest\' href=\'canvas.php?page=external&eid="+field.user_id+"\'><img class=\'search_interestTile\' src=\'images/interests/"+field.tile_filename+"\' /></a><div class=\'add_button_sm\' title="+field.user_id+"></div> <div class=\'write_button_sm\'></div><div class=\'talk_button_sm\'></div></div></div></div></li>");
								};
							};
						});
					}
				}, "json"
			);
		};

		$('div.write_button_sm').live('click', function() {
			clearModalMessages();

			contactID = $(this).parents('div:eq(1)').attr('id');
			profilePic = $(this).parents('div:eq(3)').find('img').attr('src');
			firstName = $(this).parents('div:eq(1)').find('div.online_status_sm a').text();
			city = $(this).parents('div:eq(1)').find('div.online_status_sm div').text();

			$('#modal_write').find('img').attr('src',profilePic);
			$('#modal_write_header').find('span').text(firstName);
			$('#modal_write_header').find('br').after(city);
			$('input[name=user_id_mailee]').val(contactID);
			modal('#modal_write','400','100');
		});

		$('div.add_button_sm').live('click', function() {
			contactID = $(this).parents('div:eq(1)').attr('id');
			firstName = $(this).parents('div:eq(1)').find('div.online_status_sm a').text();
			$.post(
				"actions/addContact.php",
				{user_id_contactee: contactID},
				function(data){
					if (data.success == 0){
						$('#add_message').html('You\'ve already added <span>'+firstName+'</span> to your contact list.');
					};
					if (data.success == 1){
						$('#add_message').html('<span>'+firstName+'</span> has been added to your contact list.');
					};
					modal('#modal_add','300','200');
					return false;					
				}, "json"
			);
		});
		
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
