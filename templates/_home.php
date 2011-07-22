<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<div id="iprofile">
	<div id="profile">
		<div id="profile_frame"></div><div id="profile_pic"><img src="images/global/user_pic.png"></div>
        <div id="profile_name"><div id="profile_online_status" class="online_status float_right"></div><span>Jessica</span><br />Palo Alto, CA | United States</div>
		<a id="announcement" href="canvas.php?page=mosaic">
				<div><span>Mosaic Wall</span><br>
				Customize your mosaic wall and show the world who you are! Create custom tiles  
				or leverage tiles from others to get started quickly.</div>
		</a>
    </div>
    <div id="profile_social_status">
		<div id="butterfly"></div>
		<div id="warning"></div>
    </div>
   	<div id="updates_left" class="pagination_home" style="display: none;"><a class="arrows pagination_left" href="#"></a></div>
   	<div id="updates">
    	<p>Your Woorus Activities This Week:</p>
	
	<div id="first_update"><a href="#"><p>115</p>Missed Calls</a>
		<ul>
			  <li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
		</ul>
        </div>
        <div><a href="#"><p>12</p>Added You to Contacts</a>
        	<ul>
               	<li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
            </ul>
        </div>
        <div><a href="#"><p>173</p>New Interests of Contacts</a>
        	<ul>
				<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
				<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
            </ul>
        </div>
        <div><a href="#">People interested in <p>Tennis</p></a>
        	<ul>
               	<li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
            </ul>
		</div>
    </div>
   	<div id="updates_right" class="pagination_home" style="display: none;"><a class="arrows pagination_right" href="#"></a></div>
    <div id="upload_profile_pic" style="display: none;">
    	<p>Select a photo for your profile picture:</p>
		<form id="profile_upload_form" action="actions/uploadProfilePicture.php" method="post" enctype="multipart/form-data">
            <input class="text_form" type="file" name="file" id="file" style="width: 430px;" /><br />
            <input class="buttons" id="profile_pic_upload" type="submit" name="filename" value="Upload">
            <div class="error_text" id="profile_upload_error"></div>
            <div class="success_text" id="profile_upload_success" style="display: none;"></div>
            <img id="tile_loading" style="display: none;" src="images/global/loading.gif" />
		</form>
        <div id="profile_frame"></div><div id="profile_pic"><img src="images/global/user_pic.png"></div>
    </div>
</div>

<script type="text/javascript">
	$('#profile_frame').mouseover(function() {
		$(this).css('background-position','0px -283px');
	});
	
	$('#profile_frame').mouseout(function() {
		$(this).css('background-position','0px 0px');
	});
	
	$('#profile_frame').click(function(){
		hideProfile();
		$('#upload_profile_pic').show();
	});
	
	function hideProfile(){
		$('#profile').hide();
		$('#profile_social_status').hide();
		$('#updates').hide();
	}
	
	// Upload picture file for tile crop
	$('#tile_pic_upload').click(function(){
		$("#tile_loading")
		.ajaxStart(function(){
			$('#tile_upload_error').hide();
			$('#tile_upload_success').hide();
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		
		$.ajaxFileUpload({
			url: 'actions/upload_file.php',
			secureuri: false,
            fileElementId:'file',
            dataType: 'json',
			success: function(data){
				if (data.success == 0){
					$('#tile_upload_success').hide();
					$('#tile_upload_error').show();
					$('#tile_upload_error').html(data.message); 
				} else {
					$('.pagination_mosaic').hide();
					$('#tiles').hide();
					$('#tile_crop').show();
					$('input[name=assign_tag]').val('');
					$('.tile_pic').attr('src','images/temporary/'+data.message);
					$('input[name=cropFile]').val(data.message);
				}
			}
		})
		
		return false;
	});
</script>
