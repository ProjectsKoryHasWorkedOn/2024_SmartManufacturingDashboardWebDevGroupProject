<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($errorThrowerFilePath);
require_once($sortColumnFilePath);
?>
<?php
$sql_query_for_production_operators = "SELECT employee_id, employee_first_name, employee_last_name FROM factory_employees WHERE employee_role = 'production operator';";
$result_of_sql_query_for_production_operators = $mysqli->query($sql_query_for_production_operators);
if (!$result_of_sql_query_for_production_operators) {
    throwAnError(__LINE__, __FILE__, NULL);
}
$productionOperators = [];
$totalProductionOperators = 0;
while ($row_of_factory_employees_table = $result_of_sql_query_for_production_operators->fetch_assoc()) {
    $id = $row_of_factory_employees_table['employee_id'];
    $name = $row_of_factory_employees_table['employee_first_name'] . ' ' . $row_of_factory_employees_table['employee_last_name'];
    $productionOperators[$id] = $name;
    $totalProductionOperators++;
}
$sql_query_for_production_operators_that_clocked_in_today = "SELECT working_time_period_employee_id, MAX(DATE(employee_clock_in_date)) AS last_clock_in_date 
    FROM factory_employee_time_period_worked 
    WHERE DATE(employee_clock_in_date) = ? 
    GROUP BY working_time_period_employee_id";
$stmt = $mysqli->prepare($sql_query_for_production_operators_that_clocked_in_today);
date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
$currentDate = date('Y-m-d');
$stmt->bind_param("s", $currentDate);
if (!$stmt->execute()) {
    throwAnError(__LINE__, __FILE__, NULL);
}
$result_of_sql_query_for_production_operators_that_clocked_in_today = $stmt->get_result();
if (!$result_of_sql_query_for_production_operators_that_clocked_in_today) {
    throwAnError(__LINE__, __FILE__, NULL);
}
$countOfProductionOperatorsThatClockedInToday = 0;
$productionOperatorsPresent = [];
while ($row_of_clocked_in_table = $result_of_sql_query_for_production_operators_that_clocked_in_today->fetch_assoc()) {
    $employee_id = $row_of_clocked_in_table['working_time_period_employee_id'];
    $last_clock_in_date = $row_of_clocked_in_table['last_clock_in_date'];
    $sql_query_to_get_these_employees_that_clocked_in = "SELECT employee_id, employee_first_name, employee_last_name FROM factory_employees WHERE employee_id = ? AND employee_role = 'production operator';";
    $stmtfe = $mysqli->prepare($sql_query_to_get_these_employees_that_clocked_in);
    $stmtfe->bind_param("i", $employee_id);
    if (!$stmtfe->execute()) {
        throwAnError(__LINE__, __FILE__, NULL);
    }
    $result_of_sql_query_to_get_these_employees_that_clocked_in = $stmtfe->get_result();
    if (!$result_of_sql_query_for_production_operators_that_clocked_in_today) {
        throwAnError(__LINE__, __FILE__, NULL);
    }
    $countOfProductionOperatorsThatClockedInToday = $result_of_sql_query_to_get_these_employees_that_clocked_in->num_rows;
    if ($result_of_sql_query_to_get_these_employees_that_clocked_in->num_rows > 0) {
        while ($row_of_production_operators_here_table = $result_of_sql_query_to_get_these_employees_that_clocked_in->fetch_assoc()) {
            $name = $row_of_production_operators_here_table['employee_first_name'] . ' ' . $row_of_production_operators_here_table['employee_last_name'];
            $productionOperatorsPresent[$employee_id] = [
                'name' => $name,
                'last_clock_in' => $last_clock_in_date
            ];
        }
    }
    $stmtfe->close();
}
$notPresentOperators = [];
foreach ($productionOperators as $id => $name) {
    if (!isset($productionOperatorsPresent[$id])) {
        $notPresentOperators[$id] = [
            'name' => $name,
            'last_clock_in' => null
        ];
        $sql_query_last_clock_in = "SELECT MAX(employee_clock_in_date) AS last_clock_in_date FROM factory_employee_time_period_worked WHERE working_time_period_employee_id = ?;";
        $stmtLastClockIn = $mysqli->prepare($sql_query_last_clock_in);
        $stmtLastClockIn->bind_param("i", $id);
        if (!$stmtLastClockIn->execute()) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        $result_last_clock_in = $stmtLastClockIn->get_result();
        if ($result_last_clock_in->num_rows > 0) {
            $lastClockInRow = $result_last_clock_in->fetch_assoc();
            $notPresentOperators[$id]['last_clock_in'] = $lastClockInRow['last_clock_in_date'];
        }
        $stmtLastClockIn->close();
    }
}
$countOfNotPresentOperators = count($notPresentOperators);
?>
<?php
$sql_query_to_count_total_jobs = "SELECT COUNT(*) AS total_jobs
FROM factory_jobs;";
$result_total_jobs = $mysqli->query($sql_query_to_count_total_jobs);
$row = $result_total_jobs->fetch_assoc();
$total_jobs = $row['total_jobs'];
$sql_query_to_count_total_finished_jobs = "SELECT COUNT(*) AS total_finished_jobs
FROM factory_jobs
WHERE job_status = 'finished';";
$result_total_finished_jobs = $mysqli->query($sql_query_to_count_total_finished_jobs);
$row = $result_total_finished_jobs->fetch_assoc();
$total_finished_jobs = $row['total_finished_jobs'];
$sql_query_to_count_total_postponed_jobs = "SELECT COUNT(*) AS total_postponed_jobs
FROM factory_jobs
WHERE job_status = 'postponed';";
$result_total_postponed_jobs = $mysqli->query($sql_query_to_count_total_postponed_jobs);
$row = $result_total_postponed_jobs->fetch_assoc();
$total_postponed_jobs = $row['total_postponed_jobs'];
$total_incomplete_jobs = $total_jobs - $total_finished_jobs;
?>
<?php
$sql_query_for_each_job_employee_and_total_job_status_counts = "SELECT 
  job_employee_id, 
  job_status, 
  COUNT(*) AS total_jobs
