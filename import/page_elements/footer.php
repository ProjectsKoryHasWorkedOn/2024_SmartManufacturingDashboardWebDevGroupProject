<?php
require_once("php_resource_paths.php");
include_once($settingsMenuFilePath);
?>
<script src="<?php echo $moveCurrentTimeElementPath; ?>"> </script>
<footer>
    <div id="footer_left">
        <p id="log-in_date_time_text">Logged in at <span
                class="numbers_font"><?php echo $_SESSION['user_login_time']; ?></span> <br> on <span
                class="numbers_font"><?php echo $_SESSION['user_login_date']; ?></span></p>
    </div>
    <div id="footer_middle">
        <p>© 2024 Smart Manufacturing Inc.</p>
    </div>
    <div id="footer_right">
        <button class="button smaller_button" id="site_settings_button" onclick="seeSettingsMenu();">Settings</button>
    </div>
</footer>