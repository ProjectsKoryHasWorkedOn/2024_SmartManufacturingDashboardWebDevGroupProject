<?php
$sql = "SELECT employee_id, employee_first_name, employee_last_name, employee_role FROM factory_employees ORDER BY employee_role";
$result = $mysqli->query($sql);
// Initialize arrays for each role
$production_operators = array();
$admin_staff = array();
$internal_auditors = array();
$factory_managers = array();
$maintenance_workers = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employeeName = $row["employee_first_name"] . ' ' . $row["employee_last_name"];
        $employeeID = $row["employee_id"];
        switch ($row['employee_role']) {
            case "production operator":
                array_push($production_operators, array($employeeID, $employeeName));
                break;
            case "factory manager":
                array_push($factory_managers, array($employeeID, $employeeName));
                break;
            case "internal auditor":
                array_push($internal_auditors, array($employeeID, $employeeName));
                break;
            case "admin staff":
                array_push($admin_staff, array($employeeID, $employeeName));
                break;
            case "maintenance worker":
                array_push($maintenance_workers, array($employeeID, $employeeName));
                break;
            default:
                break;
        }
    }
    $result->free();
}