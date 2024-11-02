<?php
header("Content-type: text/css; charset=utf-8");
session_start();
require_once("../../php_resource_paths.php");
require_once($errorThrowerFilePath);
if (!isset($_SESSION['activeCountPercentage'])) {
    throwAnError(__LINE__, __FILE__, "Session variable 'activeCountPercentage' is not set");
}
if (!isset($_SESSION['finishedCountPercentage'])) {
    throwAnError(__LINE__, __FILE__, "Session variable 'finishedCountPercentage' is not set");
}
if (!isset($_SESSION['cancelledCountPercentage'])) {
    throwAnError(__LINE__, __FILE__, "Session variable 'cancelledCountPercentage' is not set.");
}
if (!isset($_SESSION['postponedCountPercentage'])) {
    throwAnError(__LINE__, __FILE__, "Session variable 'postponedCountPercentage' is not set.");
}
?>
#cancelled_jobs_rectangle{
width: <?php echo $_SESSION['cancelledCountPercentage'] . '%' ?>;
background-color: var(--light-to-dark-color);
}
#postponed_jobs_rectangle{
width: <?php echo $_SESSION['postponedCountPercentage'] . '%' ?>;
background-color: var(--neutral-news-background-color);
}
#finished_jobs_rectangle{
width: <?php echo $_SESSION['finishedCountPercentage'] . '%' ?>;
background-color: var(--good-message-background-color);
}
#active_jobs_rectangle{
width: <?php echo $_SESSION['activeCountPercentage'] . '%' ?>;
background-color: var(--bad-message-background-color);
}