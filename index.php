<!DOCTYPE HTML>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<title>Woorus - The place to share your interests</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-language" content="en"/>
	<meta name="keywords" content="video chats">
	<meta name="description" content="Connecting people through interests">
	<link href="css/woorus.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
   	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/slides.min.jquery.js "></script>
    <script type="text/javascript" src="js/jquery.idle-timer.js"></script>
    <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
   	<script type="text/javascript" src="js/woorus.js"></script>
    <?php
		require_once('actions/facebookSession.php');
		/*require_once('actions/facebook.php');
		
		$facebook = new Facebook(array(
		  'appId'  => '113603915367848',
		  'secret' => 'ee894560c1bbdf11138848ce6a5620e3',
		));
		$user = $facebook->getUser();
		if ($user) {
		  try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me');
			if($user_profile){
				$facebook_id= $user_profile['id'];
				print('hello world'.$facebook_id);
			}
		  } catch (FacebookApiException $e) {
			echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
			$user = null;
		  }
		};*/
	
		session_start();
		if ($_SESSION['id']) header('Location: canvas.php');
	?>
</head>
<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '113603915367848', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true, // parse XFBML
	  oauth      : true
    });

    // Additional initialization code here
	FB.Event.subscribe('auth.login', function () {
		setTimeout('window.location.reload()',0);
	});
	FB.getLoginStatus(function(response) {
	  if (response.status === 'connected') {
		$('#facebook_login').hide();
	  }
	 });
  };

  // Load the facebook SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
