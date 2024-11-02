<?php
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($checkInputPHPFilePath);
require_once($errorThrowerFilePath);
require_once($validateInputFilePath);
require_once($passwordHasherFilePath);

function getNextHighestID($mysqli, $fieldname, $tablename)
{
    $result = $mysqli->query("SELECT MAX($fieldname) AS 'max_id' FROM $tablename;");
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];
    if ($max_id === null) {
        $next_id = 1;
    } else {
        $next_id = intval($max_id) + 1;
    }
    return $next_id;
}
function continueFromHighestID($mysqli, $autoincrement_id_field, $tablename)
{
    $result = $mysqli->query("SELECT MAX($autoincrement_id_field) AS 'max_id' FROM $tablename;");
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];
    if ($max_id === null) {
        $max_id = 0;
    }
    $next_id = intval($max_id) + 1;
    $mysqli->query("ALTER TABLE $tablename AUTO_INCREMENT = $next_id");
}
/* Think it should be insert_records.php for $redirectPage */
function insertBranchRecord($mysqli, $redirectPage, $defaultMessage, $branch_country, $branch_city, $branch_timezone, $branch_street_address)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $branch_country = sanitizeString($mysqli, $branch_country);
    $branch_city = sanitizeString($mysqli, $branch_city);
    $branch_timezone = sanitizeString($mysqli, $branch_timezone);
    $branch_street_address = sanitizeString($mysqli, $branch_street_address);
    $branch_country = checkNotBlank($branch_country, $redirectPage);
    $branch_city = checkNotBlank($branch_city, $redirectPage);
    $branch_timezone = checkNotBlank($branch_timezone, $redirectPage);
    $branch_street_address = checkNotBlank($branch_street_address, $redirectPage);
    if ($branch_country === "Missing" || $branch_city === "Missing" || $branch_timezone === "Missing" || $branch_street_address === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'branch_id', 'factory_branches');
    $query_to_insert_new_record_into_factory_branches = "INSERT INTO factory_branches 
    (branch_country, branch_city, branch_timezone, branch_street_address)
    VALUES (?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_branches);
    $prepared_statement->bind_param("ssss", $branch_country, $branch_city, $branch_timezone, $branch_street_address);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertMachineTypeRecord($mysqli, $redirectPage, $defaultMessage, $machine_name)
{
    $machine_name = sanitizeString($mysqli, $machine_name);
    $machine_name = checkNotBlank($machine_name, $redirectPage);
    if ($machine_name === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'machine_type_id', 'factory_machine_types');
    $query_to_insert_new_record_into_factory_machine_types = "INSERT INTO factory_machine_types 
    (machine_name)
    VALUES (?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_machine_types);
    $prepared_statement->bind_param("s", $machine_name);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertShiftRecord($mysqli, $redirectPage, $defaultMessage, $shift_period)
{
    $shift_period = sanitizeString($mysqli, $shift_period);
    $shift_period = checkNotBlank($shift_period, $redirectPage);
    if ($shift_period === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'shift_id', 'factory_shifts');
    $query_to_insert_new_record_into_factory_shifts = "INSERT INTO factory_shifts 
    (shift_period)
    VALUES (?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_shifts);
    $prepared_statement->bind_param("s", $shift_period);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertOvertimeRecord($mysqli, $redirectPage, $defaultMessage, $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time)
{
    $overtime_start_date = checkDateValid($overtime_start_date, $redirectPage);
    $overtime_finish_date =  checkDateValid($overtime_finish_date, $redirectPage);

    $overtime_employee_id = sanitizeString($mysqli, $overtime_employee_id);
    $overtime_start_date = sanitizeString($mysqli, $overtime_start_date);
    $overtime_start_time = sanitizeString($mysqli, $overtime_start_time);
    $overtime_finish_date = sanitizeString($mysqli, $overtime_finish_date);
    $overtime_finish_time = sanitizeString($mysqli, $overtime_finish_time);
    $overtime_employee_id = checkNotBlank($overtime_employee_id, $redirectPage);
    $overtime_start_date = checkNotBlank($overtime_start_date, $redirectPage);
    $overtime_start_time = checkNotBlank($overtime_start_time, $redirectPage);
    $overtime_finish_date = checkNotBlank($overtime_finish_date, $redirectPage);
    $overtime_finish_time = checkNotBlank($overtime_finish_time, $redirectPage);


    


    if (
        $overtime_employee_id === "Missing" || $overtime_start_date === "Missing" ||
        $overtime_start_time === "Missing" || $overtime_finish_date === "Missing" ||
        $overtime_finish_time === "Missing"
    ) {
        return;
    }
    continueFromHighestID($mysqli, 'overtime_period_id', 'factory_employee_overtime_worked');
    $query_to_insert_new_record = "INSERT INTO factory_employee_overtime_worked 
    (overtime_employee_id, overtime_start_date, overtime_start_time, overtime_finish_date, overtime_finish_time) 
    VALUES (?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record);
    $prepared_statement->bind_param("issss", $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time);
    if ($prepared_statement->execute()) {
        $_SESSION['message'] = $defaultMessage;
        $prepared_statement->close();
    } else {
        $_SESSION['message'] = "Error inserting overtime record: " . $prepared_statement->error;
    }
    header('Location: ' . $redirectPage);
}
function insertEmployeeRecord($mysqli, $redirectPage, $defaultMessage, $employee_branch_id, $employee_first_name, $employee_last_name, $employee_email_address, $employee_role, $employee_salary, $employee_hourly_rate)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $employee_branch_id = sanitizeString($mysqli, $employee_branch_id);
    $employee_first_name = sanitizeString($mysqli, $employee_first_name);
    $employee_last_name = sanitizeString($mysqli, $employee_last_name);
    $employee_email_address = sanitizeString($mysqli, $employee_email_address);
    $employee_role = sanitizeString($mysqli, $employee_role);
    $employee_salary = sanitizeString($mysqli, $employee_salary);
    $employee_hourly_rate = sanitizeString($mysqli, $employee_hourly_rate);
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
    if (checkIfExists($mysqli, 'factory_employees', 'employee_email_address', $employee_email_address, "s")) {
        $_SESSION['message'] = "E-mail address not unique";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($employee_branch_id === "Missing" || $employee_first_name === "Missing" || $employee_last_name === "Missing" || $employee_role === "Missing" || ($employee_salary === "Missing" && $employee_hourly_rate === "Missing")) {
        return;
    }
    continueFromHighestID($mysqli, 'employee_id', 'factory_employees');
    $query_to_insert_new_record_into_factory_employees = "INSERT INTO factory_employees 
    (employee_branch_id, employee_first_name, employee_last_name, employee_email_address, employee_role, employee_salary, employee_hourly_rate)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employees);
    $prepared_statement->bind_param("issssdd", $employee_branch_id, $employee_first_name, $employee_last_name, $employee_email_address, $employee_role, $employee_salary, $employee_hourly_rate);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertInventoryRecord($mysqli, $redirectPage, $defaultMessage, $stock_branch_id, $stock_name, $stock_quantity)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $stock_branch_id = sanitizeString($mysqli, $stock_branch_id);
    $stock_name = sanitizeString($mysqli, $stock_name);
    $stock_quantity = sanitizeString($mysqli, $stock_quantity);
    $stock_branch_id = checkNotBlank($stock_branch_id, $redirectPage);
    $stock_name = checkNotBlank($stock_name, $redirectPage);
    $stock_quantity = checkNotBlank($stock_quantity, $redirectPage);
    if (!checkIfExists($mysqli, 'factory_branches', 'branch_id', $stock_branch_id, "i")) {
        $_SESSION['message'] = "Branch ID not found";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($stock_branch_id === "Missing" || $stock_name === "Missing" || $stock_quantity === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'stock_id', 'factory_inventory');
    $query_to_insert_new_record_into_factory_inventory = "INSERT INTO factory_inventory 
    (stock_branch_id, stock_name, stock_quantity)
    VALUES (?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_inventory);
    $prepared_statement->bind_param("iss", $stock_branch_id, $stock_name, $stock_quantity);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeMessageRecord($mysqli, $redirectPage, $defaultMessage, $sender_employee_id, $recipient_employee_id, $message_title, $message_body, $message_date, $conversation_id, $message_attachment, $message_attachment_required)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }

    
    $message_date = checkDateValid($message_date, $redirectPage);

    $sender_employee_id = sanitizeString($mysqli, $sender_employee_id);
    $recipient_employee_id = sanitizeString($mysqli, $recipient_employee_id);
    $message_title = sanitizeString($mysqli, $message_title);
    $message_body = sanitizeString($mysqli, $message_body);
    $message_date = sanitizeString($mysqli, $message_date);
    $conversation_id = sanitizeString($mysqli, $conversation_id);
    $sender_employee_id = checkNotBlank($sender_employee_id, $redirectPage);
    $recipient_employee_id = checkNotBlank($recipient_employee_id, $redirectPage);
    $message_title = checkNotBlank($message_title, $redirectPage);
    $message_body = checkNotBlank($message_body, $redirectPage);
    $message_date = checkNotBlank($message_date, $redirectPage);
    $conversation_id = checkNotBlank($conversation_id, $redirectPage);
    $message_attachment = checkFile($message_attachment, $redirectPage, $message_attachment_required);
    
    
    if ($sender_employee_id === "Missing" || $recipient_employee_id === "Missing" || $message_title === "Missing" || $message_body === "Missing" || $message_date === "Missing" || $conversation_id === "Missing") {
        return;
    }
    if ($message_attachment === "Invalid") {
        return;
    }
    continueFromHighestID($mysqli, 'message_id', 'factory_employee_messages');
    $query_to_insert_new_record_into_factory_employee_messages = "INSERT INTO factory_employee_messages 
    (sender_employee_id, recipient_employee_id, message_title, message_body, message_date, conversation_id, message_attachment)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_messages);
    $prepared_statement->bind_param("iissssb", $sender_employee_id, $recipient_employee_id, $message_title, $message_body, $message_date, $conversation_id, $message_attachment);
    if ($message_attachment) {
        $fp = fopen($message_attachment, "rb"); // Open the file in binary mode
        while (!feof($fp)) {
            $prepared_statement->send_long_data(6, fread($fp, 8192));
        }
        fclose($fp);
    }
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertUserAccountRecord($mysqli, $redirectPage, $defaultMessage, $user_employee_id, $user_username, $user_password, $user_profile_picture)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $user_employee_id = sanitizeString($mysqli, $user_employee_id);
    $user_username = sanitizeString($mysqli, $user_username);
    $user_password = sanitizeString($mysqli, $user_password);
    $user_profile_picture = sanitizeString($mysqli, $user_profile_picture);
    $user_employee_id = checkNotBlank($user_employee_id, $redirectPage);
    $user_username = checkNotBlank($user_username, $redirectPage);
    $user_password = checkNotBlank($user_password, $redirectPage);
    $user_profile_picture = checkNotBlank($user_profile_picture, $redirectPage);
    /* Make sure user_employee_id doesn't match */
    /* Two users shouldn't have the same account */
    if (checkIfExists($mysqli, 'factory_user_accounts', 'user_employee_id', $user_employee_id, "i")) {
        $_SESSION['message'] = "User already has an account";
        header('Location: ' . $redirectPage);
        exit();
    }
    /* Make sure user_employee_id corresponds to an employee_id from factory_employees table */
    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $user_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($user_employee_id === "Missing" || $user_username === "Missing" || $user_password === "Missing" || $user_profile_picture === "Missing") {
        return;
    }
    // Hash the PWD before putting it into the DB
    $user_password = bcryptHash($user_password);
    continueFromHighestID($mysqli, 'user_account_id', 'factory_user_accounts');
    $query_to_insert_new_record_into_factory_user_accounts = "INSERT INTO factory_user_accounts 
    (user_employee_id, user_username, user_password, user_profile_picture)
    VALUES (?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_user_accounts);
    $prepared_statement->bind_param("isss", $user_employee_id, $user_username, $user_password, $user_profile_picture);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeGrievanceRecord($mysqli, $redirectPage, $defaultMessage, $grievance_employee_id, $grievance_date, $grievance_description, $grievance_action_taken)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }

    $grievance_date = checkDateValid($grievance_date, $redirectPage);



    $grievance_employee_id = sanitizeString($mysqli, $grievance_employee_id);
    $grievance_date = sanitizeString($mysqli, $grievance_date);
    $grievance_description = sanitizeString($mysqli, $grievance_description);
    $grievance_action_taken = sanitizeString($mysqli, $grievance_action_taken);
    $grievance_employee_id = checkNotBlank($grievance_employee_id, $redirectPage);
    $grievance_date = checkNotBlank($grievance_date, $redirectPage);
    $grievance_description = checkNotBlank($grievance_description, $redirectPage);
    $grievance_action_taken = checkNotBlank($grievance_action_taken, $redirectPage);


    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $grievance_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($grievance_employee_id === "Missing" || $grievance_date === "Missing" || $grievance_description === "Missing" || $grievance_action_taken === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'grievance_id', 'factory_employee_grievances');
    $query_to_insert_new_record_into_factory_employee_grievances = "INSERT INTO factory_employee_grievances 
    (grievance_employee_id, grievance_date, grievance_description, grievance_action_taken)
    VALUES (?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_grievances);
    $prepared_statement->bind_param("isss", $grievance_employee_id, $grievance_date, $grievance_description, $grievance_action_taken);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeePaymentRecord($mysqli, $redirectPage, $defaultMessage, $payment_employee_id, $payment_date_period_start, $payment_date_period_end, $payment_amount)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }


    $payment_date_period_start = checkDateValid($payment_date_period_start, $redirectPage);
    $payment_date_period_end = checkDateValid($payment_date_period_end, $redirectPage);


    $payment_employee_id = sanitizeString($mysqli, $payment_employee_id);
    $payment_date_period_start = sanitizeString($mysqli, $payment_date_period_start);
    $payment_date_period_end = sanitizeString($mysqli, $payment_date_period_end);
    $payment_amount = sanitizeString($mysqli, $payment_amount);
    $payment_employee_id = checkNotBlank($payment_employee_id, $redirectPage);
    $payment_date_period_start = checkNotBlank($payment_date_period_start, $redirectPage);
    $payment_date_period_end = checkNotBlank($payment_date_period_end, $redirectPage);
    $payment_amount = checkNotBlank($payment_amount, $redirectPage);




    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $payment_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($payment_employee_id === "Missing" || $payment_date_period_start === "Missing" || $payment_date_period_end === "Missing" || $payment_amount === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'payment_id', 'factory_employee_payments');
    $query_to_insert_new_record_into_factory_employee_payments = "INSERT INTO factory_employee_payments 
    (payment_employee_id, payment_date_period_start, payment_date_period_end, payment_amount)
    VALUES (?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_payments);
    $prepared_statement->bind_param("isss", $payment_employee_id, $payment_date_period_start, $payment_date_period_end, $payment_amount);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeTimePeriodWorkedRecord($mysqli, $redirectPage, $defaultMessage, $working_time_period_employee_id, $employee_shift_id, $employee_clock_in_date, $employee_clock_in_time, $employee_clock_off_date, $employee_clock_off_time, $clocking_in_or_off)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }


    $employee_clock_in_date = checkDateValid($employee_clock_in_date, $redirectPage);



    $working_time_period_employee_id = sanitizeString($mysqli, $working_time_period_employee_id);
    $employee_shift_id = sanitizeString($mysqli, $employee_shift_id);
    $employee_clock_in_date = sanitizeString($mysqli, $employee_clock_in_date);
    $employee_clock_in_time = sanitizeString($mysqli, $employee_clock_in_time);
    $working_time_period_employee_id = checkNotBlank($working_time_period_employee_id, $redirectPage);
    $employee_clock_in_date = checkNotBlank($employee_clock_in_date, $redirectPage);
    $employee_clock_in_time = checkNotBlank($employee_clock_in_time, $redirectPage);
    $employee_shift_id = checkNotBlank($employee_shift_id, $redirectPage);








    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $working_time_period_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if (!checkIfExists($mysqli, 'factory_shifts', 'shift_id', $employee_shift_id, "i")) {
        $_SESSION['message'] = "Shift ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($working_time_period_employee_id === "Missing" || $employee_clock_in_date === "Missing" || $employee_clock_in_time === "Missing" || $employee_shift_id === "Missing") {
        return;
    }
    if ($clocking_in_or_off == 'clocking off') {


        $employee_clock_off_date = checkDateValid($employee_clock_off_date, $redirectPage);


        $employee_clock_off_date = sanitizeString($mysqli, $employee_clock_off_date);
        $employee_clock_off_time = sanitizeString($mysqli, $employee_clock_off_time);
        $employee_clock_off_date = checkNotBlank($employee_clock_off_date, $redirectPage);
        $employee_clock_off_time = checkNotBlank($employee_clock_off_time, $redirectPage);



        if ($employee_clock_off_date === "Missing" || $employee_clock_off_time === "Missing") {
            return;
        }
    }
    continueFromHighestID($mysqli, 'working_time_period_id', 'factory_employee_time_period_worked');
    $query_to_insert_new_record_into_factory_employee_time_period_worked = "INSERT INTO factory_employee_time_period_worked 
    (working_time_period_employee_id, employee_shift_id, employee_clock_in_date, employee_clock_in_time, employee_clock_off_date, employee_clock_off_time)
    VALUES (?, ?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_time_period_worked);
    $prepared_statement->bind_param("iissss", $working_time_period_employee_id, $employee_shift_id, $employee_clock_in_date, $employee_clock_in_time, $employee_clock_off_date, $employee_clock_off_time);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function updateEmployeeClockOffTimePeriodWorkedRecord($mysqli, $redirectPage, $defaultMessage, $employee_shift_id, $employee_clock_in_date, $employee_clock_in_time, $employee_clock_off_date, $employee_clock_off_time)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }

    $employee_clock_in_date = checkDateValid($employee_clock_in_date, $redirectPage);
    $employee_clock_off_date = checkDateValid($employee_clock_off_date, $redirectPage);



    $employee_shift_id = sanitizeString($mysqli, $employee_shift_id);
    $employee_clock_in_date = sanitizeString($mysqli, $employee_clock_in_date);
    $employee_clock_in_time = sanitizeString($mysqli, $employee_clock_in_time);
    $employee_clock_off_date = sanitizeString($mysqli, $employee_clock_off_date);
    $employee_clock_off_time = sanitizeString($mysqli, $employee_clock_off_time);
    $employee_shift_id = checkNotBlank($employee_shift_id, $redirectPage);
    $employee_clock_in_date = checkNotBlank($employee_clock_in_date, $redirectPage);
    $employee_clock_in_time = checkNotBlank($employee_clock_in_time, $redirectPage);
    $employee_clock_off_date = checkNotBlank($employee_clock_off_date, $redirectPage);
    $employee_clock_off_time = checkNotBlank($employee_clock_off_time, $redirectPage);








    $query_to_update_clock_off_date_and_time = "UPDATE factory_employee_time_period_worked
                               SET employee_clock_off_date = ?, employee_clock_off_time = ?
                               WHERE employee_shift_id = ? AND employee_clock_in_date = ? AND employee_clock_in_time = ?
                               AND employee_clock_off_date IS NULL AND employee_clock_off_time IS NULL";
    $prepared_statement = $mysqli->prepare($query_to_update_clock_off_date_and_time);
    $prepared_statement->bind_param("ssiss", $employee_clock_off_date, $employee_clock_off_time, $employee_shift_id, $employee_clock_in_date, $employee_clock_in_time);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeOvertimeWorkedRecord($mysqli, $redirectPage, $defaultMessage, $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }


    $overtime_start_date = checkDateValid($overtime_start_date, $redirectPage);
    $overtime_finish_date = checkDateValid($overtime_finish_date, $redirectPage);




    $overtime_employee_id = sanitizeString($mysqli, $overtime_employee_id);
    $overtime_start_date = sanitizeString($mysqli, $overtime_start_date);
    $overtime_start_time = sanitizeString($mysqli, $overtime_start_time);
    $overtime_finish_date = sanitizeString($mysqli, $overtime_finish_date);
    $overtime_finish_time = sanitizeString($mysqli, $overtime_finish_time);
    $overtime_employee_id = checkNotBlank($overtime_employee_id, $redirectPage);
    $overtime_start_date = checkNotBlank($overtime_start_date, $redirectPage);
    $overtime_start_time = checkNotBlank($overtime_start_time, $redirectPage);
    $overtime_finish_date = checkNotBlank($overtime_finish_date, $redirectPage);
    $overtime_finish_time = checkNotBlank($overtime_finish_time, $redirectPage);




    if ($overtime_employee_id === "Missing" || $overtime_start_date === "Missing" || $overtime_start_time === "Missing" || $overtime_finish_date === "Missing" || $overtime_finish_time === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'overtime_period_id', 'factory_employee_overtime_worked');
    $query_to_insert_new_record_into_factory_employee_overtime_worked = "INSERT INTO factory_employee_overtime_worked 
    (overtime_employee_id, overtime_start_date, overtime_start_time, overtime_finish_date, overtime_finish_time)
    VALUES (?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_overtime_worked);
    $prepared_statement->bind_param("issss", $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeBreaksTakenRecord($mysqli, $redirectPage, $defaultMessage, $break_taker_employee_id, $break_start_date, $break_start_time, $break_finish_date, $break_finish_time, $break_type)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }

    $break_start_date = checkDateValid($break_start_date, $redirectPage);
    $break_finish_date = checkDateValid($break_finish_date, $redirectPage);




    $break_taker_employee_id = sanitizeString($mysqli, $break_taker_employee_id);
    $break_start_date = sanitizeString($mysqli, $break_start_date);
    $break_start_time = sanitizeString($mysqli, $break_start_time);
    $break_finish_date = sanitizeString($mysqli, $break_finish_date);
    $break_finish_time = sanitizeString($mysqli, $break_finish_time);
    $break_type = sanitizeString($mysqli, $break_type);
    $break_taker_employee_id = checkNotBlank($break_taker_employee_id, $redirectPage);
    $break_start_date = checkNotBlank($break_start_date, $redirectPage);
    $break_start_time = checkNotBlank($break_start_time, $redirectPage);
    $break_finish_date = checkNotBlank($break_finish_date, $redirectPage);
    $break_finish_time = checkNotBlank($break_finish_time, $redirectPage);
    $break_type = checkNotBlank($break_type, $redirectPage);





    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $break_taker_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($break_taker_employee_id === "Missing" || $break_start_date === "Missing" || $break_start_time === "Missing" || $break_finish_date === "Missing" || $break_finish_time === "Missing" || $break_type === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'break_period_id', 'factory_employee_breaks_taken');
    $query_to_insert_new_record_into_factory_employee_breaks_taken = "INSERT INTO factory_employee_breaks_taken 
    (break_taker_employee_id, break_start_date, break_start_time, break_finish_date, break_finish_time, break_type)
    VALUES (?, ?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_breaks_taken);
    if (!$prepared_statement) {
        $errorAdditionalInformation = "Query failed: " . $mysqli->error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $prepared_statement->bind_param("isssss", $break_taker_employee_id, $break_start_date, $break_start_time, $break_finish_date, $break_finish_time, $break_type);
    $prepared_statement->execute();
    if ($prepared_statement->affected_rows === 0) {
        $errorAdditionalInformation = "Query failed: " . $mysqli->error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertEmployeeSafetyIncidentRecord($mysqli, $redirectPage, $defaultMessage, $incident_employee_id, $incident_date, $incident_description, $incident_outcome)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }


    $incident_date = checkDateValid($incident_date, $redirectPage);



    $incident_employee_id = sanitizeString($mysqli, $incident_employee_id);
    $incident_date = sanitizeString($mysqli, $incident_date);
    $incident_description = sanitizeString($mysqli, $incident_description);
    $incident_outcome = sanitizeString($mysqli, $incident_outcome);
    $incident_employee_id = checkNotBlank($incident_employee_id, $redirectPage);
    $incident_date = checkNotBlank($incident_date, $redirectPage);
    $incident_description = checkNotBlank($incident_description, $redirectPage);
    $incident_outcome = checkNotBlank($incident_outcome, $redirectPage);



    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $incident_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($incident_employee_id === "Missing" || $incident_date === "Missing" || $incident_description === "Missing" || $incident_outcome === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'incident_id', 'factory_safety_incidents');
    $query_to_insert_new_record_into_factory_employee_safety_incidents = "INSERT INTO factory_safety_incidents 
    (incident_employee_id, incident_date, incident_description, incident_outcome)
    VALUES (?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_employee_safety_incidents);
    $prepared_statement->bind_param("isss", $incident_employee_id, $incident_date, $incident_description, $incident_outcome);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}