FROM 
  factory_jobs
WHERE 
    job_status != 'finished'
GROUP BY 
  job_employee_id, 
  job_status;";
$result_of_sql_query_for_each_job_employee_and_total_job_status_counts = $mysqli->query($sql_query_for_each_job_employee_and_total_job_status_counts);
$employee_job_statuses = [];
while ($row_of_jobs_table = $result_of_sql_query_for_each_job_employee_and_total_job_status_counts->fetch_assoc()) {
    $employee_id = $row_of_jobs_table["job_employee_id"];
    $sql_query_for_employee_names = "SELECT employee_id, employee_first_name, employee_last_name
        FROM factory_employees
        WHERE employee_id = ?;
        ";
    $stmt = $mysqli->prepare($sql_query_for_employee_names);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result_of_employee_name_query = $stmt->get_result();
    $row_of_employees_table = $result_of_employee_name_query->fetch_assoc();
    $employee_name = $row_of_employees_table['employee_first_name'] . ' ' . $row_of_employees_table['employee_last_name'];
    if (!isset($employee_job_statuses[$employee_id])) {
        $employee_job_statuses[$employee_id] = [
            'employee_name' => $employee_name,
            'statuses' => []
        ];
    }
    $employee_job_statuses[$employee_id]['statuses'][] = [
        'job_status' => $row_of_jobs_table["job_status"],
        'total_jobs' => $row_of_jobs_table["total_jobs"]
    ];
}
?>
<?php
$employee_names = array_column($employee_job_statuses, 'employee_name');
array_multisort($employee_names, SORT_ASC, $employee_job_statuses);
?>
<!-- Factory floor attendance and productivity page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Factory floor attendance &amp; productivity</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Factory floor employee metrics</h1>
        <div id="container_for_two_column_layout">
            <div class="two_column_layout">
                <h2>Attendance</h2>
                <h3>Overview</h3>
                <?php
                $pluralForClockedInProductionOperators = '';
                if (intval($countOfProductionOperatorsThatClockedInToday) > 1) {
                    $pluralForClockedInProductionOperators = 's';
                }
                $pluralForMissingProductionOperators = '';
                if (intval($countOfNotPresentOperators) > 1) {
                    $pluralForMissingProductionOperators = 's';
                }
                echo "<p>" . intval($countOfProductionOperatorsThatClockedInToday) . ' ' . 'production operator' . $pluralForClockedInProductionOperators . ' present' . "</p>";
                echo "<p>" . intval($countOfNotPresentOperators) . ' ' . 'production operator' . $pluralForMissingProductionOperators . ' missing' . "</p>";
                ?>
                <h3>Production operators here</h3>
                <?php
                if (count($productionOperatorsPresent) === 0) {
                    echo "<p>None</p>";
                } else {
                    foreach ($productionOperatorsPresent as $presentEmployee) {
                        echo "<p>" . htmlspecialchars($presentEmployee['name']) . "</p>";
                    }
                }
                ?>
                <h3>Production operators not here</h3>


