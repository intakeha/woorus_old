<script type="text/javascript" src="js/ajaxfileupload.js"></script>

<div id="mosaic">
	<div class="pagination_mosaic"><a id="tile_bank_left" class="arrows pagination_left" href="#" style="display: none;"></a></div>
	<div id="tiles">
	    <form id="tsearch_form" action="actions/tileSearch.php" method="POST">
            <input type="text" id="tile_search_field" name="tile_search" maxlength="60">
            <input type="hidden" name="query_type" value="" />
            <input type="hidden" name="offset" value="0" />
            <input class="buttons" id="tile_search_submit" type="submit" name="tile_search_submit" value="Search">
        </form>
		<div id="tiles_legend">
            <div id="sponsoredTiles" onmouseover="$(this).addClass('hoverFilter')" onmouseout="$(this).removeClass('hoverFilter')"><span class="legend_squares" id="redSquare"></span>Sponsored Tiles</div>
			<div id="communityTiles"><span class="legend_squares" id="graySquare"></span>Community Tiles</div>
            <div id="myTiles"><span class="legend_squares" id="blueSquare"></span>My Uploaded Tiles</div>
        </div><span id="tile_bank_offset" style="display: none;"></span>
        <div id="tiles_bank">
            <ul id="tile_display">
            	<li class="uploaded tile_tag" onmouseover="showInterest($(this), 'Ferrari')" onmouseout="hideInterest($(this))"><img src="images/interests/ferrari.png" /></li>
            </ul>
        </div><div id="clear"></div>
        <div id="customized_tile">
        	<span>&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; or &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</span>
            	<p>Create your own customized tile: </p>     
                <form id="tile_upload_form" action="upload_file.php" method="post" enctype="multipart/form-data">
                    <input class="text_form" type="file" name="file" id="file" style="width: 430px;" /> 
                    <br />
                    <input class="buttons" id="tile_pic_upload" type="submit" name="filename" value="Upload">
                    <div class="error_text" id="tile_upload_error"></div>
                    <div class="success_text" id="tile_upload_success" style="display: none;"></div>
                    <img id="tile_loading" style="display: none;" src="images/global/loading.gif" />
                </form>
        </div>
    </div>
	<div class="pagination_mosaic"><a id="tile_bank_right" class="arrows pagination_right" href="#" style="display: none;"></a></div>
    <div id="tile_crop" style="display: none;">
    	<div id="crop_instruction">Click on the image to crop and customize your tile.</div>
        <div id="original_photo">
            <img class="tile_pic" />
        </div>
        <div id="preview_area">
        	<font>Tile Preview</font>
            <div id="preview">
                <img class="tile_pic" />
            </div>
        </div>
        <div class="clear"></div>
        <div id="tag_tile">
        	Tag your tile with your interest
            <form id="tile_crop_form" action="actions/crop.php" method="POST">
            	<input type="text" class="text_form" id="assign_tag" name="assign_tag" maxlength="60">            
                <input type="hidden" name="x1" value="" />
                <input type="hidden" name="y1" value="" />
                <input type="hidden" name="x2" value="" />
                <input type="hidden" name="y2" value="" />
				<input type="hidden" name="w" value="" />
                <input type="hidden" name="h" value="" />
                <input type="hidden" name="cropFile" value="" />
                <br />
                <input type="submit" id="crop_save" class="buttons save" name="submit" value="Save" /><input class="buttons cancel" type="button" name="cancel" value="Cancel" onclick="location.href='canvas.php?page=mosaic'"/>
            </form>
            <div class="error_text" id="crop_error"></div>
        </div>
    </div>
    <div id="mosaic_wall">
        &laquo; Personalize your mosaic wall &raquo;         
        <div id="wall">
            <ul id="wall_display" class="tile_sort">
            </ul>
        </div>
    </div>
    <div id="wall_trash"><ul class="tile_sort" id="remove_tile"></ul></div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		
		// Clear mosaic wall and populate wall from backend
		$('#wall_display').empty();
		$.getJSON("actions/populateMosaicWall.php",function(result){
			$.each(result, function(i, field){
			  $('#wall_display').append("<li class=\'community_wall tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+field.tile_filename+"\'></li>");
			});
		});	

		// Activate sort on the mosaic wall and trash using "tile sort" class and connect via UL
		$(".tile_sort").sortable({
			tolerance: "pointer",
			cursor: "pointer",
			containment: "document",
			dropOnEmpty: true,
			connectWith: "ul",
			update: function(event, ui) {
				var data = $('#wall_display').sortable('toArray').toString();
				$.post('actions/moveTileOnWall.php', {tile_array: data}); 
				alert(data);
			}
		});
		$("#wall_display, #remove_tile").disableSelection(); // Disable text selection when dragging tiles
		
		// Validate tile search form for tile bank
		$("#tsearch_form").validate({
			onsubmit: true,
			onclick: true,
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					$('#tile_upload_success').hide(); 
					$("#tile_upload_error").show().text(validator.errorList[0].message); 
				}
			},
			submitHandler: function(form) {
				$('#tile_upload_error').hide();
				$('input[name=query_type]').val('');
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tile_bank_offset = $('#tile_bank_offset').text();
						var tile_bank_pagination = 15;
						$('#tile_display').empty();
						$.each(data, function(i, field){
							switch (field.tile_type){
								case "S":
									tile_type = "sponsored"
									break
								case "U":
									tile_type = "uploaded"
									break
								case "C":
									tile_type = "community"
									break
							};
							if (i == 0){
								var tile_bank_pages = field.tile_count;
								alert(tile_bank_pages);
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
			},
			errorPlacement: function(error, element) {
				// Override error placement to not show error messages beside elements //
			},
			rules: {
				tile_search: {
					required: true,
					minlength: 2,
					maxlength: 60	
				}
			},
			messages: {
				tile_search: {			
					required: "Please enter an interest in the search field.",
					minlength: "Search term should be at least 2 characters.",
					maxlength: "Search term should be fewer than 60 characters."
				}
			}
		});
		
		// Creating variable to call tile search validation form via function
		//var tsearchValidator = $("#tsearch_form").validate();
		
		// Associate each click on the tile bank legend to update query type and call validator
		$("#sponsoredTiles").click(function() {
			$('#communityTiles').removeClass('selectedFilter');
			$('#myTiles').removeClass('selectedFilter');
			$('input[name=query_type]').val('S');
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tile_bank_offset = $('#tile_bank_offset').text();
						var tile_bank_pagination = 15;
						$('#sponsoredTiles').addClass('selectedFilter');
						$('#tile_display').empty();
						$.each(data, function(i, field){
							switch (field.tile_type){
								case "S":
									tile_type = "sponsored"
									break
								case "U":
									tile_type = "uploaded"
									break
								case "C":
									tile_type = "community"
									break
							};
							if (i == 0){
								var tile_bank_pages = field.tile_count;
								alert(tile_bank_pages);
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
        	}
		});
		
		$("#communityTiles").click(function() {
			$('#sponsoredTiles').removeClass('selectedFilter');
			$('#myTiles').removeClass('selectedFilter');
			$('input[name=query_type]').val('C');
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tile_bank_offset = $('#tile_bank_offset').text();
						var tile_bank_pagination = 15;
						$('#communityTiles').addClass('selectedFilter');
						$('#tile_display').empty();
						$.each(data, function(i, field){
							switch (field.tile_type){
								case "S":
									tile_type = "sponsored"
									break
								case "U":
									tile_type = "uploaded"
									break
								case "C":
									tile_type = "community"
									break
							};
							if (i == 0){
								var tile_bank_pages = field.tile_count;
								alert(tile_bank_pages);
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
        	}
		});
		
		$("#myTiles").click(function() {
			$('#sponsoredTiles').removeClass('selectedFilter');
			$('#communityTiles').removeClass('selectedFilter');
			$('input[name=query_type]').val('U');
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tile_bank_offset = $('#tile_bank_offset').text();
						var tile_bank_pagination = 15;
						$('#myTiles').addClass('selectedFilter');
						$('#tile_display').empty();
						$.each(data, function(i, field){
							switch (field.tile_type){
								case "S":
									tile_type = "sponsored"
									break
								case "U":
									tile_type = "uploaded"
									break
								case "C":
									tile_type = "community"
									break
							};
							if (i == 0){
								var tile_bank_pages = field.tile_count;
								alert(tile_bank_pages);
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
        	}
		});

	});

</script>








