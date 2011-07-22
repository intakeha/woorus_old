<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<div id="iprofile">
	<div id="profile">
		<div id="profile_frame"></div><div id="profile_pic"><img src="images/global/user_pic.png"></div>
        <div id="profile_name"><div id="profile_online_status" class="online_status float_right"></div><span>Jessica</span><br />Palo Alto, CA | United States</div>
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
   	<div id="updates_left" class="pagination_home" style="display: none;"><a class="arrows pagination_left" href="#"></a></div>
   	<div id="updates">
    	<p>Your Woorus Activities This Week:</p>
	
	<div id="first_update"><a href="#"><p>115</p>Missed Calls</a>
		<ul>
			  <li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
			<li><img src="images/users/james.png"/></li>
		</ul>
        </div>
        <div><a href="#"><p>12</p>Added You to Contacts</a>
        	<ul>
               	<li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
            </ul>
        </div>
        <div><a href="#"><p>173</p>New Interests of Contacts</a>
        	<ul>
				<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
				<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
            	<li><img src="images/interests/41_1306185339.jpg"/></li>
            </ul>
        </div>
        <div><a href="#">People interested in <p>Tennis</p></a>
        	<ul>
               	<li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
                <li><img src="images/users/james.png"/></li>
            </ul>
		</div>
    </div>
   	<div id="updates_right" class="pagination_home" style="display: none;"><a class="arrows pagination_right" href="#"></a></div>
</div>

<script type="text/javascript">
	$('#profile_frame').mouseover(function() {
		$(this).css('background-position','0px -283px');
	});
	
	$('#profile_frame').mouseout(function() {
		$(this).css('background-position','0px 0px');
	});
	
	$('#profile_frame').click(function(){
		hideProfile();
	});
	
	function hideProfile(){
		$('#profile').hide();
		$('#profile_social_status').hide();
		$('#updates').hide();
	}
</script>
