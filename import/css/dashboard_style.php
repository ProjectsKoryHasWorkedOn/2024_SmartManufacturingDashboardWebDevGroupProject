<?php
header("Content-type: text/css; charset=utf-8");
session_start();
require_once("../../php_resource_paths.php");
require_once($errorThrowerFilePath);
if (
    !isset($_SESSION['startingGridValue']) || !isset($_SESSION['mediaQueryOneValue']) ||
    !isset($_SESSION['mediaQueryTwoValue']) || !isset($_SESSION['mediaQueryThreeValue']) ||
    !isset($_SESSION['mediaQueryFourValue'])
) {
    throwAnError(__LINE__, __FILE__, "Session variables are not set");
}
?>
:root {
--starting-grid-value: <?php echo $_SESSION['startingGridValue']; ?>;
--media-query-1-grid-value: <?php echo $_SESSION['mediaQueryOneValue']; ?>;
--media-query-2-grid-value: <?php echo $_SESSION['mediaQueryTwoValue']; ?>;
--media-query-3-grid-value: <?php echo $_SESSION['mediaQueryThreeValue']; ?>;
--media-query-4-grid-value: <?php echo $_SESSION['mediaQueryFourValue']; ?>;
--dashboard_width-value: <?php echo $_SESSION['dashboardLinkWidthValue']; ?>;
}
.dashboard_link{
width: var(--dashboard_width-value);
}
#role_container {
display: grid;
grid-template-columns: repeat(var(--starting-grid-value), 1fr);
justify-items: center;
}
@media (max-width: 1129px) {
#role_container {
grid-template-columns: repeat(var(--media-query-1-grid-value), 1fr);
}
}
@media (max-width: 921px) {
#role_container {
grid-template-columns: repeat(var(--media-query-2-grid-value), 1fr);
}
}
@media (max-width: 671px) {
#role_container {
grid-template-columns: repeat(var(--media-query-3-grid-value), 1fr);
}
}
@media (max-width: 451px) {
#role_container {
grid-template-columns: repeat(var(--media-query-4-grid-value), 1fr);
}
}