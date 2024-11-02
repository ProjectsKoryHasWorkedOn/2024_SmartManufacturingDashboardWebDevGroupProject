<?php
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
?>
<?php
setSessionLifetime();
session_start();
?>
<?php
require_once($setIconFilePath);
changeIcon($companyLogoIconFilePath);
?>
<!-- Landing page  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Landing page</title>
</head>
<body>
    <!-- Assumed the site is used by internal employees -->
    <!-- So landing page consists of (shows) login page -->
    <?php
    include_once "login.php";
    ?>
</body>
</html>