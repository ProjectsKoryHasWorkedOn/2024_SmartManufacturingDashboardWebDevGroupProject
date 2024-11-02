<?php
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($checkInputPHPFilePath);
require_once($errorThrowerFilePath);
require_once($validateInputFilePath);
require_once($passwordHasherFilePath);
function updateMachineType($mysqli, $redirectPage, $defaultMessage, $machine_type_id, $machine_name)
{

    $machine_type_id = sanitizeString($mysqli, $machine_type_id);
    $machine_name = sanitizeString($mysqli, $machine_name);
    $machine_type_id = checkNotBlank($machine_type_id, $redirectPage);
    $machine_name = checkNotBlank($machine_name, $redirectPage);
    if ($machine_type_id === "Missing" || $machine_name === "Missing") {
        return; 
    }
    $sql_update = "UPDATE factory_machine_types SET machine_name = ? WHERE machine_type_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql_update);
    mysqli_stmt_bind_param($stmt, 'si', $machine_name, $machine_type_id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}
function updateBranch($mysqli, $redirectPage, $defaultMessage, $branch_id, $branch_country, $branch_city, $branch_timezone, $branch_street_address)
{
   
    $branch_id = sanitizeString($mysqli, $branch_id);
    $branch_country = sanitizeString($mysqli, $branch_country);
    $branch_city = sanitizeString($mysqli, $branch_city);
    $branch_timezone = sanitizeString($mysqli, $branch_timezone);
    $branch_street_address = sanitizeString($mysqli, $branch_street_address);
    
    
    $branch_id = checkNotBlank($branch_id, $redirectPage);
    $branch_country = checkNotBlank($branch_country, $redirectPage);
    $branch_city = checkNotBlank($branch_city, $redirectPage);
    $branch_timezone = checkNotBlank($branch_timezone, $redirectPage);
    $branch_street_address = checkNotBlank($branch_street_address, $redirectPage);
    
    
    if ($branch_id === "Missing" || 
        $branch_country === "Missing" || 
        $branch_city === "Missing" || 
        $branch_timezone === "Missing" || 
        $branch_street_address === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_branches SET 
                branch_country = ?, 
                branch_city = ?, 
                branch_timezone = ?, 
                branch_street_address = ? 
            WHERE branch_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $branch_country, $branch_city, $branch_timezone, $branch_street_address, $branch_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}


function updateMachine($mysqli, $redirectPage, $defaultMessage, $machine_id, $machine_type_id, $branch_id)
{
    $machine_id = sanitizeString($mysqli, $machine_id);
    $machine_type_id = sanitizeString($mysqli, $machine_type_id);
    $branch_id = sanitizeString($mysqli, $branch_id);

    $machine_id = checkNotBlank($machine_id, $redirectPage);
    $machine_type_id = checkNotBlank($machine_type_id, $redirectPage);
    $branch_id = checkNotBlank($branch_id, $redirectPage);

    if ($machine_id === "Missing" || $machine_type_id === "Missing" || $branch_id === "Missing") {
        return; 
    }

    $sql = "UPDATE factory_machines SET machine_type_id = ?, branch_id = ? WHERE machine_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $machine_type_id, $branch_id, $machine_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }

    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}



function updateShift($mysqli, $redirectPage, $defaultMessage, $shift_id, $shift_period)
{
   
    $shift_id = sanitizeString($mysqli, $shift_id);
    $shift_period = sanitizeString($mysqli, $shift_period);
    
    
    $shift_id = checkNotBlank($shift_id, $redirectPage);
    $shift_period = checkNotBlank($shift_period, $redirectPage);
    
    
    if ($shift_id === "Missing" || $shift_period === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_shifts SET shift_period = ? WHERE shift_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $shift_period, $shift_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updatePayment($mysqli, $redirectPage, $defaultMessage, $payment_id, $payment_employee_id, $payment_amount, $payment_date_period_start, $payment_date_period_end)
{


    $payment_date_period_start = checkDateValid($payment_date_period_start, $redirectPage);
    $payment_date_period_end = checkDateValid($payment_date_period_end, $redirectPage);


    // Sanitize inputs
    $payment_id = sanitizeString($mysqli, $payment_id);
    $payment_employee_id = sanitizeString($mysqli, $payment_employee_id);
    $payment_amount = sanitizeString($mysqli, $payment_amount);
    $payment_date_period_start = sanitizeString($mysqli, $payment_date_period_start);
    $payment_date_period_end = sanitizeString($mysqli, $payment_date_period_end);
    
    // Check for blank fields
    $payment_id = checkNotBlank($payment_id, $redirectPage);
    $payment_employee_id = checkNotBlank($payment_employee_id, $redirectPage);
    $payment_amount = checkNotBlank($payment_amount, $redirectPage);
    $payment_date_period_start = checkNotBlank($payment_date_period_start, $redirectPage);
    $payment_date_period_end = checkNotBlank($payment_date_period_end, $redirectPage);
    
    // If any field is missing, return without further action
    if ($payment_id === "Missing" || 
        $payment_employee_id === "Missing" || 
        $payment_amount === "Missing" || 
        $payment_date_period_start === "Missing" || 
        $payment_date_period_end === "Missing") {
        return; 
    }

    // Prepare SQL statement for updating payment record
    $sql = "UPDATE factory_employee_payments SET 
                payment_employee_id = ?, 
                payment_amount = ?, 
                payment_date_period_start = ?, 
                payment_date_period_end = ? 
            WHERE payment_id = ?";
    
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'idsii', $payment_employee_id, $payment_amount, $payment_date_period_start, $payment_date_period_end, $payment_id);
    
    // Execute statement and check for errors
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    // Close the statement and redirect
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}


function updateFactoryEmployee($mysqli, $redirectPage, $defaultMessage, $employee_id, $employee_branch_id, $employee_first_name, $employee_last_name, $employee_email_address, $employee_role, $employee_salary, $employee_hourly_rate)
{
   
    $employee_id = sanitizeString($mysqli, $employee_id);
    $employee_branch_id = sanitizeString($mysqli, $employee_branch_id);
    $employee_first_name = sanitizeString($mysqli, $employee_first_name);
    $employee_last_name = sanitizeString($mysqli, $employee_last_name);
    $employee_email_address = sanitizeString($mysqli, $employee_email_address);
    $employee_role = sanitizeString($mysqli, $employee_role);


    $employee_salary = empty(trim($employee_salary)) ? null : sanitizeString($mysqli, $employee_salary);
    $employee_hourly_rate = empty(trim($employee_hourly_rate)) ? null : sanitizeString($mysqli, $employee_hourly_rate);

    
    $employee_id = checkNotBlank($employee_id, $redirectPage);
    $employee_branch_id = checkNotBlank($employee_branch_id, $redirectPage);
    $employee_first_name = checkNotBlank($employee_first_name, $redirectPage);
    $employee_last_name = checkNotBlank($employee_last_name, $redirectPage);
    $employee_email_address = checkNotBlank($employee_email_address, $redirectPage);
    $employee_email_address = checkEmail($employee_email_address, $redirectPage);
    
    $employee_role = checkNotBlank($employee_role, $redirectPage);
    
    $resultOfCheck = checkThatOneOfTwoInputsHasAValue($employee_salary, $employee_hourly_rate, $redirectPage);
    $employee_salary = $resultOfCheck[0];
    $employee_hourly_rate = $resultOfCheck[1];

    if (!checkIfExists($mysqli, 'factory_branches', 'branch_id', $employee_branch_id, "i")) {
        $_SESSION['message'] = "Branch ID not found";
        header('Location: ' . $redirectPage);
        exit();
    }
    
    if ($employee_id === "Missing" || 
        $employee_branch_id === "Missing" || 
        $employee_first_name === "Missing" || 
        $employee_last_name === "Missing" || 
        $employee_email_address === "Missing" || 
        $employee_role === "Missing" || 
        ($employee_salary === "Missing" && 
        $employee_hourly_rate === "Missing")
        )
        {
        return; 
    }

    $sql = "UPDATE factory_employees SET 
                employee_branch_id = ?, 
                employee_first_name = ?, 
                employee_last_name = ?, 
                employee_email_address = ?, 
                employee_role = ?,
                employee_salary = ?, 
                employee_hourly_rate = ?
            WHERE employee_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'issssssi', $employee_branch_id, $employee_first_name, $employee_last_name, $employee_email_address, $employee_role, $employee_salary, $employee_hourly_rate, $employee_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updateStock($mysqli, $redirectPage, $defaultMessage, $stock_id, $stock_branch_id, $stock_name, $stock_quantity)
{
   
    $stock_id = sanitizeString($mysqli, $stock_id);
    $stock_branch_id = sanitizeString($mysqli, $stock_branch_id);
    $stock_name = sanitizeString($mysqli, $stock_name);
    $stock_quantity = sanitizeString($mysqli, $stock_quantity);
    
    
    $stock_id = checkNotBlank($stock_id, $redirectPage);
    $stock_branch_id = checkNotBlank($stock_branch_id, $redirectPage);
    $stock_name = checkNotBlank($stock_name, $redirectPage);
    $stock_quantity = checkNotBlank($stock_quantity, $redirectPage);
    
    
    if ($stock_id === "Missing" || 
        $stock_branch_id === "Missing" || 
        $stock_name === "Missing" || 
        $stock_quantity === "Missing") {
        return; 
    }
    
    $sql = "UPDATE factory_inventory SET 
                stock_branch_id = ?, 
                stock_name = ?, 
                stock_quantity = ? 
            WHERE stock_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'isii', $stock_branch_id, $stock_name, $stock_quantity, $stock_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updateMessage($mysqli, $redirectPage, $defaultMessage, $message_id, $sender_employee_id, $recipient_employee_id, $conversation_id, $message_title, $message_body, $message_date, $message_attachment, $message_attachment_required)
{


    $message_date = checkDateValid($message_date, $redirectPage);

   
    $message_id = sanitizeString($mysqli, $message_id);
    $sender_employee_id = sanitizeString($mysqli, $sender_employee_id);
    $recipient_employee_id = sanitizeString($mysqli, $recipient_employee_id);
    $conversation_id = sanitizeString($mysqli, $conversation_id);
    $message_title = sanitizeString($mysqli, $message_title);
    $message_body = sanitizeString($mysqli, $message_body);
    $message_date = sanitizeString($mysqli, $message_date);
  
    
    $message_id = checkNotBlank($message_id, $redirectPage);
    $sender_employee_id = checkNotBlank($sender_employee_id, $redirectPage);
    $recipient_employee_id = checkNotBlank($recipient_employee_id, $redirectPage);
    $conversation_id = checkNotBlank($conversation_id, $redirectPage);
    $message_title = checkNotBlank($message_title, $redirectPage);
    $message_body = checkNotBlank($message_body, $redirectPage);
    $message_date = checkNotBlank($message_date, $redirectPage);
     $message_attachment = checkFile($message_attachment, $redirectPage, $message_attachment_required);


    
    if ($message_id === "Missing" || 
        $sender_employee_id === "Missing" || 
        $recipient_employee_id === "Missing" || 
        $conversation_id === "Missing" || 
        $message_title === "Missing" || 
        $message_body === "Missing" || 
        $message_date === "Missing" ) {
        return; 
    }

    if ($message_attachment === "Invalid") {
        return;
    }

    
    $sql = "UPDATE factory_employee_messages SET 
                sender_employee_id = ?, 
                recipient_employee_id = ?, 
                conversation_id = ?, 
                message_title = ?, 
                message_body = ?, 
                message_date = ?, 
                message_attachment = ?
            WHERE message_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);



    mysqli_stmt_bind_param($stmt, 'iiisssbi', 
    $sender_employee_id, 
    $recipient_employee_id, 
    $conversation_id, 
    $message_title, 
    $message_body, 
    $message_date, 
    $message_attachment,  
    $message_id
);


    
    if ($message_attachment) {
        $fp = fopen($message_attachment, "rb"); // Open the file in binary mode
        while (!feof($fp)) {
            $stmt->send_long_data(6, fread($fp, 8192));
        }
        fclose($fp);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updateWorkPeriod($mysqli, $redirectPage, $defaultMessage, $work_period_id, $working_time_period_employee_id, $employee_shift_id, $employee_clock_in_date, $work_period_start, $employee_clock_off_date, $work_period_end)
{

    $employee_clock_in_date = checkDateValid($employee_clock_in_date, $redirectPage);
    $employee_clock_off_date = checkDateValid($employee_clock_off_date, $redirectPage);

   
    $work_period_id = sanitizeString($mysqli, $work_period_id);
    $working_time_period_employee_id = sanitizeString($mysqli, $working_time_period_employee_id);
    $employee_shift_id = sanitizeString($mysqli, $employee_shift_id);
    $employee_clock_in_date = sanitizeString($mysqli, $employee_clock_in_date);
    $work_period_start = sanitizeString($mysqli, $work_period_start);
    $employee_clock_off_date = sanitizeString($mysqli, $employee_clock_off_date);
    $work_period_end = sanitizeString($mysqli, $work_period_end);
    
    
    $work_period_id = checkNotBlank($work_period_id, $redirectPage);
    $working_time_period_employee_id = checkNotBlank($working_time_period_employee_id, $redirectPage);
    $employee_shift_id = checkNotBlank($employee_shift_id, $redirectPage);
    $employee_clock_in_date = checkNotBlank($employee_clock_in_date, $redirectPage);
    $work_period_start = checkNotBlank($work_period_start, $redirectPage);
    $employee_clock_off_date = checkNotBlank($employee_clock_off_date, $redirectPage);
    $work_period_end = checkNotBlank($work_period_end, $redirectPage);
    
    
    if ($work_period_id === "Missing" || 
        $working_time_period_employee_id === "Missing" || 
        $employee_shift_id === "Missing" || 
        $employee_clock_in_date === "Missing" || 
        $work_period_start === "Missing" || 
        $employee_clock_off_date === "Missing" || 
        $work_period_end === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_employee_time_period_worked SET 
                working_time_period_employee_id = ?, 
                employee_shift_id = ?, 
                employee_clock_in_date = ?, 
                employee_clock_in_time = ?, 
                employee_clock_off_date = ?, 
                employee_clock_off_time = ? 
            WHERE working_time_period_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'iisissi', $working_time_period_employee_id, $employee_shift_id, $employee_clock_in_date, $work_period_start, $employee_clock_off_date, $work_period_end, $work_period_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updateBreak($mysqli, $redirectPage, $defaultMessage, $break_id, $break_taker_employee_id, $break_start_date, $break_start_time, $break_finish_date, $break_finish_time, $break_type)
{
   
    $break_start_date = checkDateValid($break_start_date, $redirectPage);
    $break_finish_date = checkDateValid($break_finish_date, $redirectPage);





    $break_id = sanitizeString($mysqli, $break_id);
    $break_taker_employee_id = sanitizeString($mysqli, $break_taker_employee_id);
    $break_start_date = sanitizeString($mysqli, $break_start_date);
    $break_start_time = sanitizeString($mysqli, $break_start_time);
    $break_finish_date = sanitizeString($mysqli, $break_finish_date);
    $break_finish_time = sanitizeString($mysqli, $break_finish_time);
    $break_type = sanitizeString($mysqli, $break_type);
    
    
    $break_id = checkNotBlank($break_id, $redirectPage);
    $break_taker_employee_id = checkNotBlank($break_taker_employee_id, $redirectPage);
    $break_start_date = checkNotBlank($break_start_date, $redirectPage);
    $break_start_time = checkNotBlank($break_start_time, $redirectPage);
    $break_finish_date = checkNotBlank($break_finish_date, $redirectPage);
    $break_finish_time = checkNotBlank($break_finish_time, $redirectPage);
    $break_type = checkNotBlank($break_type, $redirectPage);
    
    
    if ($break_id === "Missing" || 
        $break_taker_employee_id === "Missing" || 
        $break_start_date === "Missing" || 
        $break_start_time === "Missing" || 
        $break_finish_date === "Missing" || 
        $break_finish_time === "Missing" || 
        $break_type === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_employee_breaks_taken SET 
                break_taker_employee_id = ?, 
                break_start_date = ?, 
                break_start_time = ?, 
                break_finish_date = ?, 
                break_finish_time = ?, 
                break_type = ? 
            WHERE break_period_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'isssssi', $break_taker_employee_id, $break_start_date, $break_start_time, $break_finish_date, $break_finish_time, $break_type, $break_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

function updateOvertime($mysqli, $redirectPage, $defaultMessage, $overtime_id, $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time)
{
   
    $overtime_start_date = checkDateValid($overtime_start_date, $redirectPage);
    $overtime_finish_date =  checkDateValid($overtime_finish_date, $redirectPage);



    $overtime_id = sanitizeString($mysqli, $overtime_id);
    $overtime_employee_id = sanitizeString($mysqli, $overtime_employee_id);
    $overtime_start_date = sanitizeString($mysqli, $overtime_start_date);
    $overtime_start_time = sanitizeString($mysqli, $overtime_start_time);
    $overtime_finish_date = sanitizeString($mysqli, $overtime_finish_date);
    $overtime_finish_time = sanitizeString($mysqli, $overtime_finish_time);
    
    
    $overtime_id = checkNotBlank($overtime_id, $redirectPage);
    $overtime_employee_id = checkNotBlank($overtime_employee_id, $redirectPage);
    $overtime_start_date = checkNotBlank($overtime_start_date, $redirectPage);
    $overtime_start_time = checkNotBlank($overtime_start_time, $redirectPage);
    $overtime_finish_date = checkNotBlank($overtime_finish_date, $redirectPage);
    $overtime_finish_time = checkNotBlank($overtime_finish_time, $redirectPage);
    
    
    if ($overtime_id === "Missing" || 
        $overtime_employee_id === "Missing" || 
        $overtime_start_date === "Missing" || 
        $overtime_start_time === "Missing" || 
        $overtime_finish_date === "Missing" || 
        $overtime_finish_time === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_employee_overtime_worked SET 
                overtime_employee_id = ?, 
                overtime_start_date = ?, 
                overtime_start_time = ?, 
                overtime_finish_date = ?, 
                overtime_finish_time = ? 
            WHERE overtime_period_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'issssi', $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time, $overtime_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}


function updateSafetyIncident($mysqli, $redirectPage, $defaultMessage, $safety_incident_id, $incident_employee_id, $safety_incident_date, $safety_incident_description, $incident_outcome)
{
    $safety_incident_date = checkDateValid($safety_incident_date, $redirectPage);

   
    $safety_incident_id = sanitizeString($mysqli, $safety_incident_id);
    $incident_employee_id = sanitizeString($mysqli, $incident_employee_id);
    $safety_incident_date = sanitizeString($mysqli, $safety_incident_date);
    $safety_incident_description = sanitizeString($mysqli, $safety_incident_description);
    $incident_outcome = sanitizeString($mysqli, $incident_outcome);
    
    
    $safety_incident_id = checkNotBlank($safety_incident_id, $redirectPage);
    $incident_employee_id = checkNotBlank($incident_employee_id, $redirectPage);
    $safety_incident_date = checkNotBlank($safety_incident_date, $redirectPage);
    $safety_incident_description = checkNotBlank($safety_incident_description, $redirectPage);
    $incident_outcome = checkNotBlank($incident_outcome, $redirectPage);
    
    
    if ($safety_incident_id === "Missing" || 
        $incident_employee_id === "Missing" || 
        $safety_incident_date === "Missing" || 
        $safety_incident_description === "Missing" || 
        $incident_outcome === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_safety_incidents SET 
            incident_employee_id = ?, 
            incident_date = ?, 
            incident_description = ?, 
            incident_outcome = ? 
        WHERE incident_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);

    mysqli_stmt_bind_param($stmt, 'isssi', $incident_employee_id, $safety_incident_date, $safety_incident_description, $incident_outcome, $safety_incident_id);
    


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}


function updateJob($mysqli, $redirectPage, $defaultMessage, $job_id, $job_employee_id, $job_assigned_date, $job_completed_date, $job_description, $job_status, $job_task_notes, $job_priority)
{
   
    $job_id = sanitizeString($mysqli, $job_id);
    $job_employee_id = sanitizeString($mysqli, $job_employee_id);
    $job_assigned_date = sanitizeString($mysqli, $job_assigned_date);
    
    $job_completed_date = empty(trim($job_completed_date)) ? null : sanitizeString($mysqli, $job_completed_date);
    
    $job_description = sanitizeString($mysqli, $job_description);
    $job_status = sanitizeString($mysqli, $job_status);
    $job_task_notes = sanitizeString($mysqli, $job_task_notes);
    $job_priority = sanitizeString($mysqli, $job_priority);
    
    
    $job_id = checkNotBlank($job_id, $redirectPage);
    $job_employee_id = checkNotBlank($job_employee_id, $redirectPage);
    $job_assigned_date = checkNotBlank($job_assigned_date, $redirectPage);
    $job_description = checkNotBlank($job_description, $redirectPage);
    $job_status = checkNotBlank($job_status, $redirectPage);
    $job_task_notes = checkNotBlank($job_task_notes, $redirectPage);
    $job_priority = checkNotBlank($job_priority, $redirectPage);
    
    
    if ($job_id === "Missing" || 
        $job_employee_id === "Missing" || 
        $job_assigned_date === "Missing" || 
        $job_description === "Missing" || 
        $job_status === "Missing" || 
        $job_task_notes === "Missing" || 
        $job_priority === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_jobs SET 
                job_employee_id = ?, 
                job_assigned_date = ?, 
                job_completed_date = ?, 
                job_description = ?, 
                job_status = ?, 
                job_task_notes = ?, 
                job_priority = ? 
            WHERE job_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'issssssi', $job_employee_id, $job_assigned_date, $job_completed_date, $job_description, $job_status, $job_task_notes, $job_priority, $job_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}





function updateMachineStatus($mysqli, $redirectPage, $defaultMessage, $machine_status_id, $machine_id, $timestamp, $temperature, $pressure, $vibration, $humidity, $power_consumption, $operational_status, $error_code, $production_count, $maintenance_log, $speed)
{
   
    $machine_id = sanitizeString($mysqli, $machine_id);
    $timestamp = sanitizeString($mysqli, $timestamp);
    $temperature = sanitizeString($mysqli, $temperature);
    $pressure = sanitizeString($mysqli, $pressure);
    $vibration = sanitizeString($mysqli, $vibration);
    $humidity = sanitizeString($mysqli, $humidity);
    $power_consumption = sanitizeString($mysqli, $power_consumption);
    $operational_status = sanitizeString($mysqli, $operational_status);
    $production_count = sanitizeString($mysqli, $production_count);
  

    $error_code = empty(trim($error_code)) ? null : sanitizeString($mysqli, $error_code);
    $maintenance_log = empty(trim($maintenance_log)) ? null : sanitizeString($mysqli, $maintenance_log);
    $speed = empty(trim($speed)) ? null : sanitizeString($mysqli, $speed);

    
    $machine_id = checkNotBlank($machine_id, $redirectPage);
    $timestamp = checkNotBlank($timestamp, $redirectPage);
    $temperature = checkNotBlank($temperature, $redirectPage);
    $pressure = checkNotBlank($pressure, $redirectPage);
    $vibration = checkNotBlank($vibration, $redirectPage);
    $humidity = checkNotBlank($humidity, $redirectPage);
    $power_consumption = checkNotBlank($power_consumption, $redirectPage);
    $operational_status = checkNotBlank($operational_status, $redirectPage);
    $production_count = checkNotBlank($production_count, $redirectPage);
    
    if ($machine_status_id === "Missing" || 
        $machine_id === "Missing" || 
        $timestamp === "Missing" || 
        $temperature === "Missing" || 
        $pressure === "Missing" || 
        $vibration === "Missing" || 
        $humidity === "Missing" || 
        $power_consumption === "Missing" || 
        $operational_status === "Missing" || 
        $production_count === "Missing" ) {
        return; 
    }

    
    $sql = "UPDATE factory_machine_statuses SET 
                machine_id = ?, 
                timestamp = ?, 
                temperature = ?, 
                pressure = ?, 
                vibration = ?, 
                humidity = ?, 
                power_consumption = ?, 
                operational_status = ?, 
                error_code = ?, 
                production_count = ?, 
                maintenance_log = ?, 
                speed = ? 
            WHERE machine_status_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'isddddssssdsi',
        $machine_id,
        $timestamp,
        $temperature,
        $pressure,
        $vibration,
        $humidity,
        $power_consumption,
        $operational_status,
        $error_code,
        $production_count,
        $maintenance_log,
        $speed,
        $machine_status_id
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}


function updateGrievance($mysqli, $redirectPage, $defaultMessage, $grievance_id, $grievance_employee_id, $grievance_date, $grievance_description, $grievance_action_taken) {
    
    $grievance_date = checkDateValid($grievance_date, $redirectPage);



    $grievance_id = sanitizeString($mysqli, $grievance_id);
    $grievance_employee_id = sanitizeString($mysqli, $grievance_employee_id);
    $grievance_date = sanitizeString($mysqli, $grievance_date);
    $grievance_description = sanitizeString($mysqli, $grievance_description);
    $grievance_action_taken = sanitizeString($mysqli, $grievance_action_taken);
    
    $grievance_id = checkNotBlank($grievance_id, $redirectPage);
    $grievance_employee_id = checkNotBlank($grievance_employee_id, $redirectPage);
    $grievance_date = checkNotBlank($grievance_date, $redirectPage);
    $grievance_description = checkNotBlank($grievance_description, $redirectPage);
    $grievance_action_taken = checkNotBlank($grievance_action_taken, $redirectPage);
    
    if ($grievance_id === "Missing" || 
        $grievance_employee_id === "Missing" || 
        $grievance_date === "Missing" || 
        $grievance_description === "Missing" || 
        $grievance_action_taken === "Missing") {
        return; 
    }

    $sql = "UPDATE factory_employee_grievances SET 
                grievance_employee_id = ?, 
                grievance_date = ?, 
                grievance_description = ?, 
                grievance_action_taken = ? 
            WHERE grievance_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $grievance_employee_id, $grievance_date, $grievance_description, $grievance_action_taken, $grievance_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}




function updateUserAccount($mysqli, $redirectPage, $defaultMessage, $user_account_id, $user_employee_id, $user_username, $user_password, $user_profile_picture)
{
    $user_account_id = sanitizeString($mysqli, $user_account_id);
    $user_employee_id = sanitizeString($mysqli, $user_employee_id);
    $user_username = sanitizeString($mysqli, $user_username);
    $user_password = sanitizeString($mysqli, $user_password);
    $user_profile_picture = sanitizeString($mysqli, $user_profile_picture);
    
    $user_account_id = checkNotBlank($user_account_id, $redirectPage);
    $user_employee_id = checkNotBlank($user_employee_id, $redirectPage);
    $user_username = checkNotBlank($user_username, $redirectPage);
    $user_password = checkNotBlank($user_password, $redirectPage);
    $user_profile_picture = checkNotBlank($user_profile_picture, $redirectPage);
    
    if ($user_account_id === "Missing" || 
        $user_employee_id === "Missing" || 
        $user_username === "Missing" || 
        $user_password === "Missing" || 
        $user_profile_picture === "Missing") {
        return; 
    }

    
    $sql = "UPDATE factory_user_accounts SET 
                user_employee_id = ?, 
                user_username = ?, 
                user_password = ?, 
                user_profile_picture = ? 
            WHERE user_account_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'isssi',
        $user_employee_id,
        $user_username,
        $user_password,
        $user_profile_picture,
        $user_account_id
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = $defaultMessage; 
    } else {
        throwAnError(__LINE__, __FILE__, mysqli_stmt_error($stmt)); 
    }
    
    mysqli_stmt_close($stmt);
    header('Location: ' . $redirectPage); 
}

