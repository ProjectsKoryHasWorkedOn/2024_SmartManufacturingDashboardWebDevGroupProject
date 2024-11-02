<?php
/* Should be above root if this was a public website */
define("DB_SERVER", "localhost");
define("DB_NAME", "factory_db");
define("DB_USERNAME", "localuser");
$db_user_unhashed_password = "secure";
define("DB_PASSWORD", hash("sha256", $db_user_unhashed_password));