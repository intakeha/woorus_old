<div id="lounge">
	<div id="lounge_area">
		<span>Welcome to the lounge</span>
		<p>We have selected several people that you might find interesting based on your mosaic wall. <br>Check out their profiles and start meeting new people from around the world!</p>
		<div id="lounge_profiles">
			<div class="lounge_match">
				<div class="lounge_info">
					<a href="#">
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
								<a href="#"><span id="name0"></span></a>
								<div id="online_status" class="lounge_online"></div> 
							</div>
						</div>
						<div class="action_buttons">
							<a class="add_button_sm" href="#"></a>
							<a class="write_button_sm" href="#"></a>
							<a class="talk_button_sm" href="#"></a>
						</div>
					</div>
				</div>
				<ul class="lounge_tiles" id="tile0"></ul>
			</div>
			<div class="lounge_match">
				<div class="lounge_info">
					<a href="#">
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
								<a href="#"><span id="name1"></span></a>
								<div id="online_status" class="lounge_online"></div> 
							</div>
						</div>
						<div class="action_buttons">
							<a class="add_button_sm" href="#"></a>
							<a class="write_button_sm" href="#"></a>
							<a class="talk_button_sm" href="#"></a>
						</div>
					</div>
				</div>
				<ul class="lounge_tiles" id="tile1"></ul>
			</div>
		</div>
	</div>
	<div class="pagination_lounge"><a id="lounge_more"></a></div>
	<form id="loungeOffset" action="actions/loungeSearch.php" method="post"><input type="hidden" name="offset" value="0" /></form>
</div>

<script type="text/javascript">
	$(document).ready(function(){	
		// Get lounge information
		loungeUpdates();
		
		function loungeUpdates(){
			$.post(
				"actions/loungeSearch.php",
				$('#loungeOffset').serialize(),
				function(data){
					// Left profile panel
					if (data.profile[0].profile_filename_large){	
						$('#photo_left').attr('src','images/users/large/'+data.profile[0].profile_filename_large);			
					} else { 
						$('#photo_left').attr('src','images/global/silhouette.png');
					}
					if (data.profile[0].first_name){	
						$('#name0').html(data.profile[0].first_name);			
					} else { 
						$('#name0').html('Unknown');
					}
					for (i=0; i<12; i++){
						$('#tile0').append('<li onmouseover="showInterest($(this), \''+data.tiles_0[i].interest_name+'\')" onmouseout="hideInterest($(this))"><img class="lounge_interestTile" src="images/interests/'+data.tiles_0[i].tile_filename+'"></li>');
					}
					
					// Right profile panel
					if (data.profile[1].profile_filename_large){	
						$('#photo_right').attr('src','images/users/large/'+data.profile[1].profile_filename_large);			
					} else { 
						$('#photo_right').attr('src','images/global/silhouette.png');
					}
					if (data.profile[1].first_name){	
						$('#name1').html(data.profile[1].first_name);			
					} else { 
						$('#name1').html('Unknown');
					}
					for (i=0; i<12; i++){
						$('#tile1').append('<li onmouseover="showInterest($(this), \''+data.tiles_1[i].interest_name+'\')" onmouseout="hideInterest($(this))"><img class="lounge_interestTile" src="images/interests/'+data.tiles_1[i].tile_filename+'"></li>');
					}
				}, "json"
			);
		}
		
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