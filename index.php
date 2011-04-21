<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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
   	<script type="text/javascript" src="js/woorus.js"></script>
</head>
<body>
	<div class="bg_canvas"></div>
	<div class="globalContainer" id="indexContainer">
		<div id="header">
			<a id="logo" href="#"></a>
			<div id="panel">
				<div class="login_panel">
					<form id="login_form" action="actions/login.php" method="POST">
						Email <input class="login_text" type="text" name="email"> &nbsp; 
						Password <input class="login_text" type="password" name="password"> &nbsp; 
						<input id="login_button" class="buttons" type="submit" name="login" value="Login">
					</form>
					<a class="switch_link" href="#" onClick="$('.login_panel').toggle();">Forgot your password?</a>
				</div>
				<div class="login_panel" style="display: none;">
					<form id="recover_form" action="actions/forgotPassword.php" method="POST">
						Email <input class="text_form" type="text" name="email"> &nbsp; 
						<input id="recover_button" class="buttons" type="submit" name="forgot" value="Reset Password">
					</form>
					<a class="switch_link" href="#" onClick="$('.login_panel').toggle();">Log in</a>
				</div>
				<div id="auth_error" class="error_text"></div>
			</div>
		</div>
		<div id="indexContent">
			<div id="slide_show">              
                <a href="#" class="prev arrows pagination_left"></a>
                <div class="slides_container">
                    <div title="Mosaic Wall"><img src="images/global/slideshow_mosaic.jpg" /></div>
                    <div title="Video Chats"><img src="images/global/slideshow_chat.jpg"/></div>
                    <div title="Social Status"><img src="images/global/slideshow_social.jpg" /></div>
                </div>
                <a href="#" class="next arrows pagination_right"></a>
			</div>
			<div id="sign_up">
				<div id="facebook_login">
					<div id="fb-root"></div>
					<script src="http://connect.facebook.net/en_US/all.js"></script>
					<script>
						 FB.init({appId:113603915367848, cookie:true, status:true, xfbml:true});
						 FB.Event.subscribe('auth.login', function () {window.location = "../actions/facebookSession.php";});
					</script>
					<fb:login-button perms="email, user_activities, user_birthday, user_interests, user_likes, user_education_history, user_work_history">
						 <span style="font-size:12px;">Login with Facebook</span>
					</fb:login-button><br>
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
					<li><label>City</label><input class="text_form" type="text" name="city" maxlength="255"></li>
                    <li><input id="validate_button" class="buttons" type="button" name="join" value="Join"></li>
					</ul><div id="reg_error_container"><div id="registration_error" class="error_text"></div></div>
                    <ul id="captcha" style="display: none;">
                    <li><?php require_once('actions/recaptchalib.php');
				      $publickey = "6LfgpsMSAAAAAJ53tncUn6Ue25kAIusSyYIs-bPJ"; 
				      echo recaptcha_get_html($publickey);
				      ?> </li>
					<li><input id="join_button" class="buttons" type="submit" name="join" value="Join"></li>
				    </ul><div id="reg_error_captcha"></div>
				</form>
			</div>        
		</div>
		<?php include('templates/_footer.php');?>
	</div>
</body>
</html>
