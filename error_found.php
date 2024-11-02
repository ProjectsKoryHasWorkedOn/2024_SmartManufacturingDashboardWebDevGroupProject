<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
if (isset($_GET['error_message'])) {
    $error_message = $_GET['error_message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <script src="import/js/ms.js"></script>
    <script src="import/js/videoPlayer.js"></script>
    <script src="import/js/randomColorGenerator.js"></script>
    <title>Errors encountered</title>
</head>
<body id="error_page_body">
    <h1 id="problem_text">You've encountered a website breaking problem! <br>
        Contact the developer for a bug fix. Mention the bug description.
    </h1>
    <p id="bug_description"><?php
    if (isset($error_message)) {
        echo $error_message;
    }
    ?></p>
    <video id="error_page_video" muted loop>
        <source src="import/video/bat_dance.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <script>
        function keepChangingColor(seconds) {
            const problemText = document.getElementById("problem_text");
            setTimeout(() => {
                document.body.style.backgroundColor = generateRandomColor();
                problemText.style.color = generateRandomColor();
                keepChangingColor(seconds);
            }, returnMillisecondsFromSeconds(seconds));
        }
        window.onload = function () {
            const videoplayer = new VideoPlayer();
            videoplayer.setVideoSource("error_page_video");
            videoplayer.play();
            videoplayer.unmuteAfterXSeconds(3);
            keepChangingColor(1);
        };
    </script>
</body>
</html>