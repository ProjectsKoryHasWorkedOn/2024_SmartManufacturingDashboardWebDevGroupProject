<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
require_once($sessionHandlingFilePath);
/* Cleanup operations */
clearSession();
header("Location: login.php");
exit();