function insertJobRecord($mysqli, $redirectPage, $defaultMessage, $job_employee_id, $job_description, $job_status, $job_task_notes, $job_priority)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    $job_employee_id = sanitizeString($mysqli, $job_employee_id);
    $job_description = sanitizeString($mysqli, $job_description);
    $job_status = sanitizeString($mysqli, $job_status);
    $job_task_notes = sanitizeString($mysqli, $job_task_notes);
    $job_priority = sanitizeString($mysqli, $job_priority);
    $job_employee_id = checkNotBlank($job_employee_id, $redirectPage);
    $job_description = checkNotBlank($job_description, $redirectPage);
    $job_status = checkNotBlank($job_status, $redirectPage);
    $job_task_notes = checkNotBlank($job_task_notes, $redirectPage);
    $job_priority = checkNotBlank($job_priority, $redirectPage);
    if (!checkIfExists($mysqli, 'factory_employees', 'employee_id', $job_employee_id, "i")) {
        $_SESSION['message'] = "Employee ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($job_employee_id === "Missing" || $job_description === "Missing" || $job_status === "Missing" || $job_task_notes === "Missing" || $job_priority === "Missing") {
        return;
    }
    continueFromHighestID($mysqli, 'job_id', 'factory_jobs');
    $query_to_insert_new_record_into_factory_jobs = "INSERT INTO factory_jobs 
    (job_employee_id, job_description, job_status, job_task_notes, job_priority)
    VALUES (?, ?, ?, ?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_jobs);
    $prepared_statement->bind_param("issss", $job_employee_id, $job_description, $job_status, $job_task_notes, $job_priority);
    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}



