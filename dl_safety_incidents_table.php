<?php
include_once("php_resource_paths.php");
include_once($DBconnectionFilePath);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['start_date']) && $_POST['start_date'] != '') {
        $start_date = $_POST['start_date'];
    } else {
        $start_date = '1000-01-01';
    }
    if (isset($_POST['end_date']) && $_POST['end_date'] != '') {
        $end_date = $_POST['end_date'];
    } else {
        date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
        $end_date = date('Y-m-d');
    }
    // Proceed if one of the checkboxes is set
    if (
        isset($_POST['safety_incidents']) && $_POST['safety_incidents'] == 'on'
    ) {
        // Checkbox value is true if it's set
        $safety_incidents = isset($_POST['safety_incidents']);
        if ($safety_incidents) {
            // This method doesn't support multiple sheets
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"safety_incidents_data.xls\"");
            // Output what this is
            echo implode("\t", ["--- Exported data ---"]) . "\n\n";
            echo implode("\t", ["Factory employee safety incidents table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Incident ID",
                "Employee ID",
                "Incident date",
                "Description",
                "Outcome"
            ];
            echo implode("\t", $tableColumns) . "\n";
            // Output data within each table in each row of the Excel file
            $query = $mysqli->prepare("SELECT * FROM factory_safety_incidents WHERE incident_date BETWEEN ? AND ?");
            $query->bind_param("ss", $start_date, $end_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No safety incidents found\n";
            }
            exit;
        }
    } else {
        header("Location: report_generator.php");
        exit;
    }
} else {
    header("Location: report_generator.php");
    exit;
}
