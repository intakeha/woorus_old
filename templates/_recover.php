<div id="forgot_password">
    <form id="recover_form" action="../actions/savePassword.php" method="POST">
	    <div>
            <ul>
            <li class="recover_title">Please enter a new password:</li>
            <li><label>New Password</label><input class="text_form" id="new_password" type="password" name="new_password" maxlength="20"></li>
            <li><label>Confirm Password</label><input class="text_form" id="confirm_password" type="password" name="confirm_password" maxlength="20"></li>
            </ul>
            <input id="save_password" class="buttons" type="submit" value="Save"><input class="buttons" id="cancel_button" type="button" value="Cancel" onclick="location.href='index.php'" /><br />
        </div>
        <div id="recover_error" class="error_text"></div>
    </form>
</div>