function insertMachineRecord($mysqli, $redirectPage, $defaultMessage, $machine_type_id, $branch_id)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }
    
    $machine_type_id = sanitizeString($mysqli, $machine_type_id);
    $branch_id = sanitizeString($mysqli, $branch_id);
    
    $machine_type_id = checkNotBlank($machine_type_id, $redirectPage);
    $branch_id = checkNotBlank($branch_id, $redirectPage);
    
    if (!checkIfExists($mysqli, 'factory_machine_types', 'machine_type_id', $machine_type_id, "i")) {
        $_SESSION['message'] = "Machine type ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }

    if (!checkIfExists($mysqli, 'factory_branches', 'branch_id', $branch_id, "i")) {
        $_SESSION['message'] = "Branch ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }

    if ($machine_type_id === "Missing" || $branch_id === "Missing") {
        return;
    }

    continueFromHighestID($mysqli, 'machine_id', 'factory_machines');
    
    $query_to_insert_new_machine_record = "INSERT INTO factory_machines (machine_type_id, branch_id) VALUES (?, ?)";
    $prepared_statement = $mysqli->prepare($query_to_insert_new_machine_record);
    $prepared_statement->bind_param("ii", $machine_type_id, $branch_id);
    $prepared_statement->execute();
    $prepared_statement->close();

    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}



function insertMachineStatusesRecord($mysqli, $redirectPage, $defaultMessage, $machine_id, $timestamp, $temperature, $pressure, $vibration, $humidity, $power_consumption, $operational_status, $error_code, $production_count, $maintenance_logs, $speed)
{
    if ($mysqli->connect_error) {
        $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
        throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
    }


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
    $maintenance_logs = empty(trim($maintenance_logs)) ? null : sanitizeString($mysqli, $maintenance_logs);
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

    
    if (!checkIfExists($mysqli, 'factory_machines', 'machine_id', $machine_id, "i")) {
        $_SESSION['message'] = "Machine ID doesn't exist";
        header('Location: ' . $redirectPage);
        exit();
    }
    if ($machine_id === "Missing" || $timestamp === "Missing" || $temperature === "Missing" || $pressure === "Missing" || $vibration === "Missing" || $humidity === "Missing" || $power_consumption === "Missing" || $operational_status === "Missing" ||  $production_count === "Missing" ) {
        return;
    }
    continueFromHighestID($mysqli, 'machine_status_id', tablename: 'factory_machine_statuses');
    
    
    $query_to_insert_new_record_into_factory_machine_statuses = "INSERT INTO factory_machine_statuses 
    (machine_id, timestamp, temperature, pressure, vibration, humidity, power_consumption, operational_status, error_code, production_count, maintenance_log, speed)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $prepared_statement = $mysqli->prepare($query_to_insert_new_record_into_factory_machine_statuses);

    $prepared_statement->bind_param(
        "isssssssssss", 
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
        $maintenance_logs, 
        $speed 
    );



    $prepared_statement->execute();
    $prepared_statement->close();
    $_SESSION['message'] = $defaultMessage;
    header('Location: ' . $redirectPage);
}