<script type="text/javascript">
$(document).ready(function(){
	$.getJSON('../actions/lookupSettings.php', function(data) {
		$('#first_name').val(data.first_name);
		$('#last_name').val(data.last_name);
		$('#gender').val(data.gender);
		$('#birthday_month').val(data.birthday_month);
		$('#birthday_day').val(data.birthday_day);
		$('#birthday_year').val(data.birthday_year);
		$('#vemail').text(data.email);
		if (data.interest_notify == 1) {
			$('#interest_notify').attr('checked', true);
		} else {
			$('#interest_notify').attr('checked', false);
		}
		if (data.message_notify == 1) {
			$('#message_notify').attr('checked', true);
		} else {
			$('#message_notify').attr('checked', false);
		}
		if (data.contact_notify == 1) {
			$('#contact_notify').attr('checked', true);
		} else {
			$('#contact_notify').attr('checked', false);
		}
		if (data.missed_call_notify == 1) {
			$('#missed_call_notify').attr('checked', true);
		} else {
			$('#missed_call_notify').attr('checked', false);
		}
	});
});
</script>


<div id="change_settings">
    <form id="settings_form<?php if ($_SESSION['password_created']==0) echo "_c";?>" action="../actions/changeSettings.php" method="POST">
	    <div id="settings_column1">
            <ul>
            <li class="settings_title">About Me:</li>
            <li><label>First Name</label><input class="text_form" type="text" id="first_name" name="first_name" value="" maxlength="30"></li>
            <li><label>Last Name</label><input class="text_form" type="text" id="last_name" name="last_name" value="" maxlength="60"></li>
            <li><label>Gender</label><select name="gender" id="gender"><option value="-1"> Select </option><option value="F"> Female </option><option value="M"> Male </option></select></li>
            <li><label>Birthday</label><select name="birthday_month" id="birthday_month">
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
            <select name="birthday_day" id="birthday_day">
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
            <select name="birthday_year" id="birthday_year">
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
            <li><label>City</label><input class="text_form" type="text" id="city" name="city" maxlength="255"></li>
            <li>&nbsp;</li>
            <li class="settings_title">Change Email:<span>(optional)</span></li>
            <li><label>Contact Email</label><span id="vemail"></span></li>
            <li><label>New Contact Email</label><input class="text_form" type="text" name="new_email" maxlength="254"></li>
            <li>&nbsp;</li>
            <?php
			
			if ($_SESSION['password_created']==1) echo '
			<li class="settings_title">Change Password:<span>(optional)</span></li>
            <li><label>Old Password</label><input class="text_form" id="old_password" type="password" name="old_password" maxlength="20"></li>
            <li><label>New Password</label><input class="text_form" id="new_password" type="password" name="new_password" maxlength="20"></li>
            <li><label>Confirm Password</label><input class="text_form" type="password" name="confirm_password" maxlength="20"></li>';
			
			if ($_SESSION['password_created']==0) echo '
			<li class="settings_title">Create Password:<span>(optional)</span></li>
            <li><label>New Password</label><input class="text_form" id="new_password" type="password" name="new_password" maxlength="20"></li>
            <li><label>Confirm Password</label><input class="text_form" type="password" name="confirm_password" maxlength="20"></li>';
			
            ?>
            </ul>
        </div>
        <div id="settings_column2">
        	<ul>
            <li class="settings_title" style="margin-bottom: 10px;">Notifications:</li>
            <li><input id="interest_notify" type="checkbox" name="mail_interest" value="Y"/> Has similar interests as you</li>
            <li><input id="message_notify" type="checkbox" name="mail_message" value="Y"/> Sends you a message</li>
            <li><input id="contact_notify" type="checkbox" name="mail_contact" value="Y"/> Adds you to a contact list</li>
            <li><input id="missed_call_notify" type="checkbox" name="mail_calls" value="Y"/> Calls you while you're away</li>
            </ul>
            <input id="save_settings" class="buttons" type="submit" name="update" value="Save"><br />
            <div id="settings_error" class="error_text"></div>
        </div>
    </form>
</div>        
<div style="text-align:right;"><a id="deactivate" href="#">| deactivate account |</a></div>


