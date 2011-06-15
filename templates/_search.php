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
		var interest_name = "Apple apple1 apple2 apple3 Abalone Almond Alabama".split(" ");
		$("#user_search_field").focus().autocomplete(interest_name,{
			autoFill: true,
			max: 5
		});
	});
</script>