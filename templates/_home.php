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
    <div id="upload_profile_area" style="display: none;">
    	<p>Select a photo for your profile picture:</p>
		<form id="profile_upload_form" action="actions/uploadProfilePicture.php" method="post" enctype="multipart/form-data">
            <input class="text_form" type="file" name="file" id="file" style="width: 430px;" /><br />
            <input class="buttons" id="profile_pic_upload" type="submit" name="filename" value="Upload">
            <div class="error_text" id="profile_upload_error"></div>
            <div class="success_text" id="profile_upload_success" style="display: none;"></div>
            <img id="tile_loading" style="display: none;" src="images/global/loading.gif" />
		</form>
    </div>
    <div id="crop_profile_pic" style="display: none;">
    	<div id="profile_crop_instruction">Click and drag on the image below to create your profile picture.</div>
        <div id="profile_original_photo">
            <img class="profile_pic" />
        </div>
        <div id="profile_preview_area">
        	<font>Profile Photo Preview</font>
            <div id="profile_preview">
                <img />
            </div>
        
        </div>
        <div id="profile_save">
            <form id="profile_crop_form" action="actions/profileCrop.php" method="POST">       
                <input type="hidden" name="x1" value="" />
                <input type="hidden" name="y1" value="" />
                <input type="hidden" name="x2" value="" />
                <input type="hidden" name="y2" value="" />
                <input type="hidden" name="w" value="" />
                <input type="hidden" name="h" value="" />
                <input type="hidden" name="cropFile" value="" />
                <br />
                <input type="submit" id="crop_save" class="buttons save" name="submit" value="Save" /><input class="buttons cancel" type="button" name="cancel" value="Cancel" onclick="location.href='canvas.php?page=home'"/>
            </form>
            <div class="error_text" id="profile_crop_error"></div>
        </div>
    </div>
    <div id="crop_profile_thumbnail" style="display: none;">
    	<div id="profile_crop_instruction">Now create your thumbnail picture.</div>
        <div id="profile_post_crop">
            <img class="post_crop_pic" />
        </div>
        <div id="profile_preview_area">
        	<font>Profile Thumbnail Preview</font>
            <div id="profile_thumbnail_preview">
                <img />
            </div>
        </div>
        <div id="profile_save">
            <form id="profile_thumbnail_form" action="actions/profileCropThumbnail.php" method="POST">       
                <input type="hidden" name="x1" value="" />
                <input type="hidden" name="y1" value="" />
                <input type="hidden" name="x2" value="" />
                <input type="hidden" name="y2" value="" />
                <input type="hidden" name="w" value="" />
                <input type="hidden" name="h" value="" />
                <input type="hidden" name="cropFile" value="" />
                <br />
                <input type="submit" id="crop_save" class="buttons save" name="submit" value="Save" /><input class="buttons cancel" type="button" name="cancel" value="Cancel" onclick="location.href='canvas.php?page=home'"/>
            </form>
            <div class="error_text" id="profile_crop_error"></div>
        </div>
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
		$('#updates').hide();
		$('#upload_profile_area').show();
	});
	
	function hideProfile(){
		$('#profile').hide();
		$('#profile_social_status').hide();
		$('#updates').hide();
	}
	
	// Upload picture file for profile crop
	$('#profile_pic_upload').click(function(){
		$("#tile_loading")
		.ajaxStart(function(){
			$('#profile_upload_error').hide();
			$('#profile_upload_success').hide();
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		
		$.ajaxFileUpload({
			url: 'actions/uploadProfilePicture.php',
			secureuri: false,
            fileElementId:'file',
            dataType: 'json',
			success: function(data){
				if (data.success == 0){
					$('#profile_upload_success').hide();
					$('#profile_upload_error').show();
					$('#profile_upload_error').html(data.message); 
				} else {
					hideProfile();
					$('#profile_crop_error').html('');
					$('#upload_profile_area').hide(); 
					$('#crop_profile_pic').show();
					$('.profile_pic').attr('src','images/temporary/'+data.message);
					$('#profile_preview img').attr('src','images/temporary/'+data.message);
					$('input[name=cropFile]').val(data.message);
				}
			}
		})
		
		return false;
	});
	
	// Call imgAreaSelect to crop picture and associated coordinates
	$('.profile_pic').imgAreaSelect({
        handles: true,
		aspectRatio: "3:2",
		minWidth: 300,
		minHeight: 200,
		onSelectChange: previewProfile,
		onSelectEnd: function (img, selection) {				
            $('input[name=x1]').val(selection.x1);
            $('input[name=y1]').val(selection.y1);
            $('input[name=x2]').val(selection.x2);
            $('input[name=y2]').val(selection.y2); 
	        $('input[name=w]').val(selection.width);
            $('input[name=h]').val(selection.height);
        }		
    });

	// Function used by imgAreaSelect to preview thumbnail	
	function previewProfile(img, selection) {
		if (!selection.width || !selection.height)
		return;
		
		var scaleX = 300 / selection.width;
		var scaleY = 200 / selection.height;
		
		$('#profile_preview img').css({
		width: Math.round(scaleX*img.width),
		height: Math.round(scaleY*img.height),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1), 
		});
			
	} 
	
	$("#profile_crop_form").submit(function(event) {
		event.preventDefault(); 
		
		$.post(
				"actions/profileCrop.php",
				$('#profile_crop_form').serialize(),
				function(data){
					if (data.success == 0){
						$('#profile_crop_error').html(data.message); 
					}else{
						$('.post_crop_pic').attr('src','images/temporary/'+data.message);
						$('#profile_thumbnail_preview img').attr('src','images/temporary/'+data.message);
						$('#crop_profile_pic').hide();
						$('#crop_profile_thumbnail').show();
					}
				}, "json"
			);
	});
	
</script>
