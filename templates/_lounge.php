<div id="lounge_container" class="fluid">
    <div id="lounge">
        <div id="lounge_area">
            <span>Welcome to the lounge</span>
            <p>We have selected several people that you might find interesting based on your mosaic wall. <br>See if you have something in common and start meeting new people around the world!</p>
            <p style="display: none">Looks like there's no one left in the lounge. You can use the <a href="canvas.php?page=search">search page</a> to find people with specific interests.</p>
            <div id="lounge_profiles">
                <div id="lounge_col0" class="lounge_match" style="display: none;">
                    <div class="lounge_info">
                        <a class="lounge_external0" href="#">
                        <div class="lounge_photo">
                            <img id="photo_left">
                        </div></a>
                        <div>
                            <div>	
                                <div class="lounge_icons float_right">
                                    <div class="social_status float_right"></div>
                                    <div class="social_status warning_status float_right"></div>
                                </div>						
                                <div class="lounge_userInfo">
                                    <a class="lounge_external0" href="#"><span id="name0" ></span></a>
                                    <div id="lounge_onlineStatus0"></div>
                                    <div style="display: none;"><img id="profile_sm0" class="profile_sm"/></div>
                                    <div id="city0" class="city_name" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="action_buttons">
                                <div class="add_button_sm"></div>
                                <div class="write_button_sm"></div>
                                <div class="talk_button_sm"></div>
                            </div>
                        </div>
                    </div>
                    <ul class="lounge_tiles" id="tile0"></ul>
                </div>
                <div id="lounge_col1" class="lounge_match" style="display: none;">
                    <div class="lounge_info">
                        <a class="lounge_external1" href="#">
                        <div class="lounge_photo">
                            <img id="photo_right">
                        </div></a>
                        <div>
                            <div>	
                                <div class="lounge_icons float_right">
                                    <div class="social_status float_right"></div>
                                    <div class="social_status warning_status float_right"></div>
                                </div>
                                <div class="lounge_userInfo">
                                    <a class="lounge_external1" href="#"><span id="name1"></span></a>
                                    <div id="lounge_onlineStatus1"></div>
                                    <div style="display: none;"><img id="profile_sm1" class="profile_sm" /></div>
                                    <div id="city1" class="city_name" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="action_buttons">
                                <div class="add_button_sm"></div>
                                <div class="write_button_sm"></div>
                                <div class="talk_button_sm"></div>
                            </div>
                        </div>
                    </div>
                    <ul class="lounge_tiles" id="tile1"></ul>
                </div>
            </div>
        </div>
        <div id="lounge_right" class="arrows pagination_right"></div>
        <form id="loungeOffset" action="actions/loungeSearch.php" method="post"><input type="hidden" name="offset" value="0" /></form>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){	
		// Get lounge information
		$('input[name=offset]').val('0');
		loungeUpdates();
		
		// Bind more pagination with lounge results
		$("#lounge_right").click(function() {
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+2;
			$('input[name=offset]').val(nextOffset);
			loungeUpdates();
		});
		
		function loungeUpdates(){
			$.post(
				"actions/loungeSearch.php",
				$('#loungeOffset').serialize(),
				function(data){
					$('#tile0, #tile1').empty();
					resultCount = data.result_count;

					if (resultCount == 0){
							$('#lounge_right, #lounge_col0, #lounge_col1').hide();
							$('#lounge_area').children('p:eq(0)').hide();
							$('#lounge_area').children('p:eq(1)').show();
					};
					
					if (resultCount >= 1){
						// Populate left profile panel
						$('#lounge_col1').hide();
						if (data.profile[0].user_id){
							$('#lounge_col0').show();
							$('#lounge_col0').find('.lounge_info').children('div:eq(0)').attr("id",data.profile[0].user_id);
							$('.lounge_external0').attr('href','canvas.php?page=external&eid='+data.profile[0].user_id);			
						};
						if (data.profile[0].profile_filename_large){	
							$('#photo_left').attr('src','images/users/large/'+data.profile[0].profile_filename_large);			
						} else { 
							$('#photo_left').attr('src','images/global/silhouette.png');
						};
						if (data.profile[0].profile_filename_small){	
							$('#profile_sm0').attr('src','images/users/small/'+data.profile[0].profile_filename_small);
						} else { 
							$('#profile_sm0').attr('src','images/global/silhouette_sm.png');
						};
						if (data.profile[0].first_name){
							$('#name0').html(data.profile[0].first_name);			
						} else { 
							$('#name0').text('Unknown');
						};
						if (data.profile[0].city_name){	
							$('#city0').html(data.profile[0].city_name);
						};
						if (data.profile[0].online_status){
							switch (data.profile[0].online_status){
								case "online":
									statusClass = "online_status lounge_online"
									break
								case "offline":
									statusClass = "online_status offline_status lounge_online"
									break
								case "away":
									statusClass = "online_status away_status lounge_online"
									break
								case "busy":
									statusClass = "online_status busy_status lounge_online"
									break
							};
							$('#lounge_onlineStatus0').addClass(statusClass);			
						} else { 
							$('#name0').html('Unknown');
						};
						for (i=0; i<data.tiles_count0; i++){
							$('#tile0').append('<li onmouseover=\'showInterest($(this), \"'+data.tiles_0[i].interest_name+'\")\' onmouseout="hideInterest($(this))"><img class="lounge_interestTile" src="images/interests/'+data.tiles_0[i].tile_filename+'"></li>');
						};
					};
					
					if (resultCount == 2){
						// Populate right profile panel
						if (data.profile[1].user_id){
							$('#lounge_col1').show();
							$('#lounge_col1').find('.lounge_info').children('div:eq(0)').attr("id",data.profile[1].user_id);
							$('.lounge_external1').attr('href','canvas.php?page=external&eid='+data.profile[1].user_id);			
						};
						if (data.profile[1].profile_filename_large){	
							$('#photo_right').attr('src','images/users/large/'+data.profile[1].profile_filename_large);			
						} else { 
							$('#photo_right').attr('src','images/global/silhouette.png');
						};
						if (data.profile[1].profile_filename_small){	
							$('#profile_sm1').attr('src','images/users/small/'+data.profile[1].profile_filename_small);
						} else { 
							$('#profile_sm1').attr('src','images/global/silhouette_sm.png');
						};
						if (data.profile[1].first_name){
							$('#name1').html(data.profile[1].first_name);		
						} else { 
							$('#name1').text('Unknown');
						};
						if (data.profile[1].city_name){	
							$('#city1').html(data.profile[1].city_name);
						};
						if (data.profile[1].online_status){
							switch (data.profile[1].online_status){
								case "online":
									statusClass = "online_status lounge_online"
									break
								case "offline":
									statusClass = "online_status offline_status lounge_online"
									break
								case "away":
									statusClass = "online_status away_status lounge_online"
									break
								case "busy":
									statusClass = "online_status busy_status lounge_online"
									break
							};
							$('#lounge_onlineStatus1').addClass(statusClass);			
						} else { 
							$('#name0').html('Unknown');
						};
						for (i=0; i<data.tiles_count1; i++){
							$('#tile1').append('<li onmouseover=\'showInterest($(this), \"'+data.tiles_1[i].interest_name+'\")\' onmouseout="hideInterest($(this))"><img class="lounge_interestTile" src="images/interests/'+data.tiles_1[i].tile_filename+'"></li>');
						};
					};
				}, "json"
			);
		}
		
	});
	
	$('div.write_button_sm').live('click', function() {
		clearModalMessages();

		contactID = $(this).parents('div:eq(1)').attr('id');
		profilePic = $(this).parents('div:eq(2)').find('img.profile_sm').attr('src');
		firstName = $(this).parents('div:eq(1)').find('div.lounge_userInfo span').text();
		city = $(this).parents('div:eq(2)').find('div.city_name').text();

		$('#modal_write').find('img').attr('src',profilePic);
		$('#modal_write_header').find('span').text(firstName);
		$('#modal_write_header').find('p').text(city);
		$('input[name=user_id_mailee]').val(contactID);
		modal('#modal_write','400','200');
	});
	
	$('div.add_button_sm').live('click', function() {
		contactID = $(this).parents('div:eq(1)').attr('id');
		firstName = $(this).parents('div:eq(1)').find('div.lounge_userInfo span').text();
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
	
	function showInterest(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before('<div class="lounge_transparent_tag">'+tag+'</div>');
	};
	
	function hideInterest(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('div').remove();
	};
	
</script>