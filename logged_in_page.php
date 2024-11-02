<!-- Logged in page  -->
<!--
Should redirect user to dashboard after X seconds
-->
<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
include_once($redirectUsersFilePath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once($headFilePath); ?>
  <title>Logged in page</title>
</head>
<body>
  <h1><?php echo $_SESSION['employee_first_name'] . " " . $_SESSION['employee_last_name']; ?> has logged in at the branch in <?php echo $_SESSION['branch_city']; ?> on <?php echo $_SESSION['user_login_date']; ?> at <?php echo $_SESSION['user_login_time']; ?></h1>
  <?php
  include_once($timeLoggedInPageFilePath);
  ?>
</body>
</html>