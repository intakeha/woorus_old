<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<?php $externalID = $_GET['eid']; ?>
<div id="eprofile_container" class="fluid">
    <div id="eprofile">
        <div id="profile">
            <div id="external_profile_frame"></div><div id="profile_pic"><img></div>
            <div id="profile_name"><div id="eprofile_online_status" class="online_status float_right"></div><span></span><br /></div>
            <div id="actions">
                <div class="talk_button"></div>
                <div class="write_button"></div>
                <div class="add_button"></div>
            </div>
        </div>
        <div id="profile_social_status">
            <div id="butterfly"></div>
            <div id="warning"></div>
        </div>
        <div id="external_wall">
            <ul id="wall_display"></ul>
        
        </div>
    </div>
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$.post(
			"actions/showExternalProfile.php",
			{externalID: <?php echo $externalID ?>},
			function(data){
				$.each(data, function(i, field){
					if (i == 0){				
						if (field.profile_filename_large){	
							$('#profile_pic img').attr('src','images/users/large/'+field.profile_filename_large);			
						} else { 
							$('#profile_pic img').attr('src','images/global/silhouette.png');
						};
						if (field.profile_filename_small){	
							$('#modal_write').find('img').attr('src','images/users/small/'+field.profile_filename_small);			
						} else { 
							$('#modal_write').find('img').attr('src','images/global/silhouette_sm.png');
						};
						if (field.first_name){	
							firstName = decodeHTML(field.first_name);
							$('#profile_name').find('span').text(firstName);
							$('#modal_write_header').find('span').text(firstName);
						} else { 
							$('#profile_name').find('span').text("Unknown");
							$('#modal_write_header').find('span').text("Unknown");
						};
						if (field.city_name){
							city = decodeHTML(field.city_name);
							$('#profile_name').find('br').after(city);
							$('#modal_write_header').find('br').after(city)
						};		
						if (field.online_status){
							switch (field.online_status){
								case "offline":
									$('#eprofile_online_status').addClass("offline_status");
									break
								case "away":
									$('#eprofile_online_status').addClass("away_status");
									break
								case "busy":
									$('#eprofile_online_status').addClass("busy_status");
									break
							};
						};			
					} else {
						$('#wall_display').append("<li onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+field.tile_filename+"\'></li>");
					}
				});
			}, "json"
		);
		
		$('div.write_button').click(function() {
			clearModalMessages();
			$('input[name=user_id_mailee]').val('<?php echo $externalID ?>');
			modal('#modal_write','400','100');
		});
		
		$('div.add_button').click(function() {
			$.post(
				"actions/addContact.php",
				{user_id_contactee: <?php echo $externalID ?>},
				function(data){
					firstName = $('#profile_name').find('span').text();
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
	});
		
	function showInterest(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before("<div class='transparent_tag'>"+tag+"</div>");
	};
	
	function hideInterest(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('div').remove();
	};
	
</script>