<?php
$order_by = $_GET['order_by'] ?? 'name'; 
$sort = $_GET['sort'] ?? 'ASC'; 

if ($order_by == 'name') {
    $names = array_column($notPresentOperators, 'name');
    array_multisort($names, $sort === 'ASC' ? SORT_ASC : SORT_DESC, $notPresentOperators);
} elseif ($order_by == 'last_clock_in') {
    $last_clock_in = array_column($notPresentOperators, 'last_clock_in');
    array_multisort($last_clock_in, $sort === 'ASC' ? SORT_ASC : SORT_DESC, $notPresentOperators);
}
?>


                <?php
if ($countOfNotPresentOperators == 0) {
    echo "<p>No one is missing</p>";
} else {
    echo "<table class='employee_metrics_table'>";
    echo "<tr>
            <th><a class='order_by_link' href='?order_by=name&sort=" . getCurrentOrderingOfColumn('name') . "'>Employee</a></th>
            <th><a class='order_by_link' href='?order_by=last_clock_in&sort=" . getCurrentOrderingOfColumn('last_clock_in') . "'>Last clock in</a></th>
          </tr>";
    foreach ($notPresentOperators as $operator) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($operator['name']) . "</td>";
        echo "<td><span class='numbers_font'>" . htmlspecialchars($operator['last_clock_in'] ?? 'Never') . "</span></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

            </div>
            <div class="two_column_layout">
                <h2>Productivity</h2>
                <h3>Overview</h3>
                <?php
                $sentence = '<p>' . $total_jobs . ' ' . 'total jobs' . '</p>' .
                    '<p>' . $total_finished_jobs . ' ' . 'finished jobs' . '</p>' .
                    '<p>' . $total_incomplete_jobs . ' ' . 'incomplete jobs' . '</p>' .
                    '<p>' . $total_postponed_jobs . ' ' . 'postponed jobs' . '</p>';
                echo $sentence;
                ?>
                <h3>What work still needs to be done</h3>

                <?php
$order_by = $_GET['order_by'] ?? 'employee_name'; 
$sort = $_GET['sort'] ?? 'ASC'; 

if ($order_by == 'employee_name') {
    $employee_names = array_column($employee_job_statuses, 'employee_name');
    array_multisort($employee_names, $sort === 'ASC' ? SORT_ASC : SORT_DESC, $employee_job_statuses);
} elseif ($order_by == 'job_status') {
    foreach ($employee_job_statuses as &$employee_status) {
        $statuses = array_column($employee_status['statuses'], 'job_status');
        array_multisort($statuses, $sort === 'ASC' ? SORT_ASC : SORT_DESC, $employee_status['statuses']);
    }
} elseif ($order_by == 'total_jobs') {
    foreach ($employee_job_statuses as &$employee_status) {
        $totals = array_column($employee_status['statuses'], 'total_jobs');
        array_multisort($totals, $sort === 'ASC' ? SORT_ASC : SORT_DESC, $employee_status['statuses']);
    }
}
?>

                <?php
    echo "<table class='employee_metrics_table'>";
    echo "<tr>
            <th><a class='order_by_link' href='?order_by=employee_name&sort=" . getCurrentOrderingOfColumn('employee_name') . "'>Employee</a></th>
            <th><a class='order_by_link' href='?order_by=job_status&sort=" . getCurrentOrderingOfColumn('job_status') . "'>Status</a></th>
            <th><a class='order_by_link' href='?order_by=total_jobs&sort=" . getCurrentOrderingOfColumn('total_jobs') . "'>Total</a></th>
          </tr>";
    foreach ($employee_job_statuses as $employee_status) {
        echo "<tr>";
        echo "<td rowspan='" . count($employee_status['statuses']) . "'>" . htmlspecialchars($employee_status['employee_name']) . "</td>";
        echo "<td>" . htmlspecialchars($employee_status['statuses'][0]['job_status']) . "</td>";
        echo "<td><span class='numbers_font'>" . htmlspecialchars($employee_status['statuses'][0]['total_jobs']) . "</span></td>";
        echo "</tr>";
        for ($i = 1; $i < count($employee_status['statuses']); $i++) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($employee_status['statuses'][$i]['job_status']) . "</td>";
            echo "<td><span class='numbers_font'>" . htmlspecialchars($employee_status['statuses'][$i]['total_jobs']) . "</span></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
?>

            </div>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>