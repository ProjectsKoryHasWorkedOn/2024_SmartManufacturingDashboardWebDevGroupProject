<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($errorThrowerFilePath);
// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $machine_status_id = intval($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Individual machine page</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <script>
            function returnToMachineStatusPage() {
                window.location.href = 'machine_status.php';
            }
        </script>
        <button class="button" id="return_to_machine_status_page_button"
            onclick="returnToMachineStatusPage();">Return</button>
        <?php
        $sql_query_for_machines_table = "SELECT * FROM factory_machine_statuses
    WHERE 
        machine_status_id = '" . $machine_status_id . "'";
        $result_of_query_for_machines_table = $mysqli->query($sql_query_for_machines_table);
        if (!$result_of_query_for_machines_table) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        $row_of_machines_table = $result_of_query_for_machines_table->fetch_assoc();
        $result_of_query_for_machines_table->free();
        $machine_id = $row_of_machines_table['machine_id'];
        $sql_query_for_machine_types_table = "SELECT fmt.* 
        FROM factory_machine_types fmt
        JOIN factory_machines fm ON fmt.machine_type_id = fm.machine_type_id
        WHERE fm.machine_id = '" . $machine_id . "'";
        $result_of_query_for_machine_types_table = $mysqli->query($sql_query_for_machine_types_table);
        if (!$result_of_query_for_machine_types_table) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        $row_of_machine_types_table = $result_of_query_for_machine_types_table->fetch_assoc();
        $result_of_query_for_machine_types_table->free();
        /* Get all the values */
        $machine_name = $row_of_machine_types_table['machine_name'];
        $machine_timestamp = $row_of_machines_table['timestamp'];
        $machine_temperature = $row_of_machines_table['temperature'];
        $machine_humidity = $row_of_machines_table['humidity'];
        $machine_vibration = $row_of_machines_table['vibration'];
        $machine_error_code = $row_of_machines_table['error_code'];
        $machine_pressure = $row_of_machines_table['pressure'];
        $machine_operational_status = $row_of_machines_table['operational_status'];
        $machine_speed = $row_of_machines_table['speed'];
        $machine_power_consumption = $row_of_machines_table['power_consumption'];
        $machine_production_count = $row_of_machines_table['production_count'];
        $machine_maintenance_log = $row_of_machines_table['maintenance_log'];
        /* Value changing */
        if ($machine_error_code == "") {
            $machine_error_code = 'N/A';
        }
        if ($machine_maintenance_log == "") {
            $machine_maintenance_log = 'N/A';
        }
        /* Checking for abnormal values */
        /* If values aren't abnormal, fine */
        $machine_temperature_class = $machine_temperature > 40 ? 'bad_news_background' : 'good_news_background';
        $machine_humidity_class = 'good_news_background';
        $machine_vibration_class = 'good_news_background';
        $machine_error_code_class = $machine_error_code != 'N/A' ? 'bad_news_background' : 'good_news_background';
        $machine_pressure_class = 'good_news_background';
        switch ($machine_operational_status) {
            case 'active':
                $machine_operational_status_class = 'good_news_background';
                break;
            case 'maintenance':
                $machine_operational_status_class = 'bad_news_background';
                break;
            case 'idle';
                $machine_operational_status_class = 'neutral_news_background';
                break;
        }
        $machine_speed_class = 'good_news_background';
        $machine_power_consumption_class = 'good_news_background';
        $machine_production_count_class = 'good_news_background';
        $machine_maintenance_log_class = $machine_maintenance_log != 'N/A' ? 'bad_news_background' : 'good_news_background';
        /* Print out the values */
        echo '<h1>' . $machine_name . ' ' . '[' . 'Machine ID #' . $machine_id . ']' . '</h1>';
        echo '<p>Recordings on: ' . '<span class="numbers_font">' . $machine_timestamp . '</span></p>';
        echo '<p>Status ID: ' . '<span class="numbers_font">' . $machine_status_id . '</span></p>';
        echo '<div id="machine_information_container">';
        echo '<div class="machine_information ' . $machine_temperature_class . '">';
        echo '<p class="information_header">Temperature</p>';
        echo '<p class="information_value numbers_font">' . $machine_temperature . ' &#8451;' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_humidity_class . '">';
        echo '<p class="information_header">Humidity</p>';
        echo '<p class="information_value numbers_font">' . $machine_humidity . ' &#37;' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_vibration_class . '">';
        echo '<p class="information_header">Vibration</p>';
        echo '<p class="information_value numbers_font">' . $machine_vibration . ' g' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_error_code_class . '">';
        echo '<p class="information_header">Error</p>';
        echo '<p class="information_value">' . $machine_error_code . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_pressure_class . '">';
        echo '<p class="information_header">Pressure</p>';
        echo '<p class="information_value numbers_font">' . $machine_pressure . ' psi' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_operational_status_class . '">';
        echo '<p class="information_header">Operational status</p>';
        echo '<p class="information_value">' . $machine_operational_status . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_speed_class . '">';
        echo '<p class="information_header">Speed</p>';
        echo '<p class="information_value numbers_font">' . $machine_speed . ' m/s' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_power_consumption_class . '">';
        echo '<p class="information_header">Power consumption</p>';
        echo '<p class="information_value numbers_font">' . $machine_power_consumption . ' W' . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_production_count_class . '">';
        echo '<p class="information_header">Production count</p>';
        echo '<p class="information_value numbers_font">' . $machine_production_count . '</p>';
        echo '</div>';
        echo '<div class="machine_information ' . $machine_maintenance_log_class . '">';
        echo '<p class="information_header">Maintenance log</p>';
        echo '<p class="information_value">' . $machine_maintenance_log . '</p>';
        echo '</div>';
        echo '</div>';
        ?>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>