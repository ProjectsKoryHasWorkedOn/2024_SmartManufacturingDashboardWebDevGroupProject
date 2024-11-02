<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
include_once($employeeNamesFilePath);
?>
<?php
$employee_hourly_rate = 0;
$employee_salary = 0;
$posted = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_to_check_pay_for_id = $_POST['employee_to_check_pay_for'];
    $start_period = $_POST['start_period'];
    $end_period = $_POST['end_period'];

    if(!empty($start_date)){
        $start_date = checkDateValid($start_date, "payroll.php");
    }

    if(!empty($end_date)){
        $end_date = checkDateValid($end_date, "payroll.php");

    }

    if (!empty($start_period) && !empty($end_period)) {
        $payPeriod = $start_period . ' to ' . $end_period;
    } elseif (!empty($start_period)) {
        $payPeriod = $start_period . ' to End';
    } elseif (!empty($end_period)) {
        $payPeriod = 'Beginning to ' . $end_period;
    } else {
        $payPeriod = 'Beginning of time until now';
    }
    $_SESSION['pdf_pay_period'] = $payPeriod;
    $sql = "SELECT employee_first_name, employee_last_name FROM factory_employees WHERE employee_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $employee_to_check_pay_for_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $employee_name = htmlspecialchars($row['employee_first_name'] . ' ' . $row['employee_last_name']);
    $_SESSION['pdf_employee_name'] = $employee_name;
    $sql = "SELECT employee_salary, employee_hourly_rate
            FROM `factory_employees` 
            WHERE `employee_id` = ? 
            GROUP BY `employee_id`";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $employee_to_check_pay_for_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $employee_salary = $row['employee_salary'];
    $employee_hourly_rate = $row['employee_hourly_rate'];
    $_SESSION['pdf_employee_salary'] = $employee_salary;
    $_SESSION['pdf_employee_hourly_rate'] = $employee_hourly_rate;
    $stmt->close();
    date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
    if (empty($start_period)) {
        $sql_query_for_the_earliest_date = "SELECT MIN(payment_date_period_start) FROM factory_employee_payments WHERE payment_employee_id = ?";
        $stmt = $mysqli->prepare($sql_query_for_the_earliest_date);
        $stmt->bind_param("i", $employee_to_check_pay_for_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        if (isset($row[0])) {
            $start_period = $row[0];
        } else {
            $start_period = date('Y-m-d');
        }
        $stmt->close();
    }
    if (empty($end_period)) {
        $end_period = date('Y-m-d');
    }
    $sql = "SELECT SUM(`payment_amount`)
            FROM `factory_employee_payments` 
            WHERE `payment_employee_id` = ? 
            AND `payment_date_period_start` BETWEEN ? AND ? 
            GROUP BY `payment_employee_id`";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iss", $employee_to_check_pay_for_id, $start_period, $end_period);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    if (isset($row[0])) {
        $totalAmountEarned = $row[0];
    } else {
        $totalAmountEarned = 0;
    }
    $_SESSION['pdf_total_amount_earned'] = $totalAmountEarned;
    $stmt->close();
    $totalMinutesWorked = 0;
    $totalOvertimeMinutesWorked = 0;
    $totalMinutesNotWorked = 0;
    $sql_query_for_time_worked = "SELECT SUM(TIMESTAMPDIFF(MINUTE, 
                CONCAT(employee_clock_in_date, ' ', employee_clock_in_time), 
                CONCAT(employee_clock_off_date, ' ', employee_clock_off_time)))
            FROM `factory_employee_time_period_worked` 
            WHERE `working_time_period_employee_id` = ? 
            AND `employee_clock_in_date` BETWEEN ? AND ? 
            GROUP BY `working_time_period_employee_id`";
    $stmt = $mysqli->prepare($sql_query_for_time_worked);
    $stmt->bind_param("iss", $employee_to_check_pay_for_id, $start_period, $end_period);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    if (isset($row[0])) {
        $totalMinutesWorked += $row[0];
    } else {
        $totalMinutesWorked += 0;
    }
    $stmt->close();
    $sql_query_for_overtime_worked = "SELECT SUM(TIMESTAMPDIFF(MINUTE, 
            CONCAT(overtime_start_date, ' ', overtime_start_time), 
            CONCAT(overtime_finish_date, ' ', overtime_finish_time)))
        FROM `factory_employee_overtime_worked` 
        WHERE `overtime_employee_id` = ? 
        AND `overtime_start_date` BETWEEN ? AND ? 
        GROUP BY `overtime_employee_id`";
    $stmt = $mysqli->prepare($sql_query_for_overtime_worked);
    $stmt->bind_param("iss", $employee_to_check_pay_for_id, $start_period, $end_period);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    if (isset($row[0])) {
        $totalMinutesWorked += $row[0];
        $totalOvertimeMinutesWorked += $row[0];
    } else {
        $totalMinutesWorked += 0;
        $totalOvertimeMinutesWorked += 0;
    }
    $stmt->close();
    $sql_query_for_time_spent_on_break = "SELECT SUM(TIMESTAMPDIFF(MINUTE, 
        CONCAT(break_start_date, ' ', break_start_time), 
        CONCAT(break_finish_date, ' ', break_finish_time)))
    FROM `factory_employee_breaks_taken` 
    WHERE `break_taker_employee_id` = ? 
    AND `break_start_date` BETWEEN ? AND ? 
    GROUP BY `break_taker_employee_id`";
    $stmt = $mysqli->prepare($sql_query_for_time_spent_on_break);
    $stmt->bind_param("iss", $employee_to_check_pay_for_id, $start_period, $end_period);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    if (isset($row[0])) {
        $totalMinutesNotWorked += $row[0];
    } else {
        $totalMinutesNotWorked += 0;
    }
    $stmt->close();
    $posted = true;
}
?>
<!-- Payroll page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Payroll</title>
    <script>
        function downloadPDF() {
            window.location.href = 'pdf_file.php';
        }
    </script>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Payroll</h1>
        <h2>Filter</h2>
        <form action="" method="post">
            <label for="employee_to_check_pay_for">Employee:</label><br>
            <select class="input_field input_class_select_field" name="employee_to_check_pay_for" required>
                <option value="" disabled selected>Select a employee to check the pay for</option>
                <?php
                echo "<optgroup label='Admin staff'>";
                foreach ($admin_staff as $employee) {
                    echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . '</option>';
                }
                echo "</optgroup>";
                echo "<optgroup label='Maintenance workers'>";
                foreach ($maintenance_workers as $employee) {
                    echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . '</option>';
                }
                echo "</optgroup>";
                echo "<optgroup label='Factory managers'>";
                foreach ($factory_managers as $employee) {
                    echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . '</option>';
                }
                echo "</optgroup>";
                echo "<optgroup label='Internal auditors'>";
                foreach ($internal_auditors as $employee) {
                    echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . '</option>';
                }
                echo "</optgroup>";
                echo "<optgroup label='Production operators'>";
                foreach ($production_operators as $employee) {
                    echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . '</option>';
                }
                echo "</optgroup>";
                ?>
            </select><br>
            <label for="start_period">Start period:</label>
            <input type="date" class="input_field" name="start_period"><br>
            <label for="end_period">End period:</label>
            <input type="date" class="input_field" name="end_period"><br>
            <input class="button smaller_button" type="submit" value="Submit">
        </form>
        <?php
        if ($posted == true) {
            echo '<h2>Employee payment details</h2>';
            echo '<h3>Summary</h3>';
            echo '<h4>Employee name</h4>';
            if (!empty($employee_to_check_pay_for_id)) {
                echo $employee_name;
            } else {
                echo 'N/A';
            }
            echo '<h4>Period</h4>';
            if (!empty($payPeriod) && !is_null($payPeriod)) {
                echo '<p>' . $payPeriod . '</p>';
            } else {
                echo 'N/A';
            }
            echo '<h4>Hours worked</h4>';
            if (!empty($totalMinutesWorked)) {
                $totalHoursWorked = $totalMinutesWorked / 60;
                $totalHoursNotWorked = 0;
                if (!empty($totalMinutesNotWorked)) {
                    $totalHoursNotWorked = $totalMinutesNotWorked / 60;
                }
                $totalHoursWorked = $totalHoursWorked - $totalHoursNotWorked;
                $_SESSION['pdf_total_hours_worked'] = $totalHoursWorked;
                $_SESSION['pdf_total_hours_spent_on_break'] = $totalHoursNotWorked;
                echo '<p>' . $totalHoursWorked . ' hours' . '</p>';
                if (!empty($totalOvertimeMinutesWorked)) {
                    $totalOvertimeHoursWorked = $totalOvertimeMinutesWorked / 60;
                    $_SESSION['pdf_total_overtime_hours'] = $totalOvertimeHoursWorked;
                    echo '<p>(Of which ' . $totalOvertimeHoursWorked . ' hours are from overtime</p>';
                }
            } else {
                echo '<p>N/A</p>';
            }
            if (!empty($totalHoursNotWorked)) {
                echo "<h4>Hours spent on break</h4>";
                echo '<p>' . $totalHoursNotWorked . ' hours' . '</p>';
            }
            if (!is_null($employee_hourly_rate) && $employee_hourly_rate != 0) {
                echo "<h4>Hourly rate</h4>";
                echo '$ ' . number_format($employee_hourly_rate, 2);
            }
            if (!is_null($employee_salary) && $employee_hourly_rate != 0) {
                echo "<h4>Salary</h4>";
                echo '$ ' . number_format($employee_salary, 2);
            }
            echo '<h4>Total amount earned over this time period</h4>';
            if (!empty($totalAmountEarned)) {
                echo '<p>' . '$ ' . number_format($totalAmountEarned, 2) . '</p>';
            } else {
                echo '<p>N/A</p>';
            }
            echo '<h2>Produce PDF</h2>';
            echo '<button class="button" onclick="downloadPDF()">Download PDF</button>';
        }
        ?>
        <?php
        include_once($footerFilePath);
        ?>
</body>
</html>