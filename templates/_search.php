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
/*		var emails = [
				 { name: "Peter Pan", to: "peter@pan.de" },
				 { name: "Molly", to: "molly@yahoo.com" },
				 { name: "Forneria Marconi", to: "live@japan.jp" },
				 { name: "Master <em>Sync</em>", to: "205bw@samsung.com" },
				 { name: "Dr. <strong>Tech</strong> de Log", to: "g15@logitech.com" },
				 { name: "Don Corleone", to: "don@vegas.com" },
				 { name: "Mc Chick", to: "info@donalds.org" },
				 { name: "Donnie Darko", to: "dd@timeshift.info" },
				 { name: "Quake The Net", to: "webmaster@quakenet.org" },
				 { name: "Dr. Write", to: "write@writable.com" }
				]; 
		$("#user_search_field").autocomplete(emails,{
				autoFill: true,
				formatItem: function(row, i, max) {
				return i + "/" + max + ": \"" + row.name + "\" [" + row.to + "]";
				},
				formatMatch: function(row, i, max) {
				return row.name + " " + row.to;
				},
				formatResult: function(row) {
				return row.to;
				}
		});
*/

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
			mustMatch: true,
			max: 5
		}); 
	});
</script>
