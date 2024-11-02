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
        isset($_POST['paid_for_working']) && $_POST['paid_for_working'] == 'on'
    ) {
        // Checkbox value is true if it's set
        $paid_for_working = isset($_POST['paid_for_working']);
        if ($paid_for_working) {
            // This method doesn't support multiple sheets
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"employee_payments_for_time_worked_data.xls\"");
            // Output what this is
            echo implode("\t", ["--- Exported data ---"]) . "\n\n";
            echo implode("\t", ["Factory employee payments table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Payment ID",
                "Payment employee ID",
                "Payment date period end",
                "Payment date period start",
                "Payment amount"
            ];
            echo implode("\t", $tableColumns) . "\n";
            // Output data within each table in each row of the Excel file
            $query = $mysqli->prepare("SELECT * FROM factory_employee_payments
        WHERE payment_date_period_start >= ? AND payment_date_period_end <= ?");
            $query->bind_param("ss", $start_date, $end_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No employee pay data found\n";
            }
            echo "\n";
            echo implode("\t", ["Factory employee time period worked table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Working time period ID",
                "Working time period employee ID",
                "Employee shift ID",
                "Employee clock in date",
                "Employee clock in time",
                "Employee clock off date",
                "Employee clock off time"
            ];
            echo implode("\t", $tableColumns) . "\n";
            $query = $mysqli->prepare("SELECT * FROM factory_employee_time_period_worked
            WHERE employee_clock_in_date >= ? 
            AND (
                (employee_clock_off_date <= ? AND employee_clock_off_date IS NOT NULL)
                OR (employee_clock_off_date IS NULL AND employee_clock_in_date = ?)
            );");

            date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
            $current_date = date('Y-m-d');

            $query->bind_param("sss", $start_date, $end_date, $current_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No employee standard working hours data found\n";
            }
            echo "\n";
            echo implode("\t", ["Factory employee breaks taken table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Break period ID",
                "Break taker employee ID",
                "Break start date",
                "Break start time",
                "Break finish date",
                "Break finish time",
                "Break type"
            ];
            echo implode("\t", $tableColumns) . "\n";
            $query = $mysqli->prepare("SELECT * FROM factory_employee_breaks_taken
        WHERE break_start_date >= ? AND break_finish_date <= ?");
            $query->bind_param("ss", $start_date, $end_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No employee breaks data data found\n";
            }
            echo "\n";
            echo implode("\t", ["Factory employee overtime done table:"]) . "\n";
            // Output names of the columns of the Excel file
            $tableColumns = [
                "Overtime period ID",
                "Overtime employee ID",
                "Overtime start date",
                "Overtime start time",
                "Overtime finish date",
                "Overtime finish time"
            ];
            echo implode("\t", $tableColumns) . "\n";
            $query = $mysqli->prepare("SELECT * FROM factory_employee_overtime_worked
        WHERE overtime_start_date >= ? AND overtime_finish_date <= ?");
            $query->bind_param("ss", $start_date, $end_date);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo implode("\t", $row) . "\n";
                }
            } else {
                echo "No employee overtime data found\n";
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