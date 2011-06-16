<div id="search">
	<div id="search_slide"></div>
	<form id="search_form" action="../actions/userSearch.php" method="POST">
		    <input type="text" class="text_form ac_input" id="user_search_field" name="user_search" maxlength="60">
		    <input type="hidden" name="offset" value="0" />
		    <input class="buttons" id="user_search_submit" type="submit" name="user_search_submit" value="Search">
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {	
		$("#user_search_field").autocomplete("actions/interestList.php",{
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
			autoFill: true,
			minChars: 1,
			selectFirst: true,
			max: 5,
			delay: 1
		}); 
	});
</script>
