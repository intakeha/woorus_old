<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<?php $externalID = $_GET['eid']; ?>
<div id="eprofile_container" class="fluid">
    <div id="eprofile">
        <div id="profile">
            <div id="external_profile_frame"></div><div id="profile_pic"><img></div>
            <div id="profile_name"><div id="profile_online_status" class="online_status float_right"></div><span></span><br />Palo Alto, CA | United States</div>
            <div id="actions">
                <a class="talk_button" href="#"></a>
                <a class="write_button" href="#"></a>
                <a class="add_button" href="#"></a>
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
						}
						if (field.first_name){	
							$('#profile_name').find('span').append(field.first_name);		
						} else { 
							$('#profile_name').find('span').append("Unknown");
						}
					} else {
						$('#wall_display').append("<li onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+field.tile_filename+"\'></li>");
					}
				});
			}, "json"
		);		
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
