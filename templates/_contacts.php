<div id="contact_container" class="fluid">
    <div id="contacts">
        <div>
            <form id="csearch_form" action="../actions/showContacts.php" method="POST">
                <input type="text" class="text_form ac_input" id="contact_search_field" name="first_name" maxlength="60">
                <input type="hidden" name="offset" value="0" />
                <input class="buttons" id="contact_search_submit" type="submit" name="contact_search_submit" value="Search">
            </form>       	
            <div id="csearch_error">
            </div>
        </div>
        <div id="contact_results">
            <div class="pagination_contacts"><a id="contact_left" class="arrows pagination_left"></a></div>
            <ul id="contact_mosaic">			
            </ul>
            <div class="pagination_contacts"><a id="contact_right" class="arrows pagination_right"></a></div>
            <div id="contact_legend">
                <div class="online"><span class="legend_squares" id="greenSquare"></span>Online</div>
                <div class="away"><span class="legend_squares" id="orangeSquare"></span>Away</div>
                <div class="busy"><span class="legend_squares" id="redSquare"></span>Busy</div>
                <div class="offline"><span class="legend_squares" id="graySquare"></span>Offline</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	$(document).ready(function(){
		// Remove all pagination arrows
		$('.arrows').hide();
		showContacts();
		
		// Activate autocomplete for contact search field
		$("#contact_search_field").autocomplete("actions/contactList.php",{
			dataType: 'json',
			parse: function(data) {
				return $.map(data, function(item) {
					return {
						data: item,
						value: item.first_name,
						result: item.first_name
					}
				}); 
			}, 
			formatItem: function(item) {
				return item.first_name;
			},
			formatMatch: function(item) {
				return item.first_name;
			},
			formatResult: function(item) {
				return item.first_name;
			},
			autoFill: true,
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}).result( function (){
			$('input[name=offset]').val(0);
			$('#contact_mosaic').empty();
			showContacts();
		})
		
		// Bind right pagination with contact list
		$("#contact_right").click(function() {
			var currentOffset = $('input[name=offset]').val();
			var nextOffset = parseInt(currentOffset)+20;
			$('input[name=offset]').val(nextOffset);
			$('#contact_mosaic').empty();
			showContacts();
		});
		
		// Bind left pagination with contact list
		$("#contact_left").click(function() {
			var currentOffset = $('input[name=offset]').val();
			var prevOffset = parseInt(currentOffset)-20;
			$('input[name=offset]').val(prevOffset);
			$('#contact_mosaic').empty();
			showContacts();
		});
		
		$("#csearch_form").submit(function(event) {
			event.preventDefault();
			$('input[name=offset]').val(0);
			$('#contact_mosaic').empty();
			showContacts();
		});
		
		function showContacts(){
			$.post(
				"actions/showContacts.php",
				$('#csearch_form').serialize(),
				function(data){
					$('#contact_mosaic').empty();
					$.each(data, function(i, field){
						if (i == 0){
							var contactPages = Math.ceil(field.contact_count/20);	
							var currentOffset = $('input[name=offset]').val();
							var currentPage = (currentOffset/20)+1;
							if (currentPage < contactPages) {
								$("#contact_right").show();
							} else {
								$("#contact_right").hide();
							}
							if (currentPage > 1) {
								$("#contact_left").show();
							} else {
								$("#contact_left").hide();
							}
						} else {
							var statusText = "Offline", statusClass = "contact_offline";
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
							if (field.profile_filename_small){	
								profilePic = "images/users/small/"+field.profile_filename_small;			
							} else { 
								profilePic = "images/global/silhouette_sm.png";
							}
							if (field.first_name){firstName = field.first_name} else {firstName = "Unknown"};
							$('#contact_mosaic').append('<li onmouseover="showStatus($(this), \''+statusText+'\')" onmouseout="hideStatus($(this))"><a href="#"><div class="contact_profile '+statusClass+'"><img src="'+profilePic+'"/></div><div>'+firstName+'</div></a></li>')
						}
					});
				}, "json"
			);
			
		}
		
	});


	function showStatus(obj, tag){
		obj.find('img').addClass('transparent_tile');
		obj.find('img').before('<div class="transparent_tag">'+tag+'</div>');
	};
	
	function hideStatus(obj){
		obj.find('img').removeClass('transparent_tile');
		obj.find('img').prev('div').remove();
	};
</script>


