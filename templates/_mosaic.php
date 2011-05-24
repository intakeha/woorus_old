<script type="text/javascript" src="js/ajaxfileupload.js"></script>

<div id="mosaic">
	<div class="pagination_mosaic"><a class="arrows pagination_left" href="#"></a></div>
	<div id="tiles">
	    <form id="tsearch_form" action="../actions/tileSearch.php" method="POST">
            <input type="text" id="tile_search_field" name="tile_name" value="Type an interest..." onfocus="if($(this).val()=='Type an interest...'){$(this).val('')};" onblur="if($(this).val()==''){$(this).val('Type an interest...')};" maxlength="60">
            <input class="buttons" id="tile_search" type="submit" name="tile_search" value="Search">
        </form>
		<div id="tiles_legend">
            <a href="#" id="sponsoredTiles"><div><span class="legend_squares" id="redSquare"></span>Sponsored Tiles</div></a>
            <a href="#" id="myTiles"><div><span class="legend_squares" id="blueSquare"></span>Uploaded Tiles</div></a>
            <a href="#" id="communityTiles"><div><span class="legend_squares" id="graySquare"></span>Community Tiles</div></a>
        </div>
        <div id="tiles_bank">
            <ul id="tile_display">
            <li class="uploaded tile_tag" onmouseover="showInterest($(this), 'Ferrari')" onmouseout="hideInterest($(this))"><img src="images/interests/ferrari.png" /></li>
<!--        
           	<li class="uploaded" onmouseover="showInterest($(this), 'BEP')" onmouseout="hideInterest($(this))" style="background-image:url(images/interests/bep.png)" onclick="addToWall('257','298')"></li>
            <li class="uploaded" onmouseover="showInterest2($(this), 'Santorini')" onmouseout="hideInterest2($(this))"><img src="images/interests/santorini.png" /></li>
            <li class="uploaded" onmouseover="showInterest3($(this), 'Hats')" onmouseout="hideInterest3($(this),$(this).css('background-image'))" style="background-image: url(images/interests/hats.png);"></li>

			<li class="sponsored" style="background-image: url(images/interests/vespa.png);">vespa</li>
            <li class="sponsored" style="background-image: url(images/interests/santorini2.png);">Greece</li>
            <li class="sponsored" style="background-image: url(images/interests/cablecar.png);">San Francisco</li>
            <li class="uploaded" style="background-image: url(images/interests/converse.png);">Converse</li>
            <li class="uploaded" style="background-image: url(images/interests/natalie.png);">Natalie Portman</li>
            <li class="uploaded" style="background-image: url(images/interests/vangogh.png);">Van Gogh</li>
            <li class="uploaded" style="background-image: url(images/interests/loveactually.png);">Love Actually</li>
            <li class="uploaded" style="background-image: url(images/interests/coffee.png);">Coffee</li>
            <li class="uploaded" style="background-image: url(images/interests/sydney.png);">Sydney</li>
            <li class="uploaded" style="background-image: url(images/interests/paul.png);">Beatles</li>
            <li class="community" style="background-image: url(images/interests/vespa2.png);">Vespa</li>
            
-->
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
                    <div id="loading_gif"><img style="display: none;" src="images/global/loading.gif" /></div>
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
            <ul id="wall_display">
                <li style="background-image: url(images/interests/cablecar.png);">San Francisco</li>
                <li style="background-image: url(images/interests/boudin.png);">Boudin</li>
                <li style="background-image: url(images/interests/taylor.png);">Taylor</li>
                <li style="background-image: url(images/interests/fergie.png);">Fergie</li>
                <li style="background-image: url(images/interests/john.png);">John</li>
                <li style="background-image: url(images/interests/paul.png);">Paul</li>
                <li style="background-image: url(images/interests/santorini.png);">Greece</li>
                <li style="background-image: url(images/interests/conan.png);">Conan</li>
                <li style="background-image: url(images/interests/vespa.png);">Vespa</li>
                <li style="background-image: url(images/interests/tivo.png);">Tivo</li>
                <li style="background-image: url(images/interests/george.png);">George</li>
                <li style="background-image: url(images/interests/ringo.png);">Ringo</li>
                <li style="background-image: url(images/interests/ferrari.png);">Ferrari</li>
                <li style="background-image: url(images/interests/converse.png);">Converse</li>
                <li style="background-image: url(images/interests/homer.png);">Homer</li>
                <li style="background-image: url(images/interests/icehotel.png);">Ice Hotel</li>
                <li style="background-image: url(images/interests/pinata.png);">pinata</li>
                <li style="background-image: url(images/interests/loveactually.png);">Love Actually</li>
                <li style="background-image: url(images/interests/bep2.png);">BEP</li>
                <li style="background-image: url(images/interests/vegas.png);">Las Vegas</li>
                <li style="background-image: url(images/interests/breastcancer.png);">Breast Cancer</li>
                <li style="background-image: url(images/interests/training.png);">Training</li>
                <li style="background-image: url(images/interests/newyorker.png);">New Yorker</li>
                <li style="background-image: url(images/interests/007.png);">Bond</li>
                <li style="background-image: url(images/interests/natalie.png);">Natalie Portman</li>
                <li style="background-image: url(images/interests/yoga.png);">Yoga</li>
                <li style="background-image: url(images/interests/wholefoods.png);">Whole Foods</li>
                <li style="background-image: url(images/interests/patron.png);">Patron</li>
                <li style="background-image: url(images/interests/timberlake.png);">Justin Timberlake</li>
                <li style="background-image: url(images/interests/giants2.png);">Giants</li>
                <li style="background-image: url(images/interests/sailing.png);">Sailing</li>
                <li style="background-image: url(images/interests/vangogh.png);">Van Gogh</li>
                <li style="background-image: url(images/interests/sydney.png);">Sydney</li>
                <li style="background-image: url(images/interests/yogurt.png);">Yogurt</li>
                <li style="background-image: url(images/interests/coffee.png);">Coffee</li>
                <li style="background-image: url(images/interests/hefeweizen.png);">Beer</li>
            </ul>
        </div>
    </div>
</div>
