<?php
require_once("php_resource_paths.php");
?>
<script type="text/javascript" src="import/js/time.js" defer></script>
<script type="text/javascript" src="import/js/ms.js" defer></script>
<script type="text/javascript" src="import/js/updateProfileMenu.js" defer></script>
<div id="header_middle">
  <p id="time" class="numbers_font">Time</p>
</div>
<div id="header_right">
  <button class="button smaller_button" onclick="updateProfilePopUp();">Profile</button>
  <button class="button smaller_button" id="logout_button">Logout</button>
</div>
<?php
include_once($updateProfileFilePath);
?>
<script>
  // Want to make sure it obtains connection after JS script function has loaded in
  document.addEventListener('DOMContentLoaded', (event) => {
    let url = "<?php echo $currentTimePHPFilePath ?>";
    // Initially create connection
    let initialTimeConnection = getTime(url);
    // Kill connection if log-out is clicked
    const logOutElement = document.getElementById("logout_button");
    logOutElement.addEventListener("click", function (event) {
      console.log("End time connection...");
      initialTimeConnection.close();
      window.location.href = 'login.php';
    });
  });
</script>