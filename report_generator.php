<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
?>
<!-- Report generator page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Report generator</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Report generator</h1>
        <!-- Due to limitations of this method, wise to have a form for each table -->
        <h2>What to include in the report</h2>
        <div id="grid_container">
            <div class="box">
                <form action="dl_safety_incidents_table.php" method="post">
                    <h3>Select timeframe to incorporate data from</h3>
                    <label for="start_date">Start date:</label> <br>
                    <input type="date" class="input_field" id="start_date" name="start_date">
                    <br>
                    <label for="end_date">End date:</label> <br>
                    <input type="date" class="input_field" id="end_date" name="end_date">
                    <br><br>
                    <h3>Select data to include in the report</h3>
                    <input type="checkbox" name="safety_incidents" id="safety_incidents">
                    <label for="safety_incidents"> Safety incidents</label><br>
                    <br>
                    <input class="button" type="submit" value="Generate report">
                </form>
            </div>
            <div class="box">
                <form action="dl_grievances_table.php" method="post">
                    <h3>Select timeframe to incorporate data from</h3>
                    <label for="start_date">Start date:</label> <br>
                    <input type="date" class="input_field" id="start_date" name="start_date">
                    <br>
                    <label for="end_date">End date:</label> <br>
                    <input type="date" class="input_field" id="end_date" name="end_date">
                    <br><br>
                    <h3>Select data to include in the report</h3>
                    <input type="checkbox" name="worker_grievances" id="worker_grievances">
                    <label for="worker_grievances"> Worker grievances</label><br>
                    <br>
                    <input class="button" type="submit" value="Generate report">
                </form>
            </div>
            <div class="box">
                <form action="dl_machines_table.php" method="post">
                    <h3>Select timeframe to incorporate data from</h3>
                    <label for="start_date">Start date:</label> <br>
                    <input type="date" class="input_field" id="start_date" name="start_date">
                    <br>
                    <label for="end_date">End date:</label> <br>
                    <input type="date" class="input_field" id="end_date" name="end_date">
                    <br><br>
                    <h3>Select data to include in the report</h3>
                    <input type="checkbox" name="malfunctioning_machines" id="malfunctioning_machines">
                    <label for="malfunctioning_machines"> Machine malfunctioning incidents</label><br>
                    <br>
                    <input class="button" type="submit" value="Generate report">
                </form>
            </div>
            <div class="box">
                <form action="dl_employee_pay_and_time_tables.php" method="post">
                    <h3>Select timeframe to incorporate data from</h3>
                    <label for="start_date">Start date:</label> <br>
                    <input type="date" class="input_field" id="start_date" name="start_date">
                    <br>
                    <label for="end_date">End date:</label> <br>
                    <input type="date" class="input_field" id="end_date" name="end_date">
                    <br><br>
                    <h3>Select data to include in the report</h3>
                    <input type="checkbox" name="paid_for_working" id="paid_for_working">
                    <label for="paid_for_working"> Worker pay for time spent at work</label><br>
                    <br>
                    <input class="button" type="submit" value="Generate report">
                </form>
            </div>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>