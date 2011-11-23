<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<div id="mosaic_container" class="fluid">
    <div id="mosaic">
        <div class="pagination_mosaic"><a id="tile_bank_left" class="arrows pagination_left" style="display: none;"></a></div>
        <div id="tiles">
            <form id="tsearch_form" action="actions/tileSearch.php" method="POST">
                <input type="text" id="tile_search_field" name="tile_search" maxlength="60">
                <input type="hidden" name="query_type" value="" />
                <input type="hidden" name="offset" value="0" />
                <input class="buttons" id="tile_search_submit" type="submit" name="tile_search_submit" value="Search">
            </form>
            <div id="tile_bank_message" class="error_text" style="display: none;"></div>
            <div id="tiles_legend">
                <div id="sponsoredTiles" onmouseover="$(this).addClass('hoverFilter')" onmouseout="$(this).removeClass('hoverFilter')"><span class="legend_squares" id="redSquare"></span>Sponsored Tiles</div>
                <div id="communityTiles" onmouseover="$(this).addClass('hoverFilter')" onmouseout="$(this).removeClass('hoverFilter')"><span class="legend_squares" id="graySquare"></span>Community Tiles</div>
                <div id="myTiles" onmouseover="$(this).addClass('hoverFilter')" onmouseout="$(this).removeClass('hoverFilter')"><span class="legend_squares" id="blueSquare"></span>My Uploaded Tiles</div>
            </div>
            <div id="tiles_bank">
                <ul id="tile_display">
                </ul>
            </div><div id="clear"></div>
            <div id="customized_tile">
                <span>&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; or &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</span>
                    <p>Create your own customized tile: </p>     
                    <form id="tile_upload_form" action="actions/uploadTile.php" method="post" enctype="multipart/form-data">
                        <input class="text_form" type="file" name="file" id="file" style="width: 430px;" /> 
                        <br />
                        <input class="buttons" id="tile_pic_upload" type="submit" name="filename" value="Upload">
                        <div class="error_text" id="tile_upload_error"></div>
                        <div class="success_text" id="tile_upload_success" style="display: none;"></div>
                        <img id="tile_loading" style="display: none;" src="images/global/loading.gif" />
                    </form>
            </div>
        </div>
        <div class="pagination_mosaic"><a id="tile_bank_right" class="arrows pagination_right" style="display: none;"></a></div>
        <div id="tile_crop" style="display: none;">
            <div id="tile_crop_instruction">Click and drag on the image to customize your tile.</div>
            <div id="tile_original_photo">
                <img class="tile_pic" />
            </div>
            <div id="tile_preview_area">
                <font>Tile Preview</font>
                <div id="tile_preview">
                    <img />
                </div>
            </div>
            <div class="clear"></div>
            <div id="tag_tile">
                Tag your tile with your interest
                <form id="tile_crop_form" action="actions/tileCrop.php" method="POST">
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
            &laquo; Personalize your mosaic interest wall &raquo;         
            <div id="wall">
                <ul id="wall_display" class="wall_sort" style="display: block; overflow: hidden;">
                </ul>
            </div>
        </div>
        <div id="wall_trash"><ul class="trash_sort" id="remove_tile"></ul></div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		
		// Clear mosaic wall and populate wall from backend
		$('#wall_display').empty();
		$('#tiles_bank, #tiles_legend').hide();
		$.getJSON("actions/populateMosaicWall.php",function(result){
			$.each(result, function(i, field){
				switch (field.tile_type){
					case "S":
						tile_type = "sponsored";
						break
					case "U":
						tile_type = "uploaded";
						break
					case "C":
						tile_type = "community";
						break
					default:
						tile_type = "community";
				};
			  	$('#wall_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+field.tile_filename+"\'></li>");
			});
		});	
		
		// Create sortable lists between tile bank and mosaic wall
		$("#tile_display, #wall_display").sortable({
			connectWith: ".wall_sort",
			tolerance: "pointer",
			containment: 'document',
			update: function(event, ui) {
				var data = $('#wall_display').sortable('toArray').toString();
				$.post('actions/moveTileOnWall.php', {tile_array: data}); 
			}
		}).disableSelection();
		
		// Create sortable lists between mosaic wall and trash can
		$("#wall_display, #remove_tile").sortable({
			connectWith: ".trash_sort",
			dropOnEmpty: true,
		}).disableSelection();
		
		// Activate autocomplete to the tile search field
		$("#tile_search_field").autocomplete("actions/interestList.php",{
			dataType: 'json',
			parse: function(data) {
				return $.map(data, function(item) {
					return {
						data: item,
						value: item.interest_name,
						result: item.interest_name
					}
				}); 
			}, 
			formatItem: function(item) {
				return item.interest_name;
			},
			formatMatch: function(item) {
				return item.interest_name;
			},
			formatResult: function(item) {
				return item.interest_name;
			},
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}).result( function (){
			if($("#tsearch_form").valid()) { 
				$('#sponsoredTiles').removeClass('selectedFilter');
				$('#communityTiles').removeClass('selectedFilter');
				$('#myTiles').removeClass('selectedFilter');
				$('input[name=query_type]').val('');
				bankReset();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
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
								var tileBankPages = Math.ceil(field.tile_count/15);	
								if (tileBankPages > 1){
									$('#tile_bank_right').show();
								}
								if (field.tile_count == 0){
									hideBankErrors();
									$('#tile_bank_message').text('No tiles found for \"'+$('#tile_search_field').val()+'\". Be the first one to upload a tile for this interest!').show();
								}
							}else{
								$('#tiles_legend').show();
								$('#tiles_bank').slideDown('fast');	
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);}
		}); 
		
		// Activate autocomplete to the tag tile field
		$("#assign_tag").autocomplete("actions/interestList.php",{
			dataType: 'json',
			parse: function(data) {
				return $.map(data, function(item) {
					return {
						data: item,
						value: item.interest_name,
						result: item.interest_name
					}
				}); 
			}, 
			formatItem: function(item) {
				return item.interest_name;
			},
			formatMatch: function(item) {
				return item.interest_name;
			},
			formatResult: function(item) {
				return item.interest_name;
			},
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}); 
		
		// Validate tile search form for tile bank
		$("#tsearch_form").validate({
			onsubmit: true,
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					hideBankErrors();
					$("#tile_bank_message").show().text(validator.errorList[0].message); 
				}
			},
			submitHandler: function(form) {
				$('#sponsoredTiles').removeClass('selectedFilter');
				$('#communityTiles').removeClass('selectedFilter');
				$('#myTiles').removeClass('selectedFilter');
				$('input[name=query_type]').val('');
				bankReset();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						$('#tile_display').empty();
						if (data.success == 0){
							hideBankErrors();
							$("#tile_bank_message").show().html(data.message);
						}else{
							$.each(data, function(i, field){
								switch (field.tile_type){
									case "S":
										tile_type = "sponsored";
										break
									case "U":
										tile_type = "uploaded";
										break
									case "C":
										tile_type = "community";
										break
									default:
										tile_type = "community";
								};
								if (i == 0){
									var tileBankPages = Math.ceil(field.tile_count/15);	
									if (tileBankPages > 1){
										$('#tile_bank_right').show();
									}
									if (field.tile_count == 0){
										hideBankErrors();
										$('#tile_bank_message').text('No tiles found for \"'+$('#tile_search_field').val()+'\". Be the first one to upload a tile for this interest!').show();
									}
								}else{
									$('#tiles_legend').show();
									$('#tiles_bank').slideDown('fast');								
									$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
								}
							});
						}
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
		
		// Associate each click on the tile bank legend to update query type and call validator
		$("#sponsoredTiles").click(function() {
			$('#communityTiles').removeClass('selectedFilter');
			$('#myTiles').removeClass('selectedFilter');
			$('input[name=query_type]').val('S');
			bankReset();
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tileBankOffset = $('input[name=offset]').val();
						var tileBankPagination = 15;
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
								var tileBankPages = Math.ceil(field.tile_count/15);	
								if (tileBankPages > 1){
									$('#tile_bank_right').show();
								}
								if (field.tile_count == 0){
									hideBankErrors();
									$('#tile_bank_message').text('No sponsored tiles found for \"'+$('#tile_search_field').val()+'\".').show();
								}
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
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
			bankReset();
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tileBankOffset = $('input[name=offset]').val();
						var tileBankPagination = 15;
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
								var tileBankPages = Math.ceil(field.tile_count/15);	
								if (tileBankPages > 1){
									$('#tile_bank_right').show();
								}
								if (field.tile_count == 0){
									hideBankErrors();
									$('#tile_bank_message').text('No tiles found for \"'+$('#tile_search_field').val()+'\". Be the first one to upload a tile for this interest!').show();
								}
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
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
			bankReset();
			if($("#tsearch_form").valid()) { 
				$('#tile_upload_error').hide();
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
						var tileBankOffset = $('input[name=offset]').val();
						var tileBankPagination = 15;
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
								var tileBankPages = Math.ceil(field.tile_count/15);	
								if (tileBankPages > 1){
									$('#tile_bank_right').show();
								}
								if (field.tile_count == 0){
									hideBankErrors();
									$('#tile_bank_message').text('No tiles found for \"'+$('#tile_search_field').val()+'\". Customize your own tile for this interest!').show();
								}
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
        	}
		});

		$("#tile_bank_right").click(function() {
			var searchTerm = $('#tile_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+15;
			$('input[name=offset]').val(nextOffset);
			if($("#tsearch_form").valid()) { 
				$.post(
					"actions/tileSearch.php",
					$('#tsearch_form').serialize(),
					function(data){
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
								var tileBankPages = Math.ceil(field.tile_count/15);
								var offsetPage = ((nextOffset)/15)+1;
								if (offsetPage == tileBankPages){
									$('#tile_bank_right').hide();
								}
								if (nextOffset > 0){
									$('#tile_bank_left').show();
								}
							}else{
								$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
							}
						});
					}, "json"
				);
			}
		});
		
		$("#tile_bank_left").click(function() {
			var searchTerm = $('#tile_search_field').val();
			var currentOffset = $('input[name=offset]').val();
			var prevOffset = parseInt(currentOffset)-15;
			$('input[name=offset]').val(prevOffset);
			$.post(
				"actions/tileSearch.php",
				$('#tsearch_form').serialize(),
				function(data){
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
							var tileBankPages = Math.ceil(field.tile_count/15);
							var offsetPage = (prevOffset/15)+1;
							if (offsetPage < tileBankPages){
								$('#tile_bank_right').show();
							}
							if (prevOffset == 0){
								$('#tile_bank_left').hide();
							}		
						}else{
							$('#tile_display').append("<li class=\'"+tile_type+" tile_tag\' id=\'"+field.tile_id+"\' onmouseover=\'showInterest($(this), \""+field.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\' onclick=\"addToWall(\'"+field.tile_id+"\',\'"+field.interest_id+"\')\" ><img src=\'images/interests/"+field.tile_filename+"\'></li>");
						}
					});
				}, "json"
			);
		});
		
		function hideBankErrors(){
			$('#tiles_bank').slideUp('fast');
			$('#tile_bank_left').hide();
			$('#tile_bank_right').hide();
			$('#tiles_legend').hide();
		}
		
		function bankReset(){
			$('#tile_bank_left').hide();
			$('#tile_bank_right').hide();
			$('#tile_bank_message').hide().empty();
			$('#tile_upload_error').hide();
			$('input[name=offset]').val(0);
		}
		
	});
	
	function addToWall(tileID, interestID){
		$.post(
			"actions/addTileToWall.php",
			{ tile_id: tileID, interest_id: interestID },
			function(data){
				if (data.success == 0){
					$('#tile_upload_success').hide(); 
					$('#tile_upload_error').html(data.message); 
					$('#tile_upload_error').show();
				} else {
					$('#wall_display').append("<li class=\'community tile_tag\' id=\'"+data.tile_id+"\' onmouseover=\'showInterest($(this), \""+data.interest_name+"\")\' onmouseout=\'hideInterest($(this))\' onmouseup=\'hideInterest($(this))\'><img src=\'images/interests/"+data.tile_filename+"\'></li>");
					$('.tile_sort').sortable( "refresh" );
					$('#tile_upload_error').hide();
				}
			}, "json"
		);
		return false;
	};
	
	function showInterest(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before("<div class='transparent_tag'>"+tag+"</div>");
	};
	
	function hideInterest(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('div').remove();
	};
	
</script>