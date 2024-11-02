<script type="text/javascript" src="import/js/time.js" defer></script>
<script type="text/javascript" src="import/js/ms.js" defer></script>
<p id="logout_link_text">Logout</a></p>
<p id="time" class="numbers_font">Time</p>
<script>
  // Want to make sure it obtains connection after JS script function has loaded in
  document.addEventListener('DOMContentLoaded', (event) => {
    // Initially create connection
    let initialTimeConnection = getTime("<?php echo $currentTimePHPFilePath ?>");
    // Kill connection if log-out is clicked
    const logOutElement = document.getElementById("logout_link_text");
    logOutElement.addEventListener("click", function (event) {
      console.log("End time connection...");
      initialTimeConnection.close();
      window.location.href = 'login.php';
    });
    // Redirect page after certain amount of time
    var secondsToShowLoggedInPageFor = 5;
    var howLongToShowLoggedInPageFor = returnMillisecondsFromSeconds(secondsToShowLoggedInPageFor);
    setTimeout(function () {
      console.log("End time connection...");
      initialTimeConnection.close();
      // Show dashboard
      window.location.href = 'dashboard.php';
    }, howLongToShowLoggedInPageFor);
  });
</script>