</script>

	<div class="fluid" id="globalContainerIndex">
		<div id="header">
			<a id="logo" href="http://woorus.com"></a>
			<div id="panel">
				<div class="login_panel">
					<form id="login_form" action="actions/login.php" method="POST">
						Email <input class="login_text" id="login_email" type="text" name="email"> &nbsp; 
						Password <input class="login_text" type="password" name="password"> &nbsp; 
						<input id="login_button" class="buttons" type="submit" name="login" value="Login">
					</form>
					<div class="switch_link" id="switch_recover" onClick="$('.login_panel').toggle(); $('#auth_error').empty(); $('#recover_email').val($('#login_email').val()); $('#auth_error').removeClass('auth_error_login').addClass('auth_error_recover');">Forgot your password?</div>
				</div>
				<div class="login_panel" style="display: none;">
					<form id="recover_form" action="actions/forgotPassword.php" method="POST">
						Email <input class="text_form" id="recover_email" type="text" name="email"> &nbsp; 
						<input id="recover_button" class="buttons" type="submit" name="forgot" value="Reset Password">
					</form>
					<div class="switch_link" id="switch_login" onClick="$('.login_panel').toggle(); $('#auth_error').empty(); $('#login_email').val($('#recover_email').val()); $('#auth_error').removeClass('auth_error_recover').addClass('auth_error_login');">Log in</div>
				</div>
				<div id="auth_error" class="error_text auth_error_login"></div>
			</div>
		</div>
	</div>
    <div class="fluid">
        <div id="indexContent">
            <div id="slide_show">              
                <a href="#" class="prev arrows pagination_left" style="display: none"></a>
                <div class="slides_container">
                    <div title="Mosaic Wall"><img src="images/global/slideshow_mosaic.jpg" /></div>
                    <div title="Video Chats"><img src="images/global/slideshow_chat.jpg"/></div>
                    <div title="Social Status"><img src="images/global/slideshow_social.jpg" /></div>
                </div>
                <a href="#" class="next arrows pagination_right" style="display: none"></a>
            </div>
            <div id="sign_up">
            	<div id="invitation">
                	<p>Welcome to Woorus!</p>
                	<div id="enter_code">    
                        <div>We're building a community one by one and we can't wait for you to be a part of it.</div>
                        <div>If you've gotten an invitation from a friend, please enter the access code here:</div>
                        <form id="invitation_form" action="actions/checkAccessCode.php" method="post">
                        <ul id="invitation_code">
                            <li><input class="text_form" type="text" id="access_code" name="access_code" maxlength="8"> <input id="confirm_invite" class="buttons" type="submit" name="confirm_invite" value="Enter"></li>
                            <li><font id="invitation_error"></font></li>
                        </ul>
                        </form>
                        <span>&mdash;&mdash;&mdash;&mdash; or &mdash;&mdash;&mdash;&mdash;</span>
                        <div id="guestlist" style="display: block;">Add me to the guest list <img src="images/global/guestlist.jpg"></div>
					</div>
                    <div id="get_invite" style="display: none;">
                        <div>Need an invitation code?  Don't worry, just enter your email address below and we'll put you on our guest list!</div>
                        <form id="guest_form" action="actions/addToGuestList.php" method="post">
                        <ul>
                            <li><input class="text_form" id="guestlist_email" type="text" name="email" maxlength="254"> <input id="guestlist_add" class="buttons" type="submit" name="guestlist" value="Add to List"></li>
                            <li><font id="guestlist_error"></font></li>
                        </ul>
                        </form>    
                    </div>
                    <div id="guestlist_confirmed" style="display: none;">
                        <div>
                            <div>Thank you for your interest in Woorus. We have entered <font></font> to the guest list.</div>
                            <div>Invitations are currently being sent out upon limited availability. Please check your email periodically for an invite code. We look forward to seeing you soon!</div>
                        </div>
                    </div>
                </div>
                <div id="register" style="display:none;">
                    <div id="facebook_login">
                        <div class="fb-login-button" data-show-faces="true" data-width="250" data-max-rows="1" scope="email, user_activities, user_birthday, user_interests, user_likes, user_education_history, user_work_history">Login with Facebook</div><br>
                        <span>&mdash;&mdash;&mdash;&mdash; or &mdash;&mdash;&mdash;&mdash;</span>
                    </div>
                    <form id="registration_form" action="actions/register.php" method="POST">
                        <ul id="userInfo">
                        <li><label>First Name</label><input class="text_form" type="text" name="first_name" maxlength="30"></li>
                        <li><label>Last Name</label><input class="text_form" type="text" name="last_name" maxlength="60"></li>
                        <li><label>Email</label><input class="text_form" id="email" type="text" name="email" maxlength="254"></li>
                        <li><label>Confirm Email</label><input class="text_form" type="text" name="confirm_email" maxlength="254"></li>
                        <li><label>Password</label><input class="text_form"  type="password" name="password" maxlength="20"></li>
                        <li><label>Gender</label><select name="gender" id="gender"><option value="-1"> Select </option><option value="F"> Female </option><option value="M"> Male </option></select></li>
                        <li><label>Birthday</label><select name="birthday_month">
                            <option value="-1">Month</option>
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                        <select name="birthday_day">
                            <option value="-1">Day</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                        <select name="birthday_year">
                            <option value="-1">Year</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                            <option value="1969">1969</option>
                            <option value="1968">1968</option>
                            <option value="1967">1967</option>
                            <option value="1966">1966</option>
                            <option value="1965">1965</option>
                            <option value="1964">1964</option>
                            <option value="1963">1963</option>
                            <option value="1962">1962</option>
                            <option value="1961">1961</option>
                            <option value="1960">1960</option>
                            <option value="1959">1959</option>
                            <option value="1958">1958</option>
                            <option value="1957">1957</option>
                            <option value="1956">1956</option>
                            <option value="1955">1955</option>
                            <option value="1954">1954</option>
                            <option value="1953">1953</option>
                            <option value="1952">1952</option>
                            <option value="1951">1951</option>
                            <option value="1950">1950</option>
                            <option value="1949">1949</option>
                            <option value="1948">1948</option>
                            <option value="1947">1947</option>
                            <option value="1946">1946</option>
                            <option value="1945">1945</option>
                            <option value="1944">1944</option>
                            <option value="1943">1943</option>
                            <option value="1942">1942</option>
                            <option value="1941">1941</option>
                            <option value="1940">1940</option>
                            <option value="1939">1939</option>
                            <option value="1938">1938</option>
                            <option value="1937">1937</option>
                            <option value="1936">1936</option>
                            <option value="1935">1935</option>
                            <option value="1934">1934</option>
                            <option value="1933">1933</option>
                            <option value="1932">1932</option>
                            <option value="1931">1931</option>
                            <option value="1930">1930</option>
                            <option value="1929">1929</option>
                            <option value="1928">1928</option>
                            <option value="1927">1927</option>
                            <option value="1926">1926</option>
                            <option value="1925">1925</option>
                            <option value="1924">1924</option>
                            <option value="1923">1923</option>
                            <option value="1922">1922</option>
                            <option value="1921">1921</option>
                            <option value="1920">1920</option>
                            <option value="1919">1919</option>
                            <option value="1918">1918</option>
                            <option value="1917">1917</option>
                            <option value="1916">1916</option>
                            <option value="1915">1915</option>
                            <option value="1914">1914</option>
                            <option value="1913">1913</option>
                            <option value="1912">1912</option>
                            <option value="1911">1911</option>
                            <option value="1910">1910</option>
                            <option value="1909">1909</option>
                            <option value="1908">1908</option>
                            <option value="1907">1907</option>
                            <option value="1906">1906</option>
                            <option value="1905">1905</option>
                        </select></li>
                        <li><label>City</label><input id="city" class="text_form" type="text" name="city" maxlength="255"><input type="hidden" id="city_id" name="city_id" value="" /><input type="hidden" id="access_code_submit" name="access_code" value="" /></li>
                        <li><input id="validate_registration" class="buttons" type="submit" name="join" value="Join"></li>
                        </ul><div id="reg_error_container"><div id="registration_error" class="error_text"></div></div>
                        <ul id="captcha" style="display: none;">
                        <li><?php require_once('actions/recaptchalib.php');
                          $publickey = "6LfgpsMSAAAAAJ53tncUn6Ue25kAIusSyYIs-bPJ"; 
                          echo recaptcha_get_html($publickey);
                          ?> </li>
                        <li><input id="join_button" class="buttons" type="submit" name="join" value="Join"></li>
                        </ul><div id="reg_error_captcha" style="display: none;"></div>
                    </form>
            	</div>
            </div>        
        </div>
    </div>
    <?php include('templates/_footer.php');?>
</body>
</html>
