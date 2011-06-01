<script type="text/javascript" src="js/ajaxfileupload.js"></script>

<div id="mosaic">
	<div class="pagination_mosaic"><a class="arrows pagination_left" href="#"></a></div>
	<div id="tiles">
	    <form id="tsearch_form" action="actions/tileSearch.php" method="POST">
            <input type="text" id="tile_search_field" name="tile_search" maxlength="60">
            <input class="buttons" id="tile_search_submit" type="submit" name="tile_search_submit" value="Search">
        </form>
		<div id="tiles_legend">
            <a href="#" id="sponsoredTiles"><div><span class="legend_squares" id="redSquare"></span>Sponsored Tiles</div></a>
            <a href="#" id="myTiles"><div><span class="legend_squares" id="blueSquare"></span>Uploaded Tiles</div></a>
            <a href="#" id="communityTiles"><div><span class="legend_squares" id="graySquare"></span>Community Tiles</div></a>
        </div>
        <div id="tiles_bank">
            <ul id="tile_display" class="tile_sort">
            	<li class="uploaded tile_tag" onmouseover="showInterest($(this), 'Ferrari')" onmouseout="hideInterest($(this))"><img src="images/interests/ferrari.png" /></li>
            </ul><div id="clear"></div>
        </div>
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
	<div class="pagination_mosaic"><a class="arrows pagination_right" href="#"></a></div>
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
		$('#wall_display').empty();
		$.getJSON("actions/populateMosaicWall.php",function(result){
			$.each(result, function(i, field){
			  $('#wall_display').append("<li class=\'community_wall tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\"showInterest($(this), \'"+field.interest_name+"\')\" onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+field.tile_filename+"\'></li>");
			});
		});		

		$("#wall_display").sortable({
			tolerance: 'pointer',
			cursor: 'pointer',
			update: function(event, ui) {
				var data = $('#wall_display').sortable('toArray').toString();
				$.post('actions/moveTileOnWall.php', {tile_array: data}); 
				alert(data);
			}
		});
		$("#wall_display").disableSelection(); 

	});


/*
		$(function() {
			$( "ul.tile_sort" ).sortable({
				tolerance: 'pointer',
				cursor: 'pointer',
				dropOnEmpty: true,
				connectWith: 'ul.tile_sort',
				update: function(event, ui) { 
					var data = $('#wall_display').sortable('toArray').toString();
					if(this.id == 'remove_tile') {
						// Remove the element dropped on #remove_tile
						// jQuery('#'+ui.item.attr('id')).remove();
					} else {
						// Update back-end to reflect mosaic wall
						$.post('actions/moveTileOnWall.php', {tile_array: data}); 	
					}
				}
			});
			$( "#wall_display" ).disableSelection();
			return false;
		});
*/

</script>








