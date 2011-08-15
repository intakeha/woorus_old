<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<div id="iprofile">
	<div id="profile">
		<div id="profile_frame"></div><div id="profile_pic"><img></div>
        <div id="profile_name"><div id="profile_online_status" class="online_status float_right"></div><span></span><br />Palo Alto, CA | United States</div>
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
   	<div id="updates_left" class="pagination_home" style="display: none;"><a class="arrows pagination_left" style="display: none;"></a></div>
   	<div id="updates">
    	<p>Your Woorus Activities This Week:</p>	
		<div id="first_update"><a id="anchor_missed_calls" class="updates_anchor"><p></p></a>
            <ul id="list_missed_calls">
            </ul>
        </div>
        <div><a id="anchor_contacts" class="updates_anchor"><p></p>Added You to Contacts</a>
        	<ul id="list_contacts">
            </ul>
        </div>
        <div><a id="anchor_contact_interests" class="updates_anchor"><p></p></a>
        	<ul id="list_contact_interests">
            </ul>
        </div>
        <div id="shared_interest"><a id="anchor_interests" class="updates_anchor">People interested in <p></p></a>
        	<ul id="list_interests">
            </ul>
		</div>
    </div>
   	<div id="updates_missed_calls" class="updates_results" style="display: none;">
    	<div class="dashboard_view">back</div><div id="title_missed_calls"></div>
        <div class="updates_results_list">
            <ul id="show_missed_calls">
            </ul>
        </div>
    </div>
    <div id="updates_contacts" class="updates_results" style="display: none;">
	    <div class="dashboard_view">back</div><div id="title_contacts"></div>
        <div class="updates_results_list">
            <ul id="show_contacts">
            </ul>
        </div>
    </div>
    <div id="updates_contact_interests" class="updates_results" style="display: none;">
    	<div class="dashboard_view">back</div><div id="title_contact_interests"></div>
		<div class="updates_results_list">
            <ul id="show_contact_interests">
            </ul>
        </div>
    </div>
    <div id="updates_interests" class="updates_results" style="display: none;">
    	<div class="dashboard_view">back</div><div id="title_interests"></div>
        <div class="updates_results_list">
            <ul id="show_interests">
            </ul>
        </div>
    </div>
   	<div id="updates_right" class="pagination_home" style="display: none;"><a class="arrows pagination_right" style="display: none;"></a></div>
    <input type="hidden" name="offset" value="0" />
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
            <div class="error_text profile_crop_error"></div>
        </div>
    </div>
    <div id="crop_profile_thumbnail" style="display: none;">
    	<div id="profile_crop_instruction">Now click and drag again to create your profile thumbnail.</div>
        <div id="profile_post_crop">
            <img class="post_crop_pic" />
        </div>
        <div id="profile_thumbnail_preview_area">
        	<font>Profile Thumbnail</font>
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
            <div class="error_text profile_crop_error"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$.getJSON("actions/internalProfile.php",function(result){
			if (result.profile_filename_large){	
				$('#profile_pic img').attr('src','images/users/large/'+result.profile_filename_large);			
			} else { 
				$('#profile_pic img').attr('src','images/global/silhouette.png');
			}
			if (result.first_name){	
				$('#profile_name').find('span').append(result.first_name);		
			} else { 
				$('#profile_name').find('span').append("Unknown");
			}
		});
		
		$.getJSON("actions/showFeed.php",function(result){
			var i=1, tileCount=0;
			$('#anchor_missed_calls').find('p').append(result.call_count);
			if (result.call_count == 0) {
				$('#list_missed_calls').append('<h1>Yay! No missed calls.</h1>')
			} else {
				var missedCallPages = Math.ceil(result.call_count/20);
				$('#anchor_missed_calls').click(function(){
					$('#updates').hide();
					showPagination();
					$('#updates_missed_calls').show();
					$('#updates_right').find('a').attr('onclick','alert("Hello Missed Calls")'); // Testing multiple assignments
				});
			}
			if (result.call_count > 5) {tileCount=5} else {tileCount=result.call_count};
			if (result.call_count == 1) {
				$('#anchor_missed_calls').append('Missed Call');
				$('#title_missed_calls').append('You have <span>'+result.call_count+' missed call</span> this week');	
				
			} else {
				$('#anchor_missed_calls').append('Missed Calls');
				$('#title_missed_calls').append('You have <span>'+result.call_count+' missed calls</span> this week');	
			};
			for (i=1;i<=tileCount;i++) {
				if (result.missed_calls[i].profile_filename_small)
				{source = "images/users/small/"+result.missed_calls[i].profile_filename_small}
				else {
					source = "images/global/silhouette_sm.png";}
				$('#list_missed_calls').append('<li onmouseover=\"showTransparentUpdate($(this), \''+result.missed_calls[i].first_name+'\')\" onmouseout="hideTransparentUpdate($(this))"><img src=\"'+source+'\"/></li>');
			};
			$('#anchor_contacts').find('p').append(result.new_contacts_count);
			if (result.new_contacts_count == 0) {
				$('#list_contacts').append('<h1>Start chatting to get others to add you to their contact list.</h1>')
			} else {
				$('#anchor_contacts').click(function(){
					$('#updates').hide();
					showPagination();
					$('#updates_contacts').show();
				});
			}
			if (result.new_contacts_count > 5) {tileCount=5} else {tileCount=result.new_contacts_count};
			if (result.new_contacts_count == 1) {
				$('#title_contacts').append('<span>'+result.new_contacts_count+' person</span> added you to his/her contact list this week');
			} else {
				$('#title_contacts').append('<span>'+result.new_contacts_count+' people</span> added you to their contact list this week');	
			};			
			for (i=1;i<=tileCount;i++) {
				if (result.new_contacts[i].profile_filename_small)
				{source = "images/users/small/"+result.new_contacts[i].profile_filename_small}
				else {
					source = "images/global/silhouette_sm.png";}
				$('#list_contacts').append('<li onmouseover=\"showTransparentUpdate($(this), \''+result.new_contacts[i].first_name+'\')\" onmouseout="hideTransparentUpdate($(this))"><img src=\"'+source+'\"/></li>');
			};
			$('#anchor_contact_interests').find('p').append(result.interest_count);
			if (result.interest_count == 0) {
				$('#list_contact_interests').append('<h1>When your contacts add new interests, we\'ll keep you posted!</h1>')
			} else {
				$('#anchor_contact_interests').click(function(){
					$('#updates').hide();
					$('#updates_contact_interests').show();
				});
			}
			if (result.interest_count > 5) {tileCount=5} else {tileCount=result.interest_count};
			if (result.interest_count == 1) {
				$('#anchor_contact_interests').append('New Interest of Contacts');
				$('#title_contact_interests').append('Your contacts added <span>'+result.interest_count+' new interest</span> this week');
			} else {
				$('#anchor_contact_interests').append('New Interests of Contacts')
				$('#title_contact_interests').append('Your contacts added <span>'+result.interest_count+' new interests</span> this week');
			};
			for (i=1;i<=tileCount;i++) {
				if (result.new_interests[i].tile_filename)
				{source = "images/interests/"+result.new_interests[i].tile_filename}
				else {
					source = "images/global/silhouette_sm.png";}
				$('#list_contact_interests').append('<li onmouseover=\"showTransparentUpdate($(this), \''+result.new_interests[i].interest_name+'\')\" onmouseout="hideTransparentUpdate($(this))"><img src=\"'+source+'\"/></li>');
			};
			if (result.common_interests_count == 0) {
				$('#shared_interest').html('<h1>We\'ll continue to search for people who share your interests.</h1>')
			} else {
				$('#anchor_interests').find('p').append(result.interest_chosen.interest_name);
				$('#anchor_interests').click(function(){
					$('#updates').hide();
					$('#updates_interests').show();
				});
			}		
			if (result.common_interests_count > 5) {tileCount=5} else {tileCount=result.common_interests_count};
			if (result.common_interests_count != 0){
				if (result.common_interests_count == 1) {
					$('#title_interests').append('This person is interested in <span>'+result.interest_chosen.interest_name+'</span>');
				} else {
					$('#title_interests').append('The following people are interested in <span>'+result.interest_chosen.interest_name+'</span>');	
				};
			};
			for (i=1;i<=tileCount;i++) {
				if (result.common_interests[i].profile_filename_small)
				{source = "images/users/small/"+result.common_interests[i].profile_filename_small}
				else {
					source = "images/global/silhouette_sm.png";}
				$('#list_interests').append('<li onmouseover=\"showTransparentUpdate($(this), \''+result.common_interests[i].first_name+'\')\" onmouseout="hideTransparentUpdate($(this))"><img src=\"'+source+'\"/></li>');
			};
		});
	
		$.post("actions/showMissedCalls.php", {offset:0},
			function(data){
				var currentOffset = $('input[name=offset]').val();
				$.each(data, function(i, field){
					if (i == 0){
						var missedCallPages = Math.ceil(field.missed_calls_count/20);
						var offsetPage = (currentOffset/20)+1;
						alert("missedCallPages: "+missedCallPages);
						alert("offsetPage: "+offsetPage);
						if (offsetPage < missedCallPages){
							$('#updates_right').find('a').show();
							
						} else {
							$('#updates_right').find('a').hide();
						}
						if (currentOffset > 0){
							$('#updates_left').find('a').show();
						}
					} else {
						var statusText = "Online", statusClass = "contact_online";
						switch (field.online_status){
							case "online":
								statusText = "Online"
								statusClass = "contact_online"
								break
							case "offline":
								statusText = "Offline"
								statusClass = "contact_offline"
								break
							case "away":
								statusText = "Away"
								statusClass = "contact_away"
								break
							case "busy":
								statusText = "Busy"
								statusClass = "contact_busy"
								break
						};
						if (field.profile_filename_small)
							{source = "images/users/small/"+field.profile_filename_small}
						else {
							source = "images/global/silhouette_sm.png";}
						$('#show_missed_calls').append('<li onmouseover="showStatus($(this), \''+statusText+'\')" onmouseout="hideStatus($(this))"><a href="#"><div class="contact_profile '+statusClass+'"><img src="'+source+'"/></div><div>'+field.first_name+'</div></a></li>');
						
					}
				})
			},"json"
		);

		$.post("actions/showAddedToContacts.php", {offset:0},
			function(result){
				var currentOffset = $('input[name=offset]').val();
				$.each(result, function(i, field){
					if (i == 0){
						var newContactsPages = Math.ceil(field.new_contacts_count/20);
						var offsetPage = (currentOffset/20)+1;
						if (offsetPage < newContactsPages){
							$('#updates_right').find('a').show();
							
						} else {
							$('#updates_right').find('a').hide();
						}
						if (currentOffset > 0){
							$('#updates_left').find('a').show();
						}
					} else {
						var statusText = "Online", statusClass = "contact_online";
						switch (field.online_status){
							case "online":
								statusText = "Online"
								statusClass = "contact_online"
								break
							case "offline":
								statusText = "Offline"
								statusClass = "contact_offline"
								break
							case "away":
								statusText = "Away"
								statusClass = "contact_away"
								break
							case "busy":
								statusText = "Busy"
								statusClass = "contact_busy"
								break
						};
						if (field.profile_filename_small)
							{source = "images/users/small/"+field.profile_filename_small}
						else {
							source = "images/global/silhouette_sm.png";}
						$('#show_contacts').append('<li onmouseover="showStatus($(this), \''+statusText+'\')" onmouseout="hideStatus($(this))"><a href="#"><div class="contact_profile '+statusClass+'"><img src="'+source+'"/></div><div>'+field.first_name+'</div></a></li>');
						
					}
				})
			}, "json"
		);
		
		$.getJSON("actions/showNewInterestsOfContacts.php",function(result){
			var missedCallOffset = $('input[name=offset]').val();
			var missedCallPagination = 20;	
			$.each(result, function(i, field){
				if (i == 0){

				} else {
					$('#show_contact_interests').append('<li class="community_wall tile_tag" onmouseup="hideInterest($(this))" onmouseout="hideInterest($(this))" onmouseover="showInterest($(this), \''+field.interest_name+'\')"><img src="images/interests/'+field.tile_filename+'"></li>');
				}
			})
		});
		
		$.getJSON("actions/showUsersWithSharedInterests.php",function(result){
			var missedCallOffset = $('input[name=offset]').val();
			var missedCallPagination = 20;	
			$.each(result, function(i, field){
				if (i == 0){

				} else {
					var statusText = "Online", statusClass = "contact_online";
					switch (field.online_status){
						case "online":
							statusText = "Online"
							statusClass = "contact_online"
							break
						case "offline":
							statusText = "Offline"
							statusClass = "contact_offline"
							break
						case "away":
							statusText = "Away"
							statusClass = "contact_away"
							break
						case "busy":
							statusText = "Busy"
							statusClass = "contact_busy"
							break
					};
					if (field.profile_filename_small)
						{source = "images/users/small/"+field.profile_filename_small}
					else {
						source = "images/global/silhouette_sm.png";}
					$('#show_interests').append('<li onmouseover="showStatus($(this), \''+statusText+'\')" onmouseout="hideStatus($(this))"><a href="#"><div class="contact_profile '+statusClass+'"><img src="'+source+'"/></div><div>'+field.first_name+'</div></a></li>');
					
				}
			})
		});
	});

	$('#profile_frame').mouseover(function() {
		$(this).css('background-position','0px -283px');
	});
	
	$('#profile_frame').mouseout(function() {
		$(this).css('background-position','0px 0px');
	});
	
	$('#profile_frame').click(function(){
		$('#updates').hide();
		$('#updates_missed_calls').hide();
		$('#updates_contacts').hide();
		$('#updates_contact_interests').hide();
		$('#updates_interests').hide();
		$('#updates_left').hide();
		$('#updates_right').hide();
		$('#upload_profile_area').show();
	});
	
	function showPagination(){
		$('#updates_right').show();
		$('#updates_left').show();
	}
	
