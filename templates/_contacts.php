<div id="contacts">
	<div>
		<form id="csearch_form" action="../actions/contactSearch.php" method="POST">
			<input type="text" class="text_form ac_input" id="contact_search_field" name="contact_search" maxlength="60">
			<input type="hidden" name="offset" value="0" />
			<input class="buttons" id="contact_search_submit" type="submit" name="contact_search_submit" value="Search">
		</form>
		<div id="csearch_error">
		</div>
	</div>
	<div>
		<ul id="result_entries_right">
			<li class="result_entry">
				<div class="list_users">
					<a class="feed_profile" href="#"><img src="images/users/james.png" /></a>
					<div>
						<div class="user_info">
							<div class="online_status"><a href="#">Melanie</a></div> 
							<div class="social_status float_right"></div>
						</div>
						<div class="action_buttons">
							<a class="feed_interest" href="#"><img src="images/interests/starwood.png" /></a>
							<a class="add_button_sm" href="#"></a>
							<a class="write_button_sm" href="#"></a>
							<a class="talk_button_sm" href="#"></a>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>



