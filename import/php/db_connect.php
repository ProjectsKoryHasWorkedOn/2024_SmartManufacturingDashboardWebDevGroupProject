<?php
require_once($DBconfigurationFilePath);
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
	$errorAdditionalInformation = "Connection to MySQL failed: " . $mysqli->connect_error;
	throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
	exit();
}