/*	function missedCallRight(offset){
		$.post(
			"actions/showMissedCalls.php",
			{offset: offset},
			function(data) {
				$('#show_missed_calls').empty();
				$.each(data, function(i, field){
					if (i == 0){
						var missedCallPages = Math.ceil(field.missed_calls_count/20);
						var offsetPage = (offset/20)+1;
						if (offsetPage < missedCallPages){
							$('#updates_right').find('a').show();
						} else {
							$('#updates_right').find('a').hide();
						}
						if (offset > 0){
							$('#updates_left').find('a').show();
						}
						$('#updates_right').find('a').live('click', function() {
							missedCallRight(offset+20);
						});
					} else {
						var statusText = "Online", statusClass = "contact_online";
						switch (field.online_status){
							case "online":
								statusText = "Online"
								statusClass = "contact_online"
								break
							case "offline":
								statusText = "Offline"
								statusClass = "contact_offline"
								break
							case "away":
								statusText = "Away"
								statusClass = "contact_away"
								break
							case "busy":
								statusText = "Busy"
								statusClass = "contact_busy"
								break
						};
						if (field.profile_filename_small)
							{source = "images/users/small/"+field.profile_filename_small}
						else {
							source = "images/global/silhouette_sm.png";}
						$('#show_missed_calls').append('<li onmouseover="showStatus($(this), \''+statusText+'\')" onmouseout="hideStatus($(this))"><a href="#"><div class="contact_profile '+statusClass+'"><img src="'+source+'"/></div><div>'+field.first_name+'</div></a></li>');
					}
				})
			}, "json"
		)
	}
*/
	
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
	
	// Call imgAreaSelect to crop profile picture and associated coordinates
	$('.profile_pic').imgAreaSelect({
        handles: true,
		aspectRatio: "3:2",
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

	// Function used by imgAreaSelect to preview profile picture	
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
						$('.profile_crop_error').html(data.message); 
					}else{
						$('.post_crop_pic').attr('src','images/users/large/'+data.message);
						$('#profile_thumbnail_preview img').attr('src','images/users/large/'+data.message);
						$('input[name=cropFile]').val(data.message);
						$('input[name=x1]').val('');
						$('input[name=y1]').val('');
						$('input[name=x2]').val('');
						$('input[name=y2]').val(''); 
						$('input[name=w]').val('');
						$('input[name=h]').val('');
						$('.profile_crop_error').html(''); 
						$('.profile_pic').imgAreaSelect({
							hide: true
						});
						$('#crop_profile_pic').hide();
						$('#crop_profile_thumbnail').show();
					}
				}, "json"
			);
	});

	// Call imgAreaSelect to crop profile thumbnail picture and associated coordinates
	$('.post_crop_pic').imgAreaSelect({
        handles: true,
		aspectRatio: "1:1",
		onSelectChange: previewProfileThumbnail,
		onSelectEnd: function (img, selection) {				
            $('input[name=x1]').val(selection.x1);
            $('input[name=y1]').val(selection.y1);
            $('input[name=x2]').val(selection.x2);
            $('input[name=y2]').val(selection.y2); 
	        $('input[name=w]').val(selection.width);
            $('input[name=h]').val(selection.height);
        }		
    });

	// Function used by imgAreaSelect to preview profile thumbnail	
	function previewProfileThumbnail(img, selection) {
		if (!selection.width || !selection.height)
		return;
		
		var scaleX = 80 / selection.width;
		var scaleY = 80 / selection.height;
		
		$('#profile_thumbnail_preview img').css({
		width: Math.round(scaleX*img.width),
		height: Math.round(scaleY*img.height),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1), 
		});
			
	} 
	
	$("#profile_thumbnail_form").submit(function(event) {
		event.preventDefault(); 
		$.post(
				"actions/profileCropThumbnail.php",
				$('#profile_thumbnail_form').serialize(),
				function(data){
					if (data.success == 0){
						$('.profile_crop_error').html(data.message); 
					}else{
						$('.profile_crop_error').removeClass('error_text').addClass('success_text').html('Thumbnail is saved!');
						window.location = "canvas.php";
					}
				}, "json"
			);
	});
	
	$('.dashboard_view').click(function(){
		showUpdates();
		hideUpdatesResult();
	});
	
	function hideUpdatesResult(){
		$('#updates').show();
		$('#updates_left').hide();
		$('#updates_right').hide();
	}
	
	function showUpdates(){
		$('#updates_missed_calls').hide();
		$('#updates_contacts').hide();
		$('#updates_contact_interests').hide();
		$('#updates_interests').hide();
	}

	function showTransparentUpdate(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before('<div class="transparent_update">'+tag+'</div>');
	};
	
	function hideTransparentUpdate(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('img').prev('div').remove();
	};

	function showStatus(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before('<div class="transparent_tag">'+tag+'</div>');
	};
	
	function hideStatus(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('img').prev('div').remove();
	};
	
	function showInterest(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before('<div class="transparent_tag">'+tag+'</div>');
	};
	
	function hideInterest(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('div').remove();
	};
	
</script>
