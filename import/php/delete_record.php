<?php
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($checkInputPHPFilePath);
require_once($errorThrowerFilePath);
require_once($validateInputFilePath);
function deleteMachineType($mysqli, $redirectPage, $defaultMessage, $machine_type_id, &$current_index_machine_type, $total_machine_types)
{
    $sql_delete = "DELETE FROM factory_machine_types WHERE machine_type_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql_delete);
    mysqli_stmt_bind_param($stmt, 'i', $machine_type_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_machine_type >= $total_machine_types - 1) {
        $current_index_machine_type--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}



function deleteGrievance($mysqli, $redirectPage, $defaultMessage, $grievance_id, &$current_index_grievance, $total_grievances) {

    $sql_delete = "DELETE FROM factory_employee_grievances WHERE grievance_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql_delete);
    
    mysqli_stmt_bind_param($stmt, 'i', $grievance_id);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        if ($current_index_grievance >= $total_grievances - 1) {
            $current_index_grievance--;
        }
        
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
    
    header('Location: ' . $redirectPage); 
}


function deleteMachine($mysqli, $redirectPage, $defaultMessage, $machine_id, &$current_index_machine, $total_machines)
{
    $sql_delete = "DELETE FROM factory_machines WHERE machine_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql_delete);
    mysqli_stmt_bind_param($stmt, 'i', $machine_id);
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($current_index_machine >= $total_machines - 1) {
        $current_index_machine--;
    }

    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}



function deleteBranch($mysqli, $redirectPage, $defaultMessage, $branch_id, &$current_index_branch, $total_branches)
{
    $sql = "DELETE FROM factory_branches WHERE branch_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $branch_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_branch == $total_branches - 1) {
        $current_index_branch--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteShift($mysqli, $redirectPage, $defaultMessage, $shift_id, &$current_index_shift, $total_shifts)
{
    $sql = "DELETE FROM factory_shifts WHERE shift_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $shift_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_shift == $total_shifts - 1) {
        $current_index_shift--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteEmployee($mysqli, $redirectPage, $defaultMessage, $employee_id, &$current_index_employee, $total_employees)
{
    $sql = "DELETE FROM factory_employees WHERE employee_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $employee_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_employee == $total_employees - 1) {
        $current_index_employee--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteStock($mysqli, $redirectPage, $defaultMessage, $stock_id, &$current_index_stock, $total_inventory)
{
    $sql = "DELETE FROM factory_inventory WHERE stock_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $stock_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_stock == $total_inventory - 1) {
        $current_index_stock--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteMessage($mysqli, $redirectPage, $defaultMessage, $message_id, &$current_index_message, $total_messages)
{
    $sql = "DELETE FROM factory_employee_messages WHERE message_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $message_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_message == $total_messages - 1) {
        $current_index_message--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deletePayment($mysqli, $redirectPage, $defaultMessage, $payment_id, &$current_index_payment, $total_payments)
{
    $sql = "DELETE FROM factory_employee_payments WHERE payment_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $payment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_payment == $total_payments - 1) {
        $current_index_payment--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteWorkPeriod($mysqli, $redirectPage, $defaultMessage, $work_period_id, &$current_index_work_period, $total_work_periods)
{
    $sql = "DELETE FROM factory_employee_time_period_worked WHERE work_period_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $work_period_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_work_period == $total_work_periods - 1) {
        $current_index_work_period--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteBreak($mysqli, $redirectPage, $defaultMessage, $break_id, &$current_index_break, $total_breaks)
{
    $sql = "DELETE FROM factory_employee_breaks_taken WHERE break_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $break_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_break == $total_breaks - 1) {
        $current_index_break--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteOvertime($mysqli, $redirectPage, $defaultMessage, $overtime_id, &$current_index_overtime, $total_overtime)
{
    $sql = "DELETE FROM factory_employee_overtime_worked WHERE overtime_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $overtime_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_overtime == $total_overtime - 1) {
        $current_index_overtime--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteSafetyIncident($mysqli, $redirectPage, $defaultMessage, $safety_incident_id, &$current_index_safety_incident, $total_safety_incidents)
{
    $sql = "DELETE FROM factory_safety_incidents WHERE safety_incident_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $safety_incident_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_safety_incident == $total_safety_incidents - 1) {
        $current_index_safety_incident--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteJob($mysqli, $redirectPage, $defaultMessage, $job_id, &$current_index_job, $total_jobs)
{
    $sql = "DELETE FROM factory_jobs WHERE job_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $job_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_job == $total_jobs - 1) {
        $current_index_job--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteMachineStatus($mysqli, $redirectPage, $defaultMessage, $machine_status_id, &$current_index_machine_status, $total_machine_statuses)
{
    $sql = "DELETE FROM factory_machine_statuses WHERE machine_status_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $machine_status_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_machine_status == $total_machine_statuses - 1) {
        $current_index_machine_status--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}
function deleteUserAccount($mysqli, $redirectPage, $defaultMessage, $user_account_id, &$current_index_user_account, $total_user_accounts)
{
    $sql = "DELETE FROM factory_user_accounts WHERE user_account_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_account_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($current_index_user_account == $total_user_accounts - 1) {
        $current_index_user_account--;
    }
    $_SESSION['message'] = $defaultMessage; 
    header('Location: ' . $redirectPage); 
}