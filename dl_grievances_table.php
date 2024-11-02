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
        isset($_POST['worker_grievances']) && $_POST['worker_grievances'] == 'on'
    ) {
        // Checkbox value is true if it's set
        $worker_grievances = isset($_POST['worker_grievances']);
        if ($worker_grievances) {
            // This method doesn't support multiple sheets
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"employee_grievances_data.xls\"");
            // Output what this is
            echo implode("\t", ["--- Exported data ---"]) . "\n\n";
            echo implode("\t", ["Factory employee grievances table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Grievance ID",
                "Grievance employee ID",
                "Grievance date",
                "Grievance description",
                "Grievance action taken"
            ];
            echo implode("\t", $tableColumns) . "\n";
            // Output data within each table in each row of the Excel file
            $query = $mysqli->prepare("SELECT * FROM factory_employee_grievances WHERE grievance_date BETWEEN ? AND ?");
            $query->bind_param("ss", $start_date, $end_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No grievances found\n";
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
