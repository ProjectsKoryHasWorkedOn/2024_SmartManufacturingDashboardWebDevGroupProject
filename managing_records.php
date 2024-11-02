<?php
require_once("php_resource_paths.php");
include_once($DBconnectionFilePath);
require_once($errorThrowerFilePath);
require_once($updateRecordFilePath);
require_once($deleteRecordFilePath);
if (!isset($_SESSION['current_index_machine_type'])) {
    $_SESSION['current_index_machine_type'] = 0;
}
if (!isset($_SESSION['current_index_branch'])) {
    $_SESSION['current_index_branch'] = 0;
}
if (!isset($_SESSION['current_index_shift'])) {
    $_SESSION['current_index_shift'] = 0;
}
if (!isset($_SESSION['current_index_machine'])) {
    $_SESSION['current_index_machine'] = 0;
}
if (!isset($_SESSION['current_index_employee'])) {
    $_SESSION['current_index_employee'] = 0;
}
if (!isset($_SESSION['current_index_stock'])) {
    $_SESSION['current_index_stock'] = 0;
}
if (!isset($_SESSION['current_index_message'])) {
    $_SESSION['current_index_message'] = 0;
}
if (!isset($_SESSION['current_index_grievance'])) {
    $_SESSION['current_index_grievance'] = 0;
}
if (!isset($_SESSION['current_index_payment'])) {
    $_SESSION['current_index_payment'] = 0;
}
if (!isset($_SESSION['current_index_work_period'])) {
    $_SESSION['current_index_work_period'] = 0;
}
if (!isset($_SESSION['current_index_break'])) {
    $_SESSION['current_index_break'] = 0;
}
if (!isset($_SESSION['current_index_overtime'])) {
    $_SESSION['current_index_overtime'] = 0;
}
if (!isset($_SESSION['current_index_safety_incident'])) {
    $_SESSION['current_index_safety_incident'] = 0;
}
if (!isset($_SESSION['current_index_job'])) {
    $_SESSION['current_index_job'] = 0;
}
if (!isset($_SESSION['current_index_machine_status'])) {
    $_SESSION['current_index_machine_status'] = 0;
}
if (!isset($_SESSION['current_index_user_account'])) {
    $_SESSION['current_index_user_account'] = 0;
}
$sql_query_for_machine_types = "SELECT machine_type_id, machine_name FROM factory_machine_types";
$result_of_sql_query_for_machine_types = $mysqli->query($sql_query_for_machine_types);
if (!$result_of_sql_query_for_machine_types) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_machine_types->data_seek($_SESSION['current_index_machine_type']);
$row_of_machine_types_table = $result_of_sql_query_for_machine_types->fetch_assoc();
$result_of_sql_query_for_machine_types->free();
$sql_query_for_branches = "SELECT branch_id, branch_country, branch_city, branch_timezone, branch_street_address FROM factory_branches;";
$result_of_sql_query_for_branches = $mysqli->query($sql_query_for_branches);
if (!$result_of_sql_query_for_branches) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_branches->data_seek($_SESSION['current_index_branch']);
$row_of_branches_table = $result_of_sql_query_for_branches->fetch_assoc();
$result_of_sql_query_for_branches->free();
$sql_query_for_shifts = "SELECT shift_id, shift_period FROM factory_shifts;";
$result_of_sql_query_for_shifts = $mysqli->query($sql_query_for_shifts);
if (!$result_of_sql_query_for_shifts) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_shifts->data_seek($_SESSION['current_index_shift']);
$row_of_shifts_table = $result_of_sql_query_for_shifts->fetch_assoc();
$result_of_sql_query_for_shifts->free();
$sql_query_for_employees = "SELECT employee_id, employee_branch_id, employee_first_name, employee_last_name, employee_email_address, employee_role, employee_salary, employee_hourly_rate FROM factory_employees;";
$result_of_sql_query_for_employees = $mysqli->query($sql_query_for_employees);
if (!$result_of_sql_query_for_employees) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_employees->data_seek($_SESSION['current_index_employee']);
$row_of_employees_table = $result_of_sql_query_for_employees->fetch_assoc();
$result_of_sql_query_for_employees->free();
$sql_query_for_inventory = "SELECT stock_id, stock_name, stock_quantity, stock_branch_id FROM factory_inventory;";
$result_of_sql_query_for_inventory = $mysqli->query($sql_query_for_inventory);
if (!$result_of_sql_query_for_inventory) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_inventory->data_seek($_SESSION['current_index_stock']);
$row_of_inventory_table = $result_of_sql_query_for_inventory->fetch_assoc();
$result_of_sql_query_for_inventory->free();
$sql_query_for_messages = "SELECT message_id, sender_employee_id, recipient_employee_id, conversation_id, message_title, message_body, message_date, message_attachment FROM factory_employee_messages;";
$result_of_sql_query_for_messages = $mysqli->query($sql_query_for_messages);
if (!$result_of_sql_query_for_messages) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_messages->data_seek($_SESSION['current_index_message']);
$row_of_messages_table = $result_of_sql_query_for_messages->fetch_assoc();
$result_of_sql_query_for_messages->free();
$sql_query_for_grievances = "SELECT grievance_id, grievance_employee_id, grievance_date, grievance_description, grievance_action_taken FROM factory_employee_grievances;";
$result_of_sql_query_for_grievances = $mysqli->query($sql_query_for_grievances);
if (!$result_of_sql_query_for_grievances) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_grievances->data_seek($_SESSION['current_index_grievance']);
$row_of_grievances_table = $result_of_sql_query_for_grievances->fetch_assoc();
$result_of_sql_query_for_grievances->free();
$sql_query_for_payments = "SELECT payment_id, payment_employee_id, payment_date_period_start, payment_date_period_end, payment_amount FROM factory_employee_payments;";
$result_of_sql_query_for_payments = $mysqli->query($sql_query_for_payments);
if (!$result_of_sql_query_for_payments) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_payments->data_seek($_SESSION['current_index_payment']);
$row_of_payments_table = $result_of_sql_query_for_payments->fetch_assoc();
$result_of_sql_query_for_payments->free();
$sql_query_for_work_periods = "SELECT working_time_period_id, working_time_period_employee_id, employee_shift_id, employee_clock_in_date, employee_clock_in_time, employee_clock_off_date, employee_clock_off_time FROM factory_employee_time_period_worked;";
$result_of_sql_query_for_work_periods = $mysqli->query($sql_query_for_work_periods);
if (!$result_of_sql_query_for_work_periods) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_work_periods->data_seek($_SESSION['current_index_work_period']);
$row_of_work_periods_table = $result_of_sql_query_for_work_periods->fetch_assoc();
$result_of_sql_query_for_work_periods->free();
$sql_query_for_breaks = "SELECT break_period_id, break_taker_employee_id, break_start_date, break_start_time, break_finish_date, break_finish_time, break_type FROM factory_employee_breaks_taken;";
$result_of_sql_query_for_breaks = $mysqli->query($sql_query_for_breaks);
if (!$result_of_sql_query_for_breaks) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_breaks->data_seek($_SESSION['current_index_break']);
$row_of_breaks_table = $result_of_sql_query_for_breaks->fetch_assoc();
$result_of_sql_query_for_breaks->free();
$sql_query_for_overtime = "SELECT overtime_period_id, overtime_employee_id, overtime_start_date, overtime_start_time, overtime_finish_date, overtime_finish_time FROM factory_employee_overtime_worked;";
$result_of_sql_query_for_overtime = $mysqli->query($sql_query_for_overtime);
if (!$result_of_sql_query_for_overtime) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_overtime = $result_of_sql_query_for_overtime->num_rows; 
if ($total_overtime > 0) {
    $result_of_sql_query_for_overtime->data_seek($_SESSION['current_index_overtime']);
    $row_of_overtime_table = $result_of_sql_query_for_overtime->fetch_assoc();
} else {
    $row_of_overtime_table = null; 
}
$result_of_sql_query_for_overtime->free();


$sql_query_for_machines = "SELECT machine_id, machine_type_id, branch_id FROM factory_machines;";
$result_of_sql_query_for_machines = $mysqli->query($sql_query_for_machines);

if (!$result_of_sql_query_for_machines) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}

$result_of_sql_query_for_machines->data_seek($_SESSION['current_index_machine']);
$row_of_machines_table = $result_of_sql_query_for_machines->fetch_assoc();
$result_of_sql_query_for_machines->free();











$sql_query_for_incidents = "SELECT incident_id, incident_employee_id, incident_date, incident_description, incident_outcome FROM factory_safety_incidents;";
$result_of_sql_query_for_incidents = $mysqli->query($sql_query_for_incidents);
if (!$result_of_sql_query_for_incidents) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_incidents->data_seek($_SESSION['current_index_safety_incident']);
$row_of_safety_incidents_table = $result_of_sql_query_for_incidents->fetch_assoc();
$result_of_sql_query_for_incidents->free();
$sql_query_for_jobs = "SELECT job_id, job_employee_id, job_assigned_date, job_completed_date, job_description, job_status, job_task_notes, job_priority FROM factory_jobs;";
$result_of_sql_query_for_jobs = $mysqli->query($sql_query_for_jobs);
if (!$result_of_sql_query_for_jobs) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_jobs->data_seek($_SESSION['current_index_job']);
$row_of_jobs_table = $result_of_sql_query_for_jobs->fetch_assoc();
$result_of_sql_query_for_jobs->free();




$sql_query_for_machine_status = "SELECT machine_status_id, machine_id, timestamp, temperature, pressure, vibration, humidity, power_consumption, operational_status, error_code, production_count, maintenance_log, speed FROM factory_machine_statuses;";
$result_of_sql_query_for_machine_status = $mysqli->query($sql_query_for_machine_status);

if (!$result_of_sql_query_for_machine_status) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}

if ($result_of_sql_query_for_machine_status->data_seek($_SESSION['current_index_machine_status'])) {
    $row_of_machine_statuses_table = $result_of_sql_query_for_machine_status->fetch_assoc();

    if (!$row_of_machine_statuses_table) {
        echo "No data found for the current index.";
        exit; 
    }

    $timestamp = $row_of_machine_statuses_table['timestamp'];
   $formatted_timestamp = date('Y-m-d\TH:i', strtotime($timestamp));
} else {
    echo "Error seeking to the specified index.";
    exit; 
}






$total_machine_types_query = "SELECT COUNT(*) AS total FROM factory_machine_types";
$total_machine_types_result = $mysqli->query($total_machine_types_query);
if (!$total_machine_types_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_machine_types_row = $total_machine_types_result->fetch_assoc();
$total_machine_types = $total_machine_types_row['total'];
$total_machine_types_result->free();



$total_branches_query = "SELECT COUNT(*) AS total FROM factory_branches";
$total_branches_result = $mysqli->query($total_branches_query);
if (!$total_branches_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_branches_row = $total_branches_result->fetch_assoc();
$total_branches = $total_branches_row['total'];
$total_branches_result->free();
$total_shifts_query = "SELECT COUNT(*) AS total FROM factory_shifts";
$total_shifts_result = $mysqli->query($total_shifts_query);
if (!$total_shifts_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_shifts_row = $total_shifts_result->fetch_assoc();
$total_shifts = $total_shifts_row['total'];
$total_shifts_result->free();
$total_employees_query = "SELECT COUNT(*) AS total FROM factory_employees";
$total_employees_result = $mysqli->query($total_employees_query);
if (!$total_employees_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_employees_row = $total_employees_result->fetch_assoc();
$total_employees = $total_employees_row['total'];
$total_employees_result->free();
$total_inventory_query = "SELECT COUNT(*) AS total FROM factory_inventory";
$total_inventory_result = $mysqli->query($total_inventory_query);
if (!$total_inventory_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_inventory_row = $total_inventory_result->fetch_assoc();
$total_inventory = $total_inventory_row['total'];
$total_inventory_result->free();
$total_messages_query = "SELECT COUNT(*) AS total FROM factory_employee_messages";
$total_messages_result = $mysqli->query($total_messages_query);
if (!$total_messages_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_messages_row = $total_messages_result->fetch_assoc();
$total_messages = $total_messages_row['total'];
$total_messages_result->free();
$total_grievances_query = "SELECT COUNT(*) AS total FROM factory_employee_grievances";
$total_grievances_result = $mysqli->query
($total_grievances_query);
if (!$total_grievances_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_grievances_row = $total_grievances_result->fetch_assoc();
$total_grievances = $total_grievances_row['total'];
$total_grievances_result->free();
$total_payments_query = "SELECT COUNT(*) AS total FROM factory_employee_payments";
$total_payments_result = $mysqli->query($total_payments_query);
if (!$total_payments_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_payments_row = $total_payments_result->fetch_assoc();
$total_payments = $total_payments_row['total'];
$total_payments_result->free();
$total_work_periods_query = "SELECT COUNT(*) AS total FROM factory_employee_time_period_worked";
$total_work_periods_result = $mysqli->query($total_work_periods_query);
if (!$total_work_periods_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_work_periods_row = $total_work_periods_result->fetch_assoc();
$total_work_periods = $total_work_periods_row['total'];
$total_work_periods_result->free();
$total_breaks_query = "SELECT COUNT(*) AS total FROM factory_employee_breaks_taken";
$total_breaks_result = $mysqli->query($total_breaks_query);
if (!$total_breaks_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_breaks_row = $total_breaks_result->fetch_assoc();
$total_breaks = $total_breaks_row['total'];
$total_breaks_result->free();
$total_overtime_query = "SELECT COUNT(*) AS total FROM factory_employee_overtime_worked";
$total_overtime_result = $mysqli->query($total_overtime_query);
if (!$total_overtime_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_overtime_row = $total_overtime_result->fetch_assoc();
$total_overtime = $total_overtime_row['total'];
$total_overtime_result->free();




$total_machines_query = "SELECT COUNT(*) AS total FROM factory_machines";
$total_machines_result = $mysqli->query($total_machines_query);

if (!$total_machines_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}

$total_machines_row = $total_machines_result->fetch_assoc();
$total_machines = $total_machines_row['total'];
$total_machines_result->free();






$total_safety_incidents_query = "SELECT COUNT(*) AS total FROM factory_safety_incidents";
$total_safety_incidents_result = $mysqli->query($total_safety_incidents_query);
if (!$total_safety_incidents_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_safety_incidents_row = $total_safety_incidents_result->fetch_assoc();
$total_safety_incidents = $total_safety_incidents_row['total'];
$total_safety_incidents_result->free();
$total_jobs_query = "SELECT COUNT(*) AS total FROM factory_jobs";
$total_jobs_result = $mysqli->query($total_jobs_query);
if (!$total_jobs_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_jobs_row = $total_jobs_result->fetch_assoc();
$total_jobs = $total_jobs_row['total'];
$total_jobs_result->free();
$total_machine_status_query = "SELECT COUNT(*) AS total FROM factory_machine_statuses";
$total_machine_status_result = $mysqli->query($total_machine_status_query);
if (!$total_machine_status_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_machine_status_row = $total_machine_status_result->fetch_assoc();
$total_machine_statuses = $total_machine_status_row['total'];
$total_machine_status_result->free();
$sql_query_for_user_accounts = "SELECT user_account_id, user_employee_id, user_username, user_profile_picture FROM factory_user_accounts;";
$result_of_sql_query_for_user_accounts = $mysqli->query($sql_query_for_user_accounts);
if (!$result_of_sql_query_for_user_accounts) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$result_of_sql_query_for_user_accounts->data_seek($_SESSION['current_index_user_account']);
$row_of_user_accounts_table = $result_of_sql_query_for_user_accounts->fetch_assoc();
$result_of_sql_query_for_user_accounts->free();
$total_user_accounts_query = "SELECT COUNT(*) AS total FROM factory_user_accounts";
$total_user_accounts_result = $mysqli->query($total_user_accounts_query);
if (!$total_user_accounts_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}
$total_user_accounts_row = $total_user_accounts_result->fetch_assoc();
$total_user_accounts = $total_user_accounts_row['total'];
$total_user_accounts_result->free();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['first_machine_type'])) {
        $_SESSION['current_index_machine_type'] = 0;
    } elseif (isset($_POST['last_machine_type'])) {
        $_SESSION['current_index_machine_type'] = $total_machine_types - 1;
    }
    if (isset($_POST['first_user_account'])) {
        $_SESSION['current_index_user_account'] = 0;
    } elseif (isset($_POST['last_user_account'])) {
        $_SESSION['current_index_user_account'] = $total_user_accounts - 1;
    }
    if (isset($_POST['first_branch'])) {
        $_SESSION['current_index_branch'] = 0;
    } elseif (isset($_POST['last_branch'])) {
        $_SESSION['current_index_branch'] = $total_branches - 1;
    }
    if (isset($_POST['first_shift'])) {
        $_SESSION['current_index_shift'] = 0;
    } elseif (isset($_POST['last_shift'])) {
        $_SESSION['current_index_shift'] = $total_shifts - 1;
    }
    if (isset($_POST['first_employee'])) {
        $_SESSION['current_index_employee'] = 0;
    } elseif (isset($_POST['last_employee'])) {
        $_SESSION['current_index_employee'] = $total_employees - 1;
    }
    if (isset($_POST['first_stock'])) {
        $_SESSION['current_index_stock'] = 0;
    } elseif (isset($_POST['last_stock'])) {
        $_SESSION['current_index_stock'] = $total_inventory - 1;
    }
    if (isset($_POST['first_message'])) {
        $_SESSION['current_index_message'] = 0;
    } elseif (isset($_POST['last_message'])) {
        $_SESSION['current_index_message'] = $total_messages - 1;
    }
    if (isset($_POST['first_grievance'])) {
        $_SESSION['current_index_grievance'] = 0;
    } elseif (isset($_POST['last_grievance'])) {
        $_SESSION['current_index_grievance'] = $total_grievances - 1;
    }
    if (isset($_POST['first_payment'])) {
        $_SESSION['current_index_payment'] = 0;
    } elseif (isset($_POST['last_payment'])) {
        $_SESSION['current_index_payment'] = $total_payments - 1;
    }
    if (isset($_POST['first_work_period'])) {
        $_SESSION['current_index_work_period'] = 0;
    } elseif (isset($_POST['last_work_period'])) {
        $_SESSION['current_index_work_period'] = $total_work_periods - 1;
    }
    if (isset($_POST['first_break'])) {
        $_SESSION['current_index_break'] = 0;
    } elseif (isset($_POST['last_break'])) {
        $_SESSION['current_index_break'] = $total_breaks - 1;
    }
    if (isset($_POST['first_overtime'])) {
        $_SESSION['current_index_overtime'] = 0;
    } elseif (isset($_POST['last_overtime'])) {
        $_SESSION['current_index_overtime'] = $total_overtime - 1;
    }

    if (isset($_POST['first_machine'])) {
        $_SESSION['current_index_machine'] = 0;
    } elseif (isset($_POST['last_machine'])) {
        $_SESSION['current_index_machine'] = $total_machines - 1;
    }
    
    if (isset($_POST['prev_machine'])) {
        if ($_SESSION['current_index_machine'] > 0) {
            $_SESSION['current_index_machine']--;
        }
    }
    if (isset($_POST['next_machine'])) {
        if ($_SESSION['current_index_machine'] < $total_machines - 1) {
            $_SESSION['current_index_machine']++;
        }
    }

    if (isset($_POST['first_safety_incident'])) {
        $_SESSION['current_index_safety_incident'] = 0;
    } elseif (isset($_POST['last_safety_incident'])) {
        $_SESSION['current_index_safety_incident'] = $total_safety_incidents - 1;
    }
    if (isset($_POST['first_job'])) {
        $_SESSION['current_index_job'] = 0;
    } elseif (isset($_POST['last_job'])) {
        $_SESSION['current_index_job'] = $total_jobs - 1;
    }
    if (isset($_POST['first_machine_status'])) {
        $_SESSION['current_index_machine_status'] = 0;
    } elseif (isset($_POST['last_machine_status'])) {
        $_SESSION['current_index_machine_status'] = $total_machine_statuses - 1;
    }
    if (isset($_POST['prev_machine_type'])) {
        if ($_SESSION['current_index_machine_type'] > 0) {
            $_SESSION['current_index_machine_type']--;
        }
    }
    if (isset($_POST['next_machine_type'])) {
        if ($_SESSION['current_index_machine_type'] < $total_machine_types - 1) {
            $_SESSION['current_index_machine_type']++;
        }
    }
    if (isset($_POST['update_machine_type'])) {
        $machine_type_id = $_POST['machine_type_id'];
        $machine_name = $_POST['machine_name'];
        updateMachineType($mysqli, 'manage_records.php', 'Updated record', $machine_type_id, $machine_name);
    }
    if (isset($_POST['delete_machine_type'])) {
        $machine_type_id = $_POST['machine_type_id'];
        deleteMachineType($mysqli, 'manage_records.php', 'Deleted record', $machine_type_id, $_SESSION['current_index_machine_type'], $total_machine_types);
    }
    if (isset($_POST['prev_branch'])) {
        if ($_SESSION['current_index_branch'] > 0) {
            $_SESSION['current_index_branch']--;
        }
    }
    if (isset($_POST['next_branch'])) {
        if ($_SESSION['current_index_branch'] < $total_branches - 1) {
            $_SESSION['current_index_branch']++;
        }
    }
    if (isset($_POST['update_branch'])) {
        $branch_id = $_POST['branch_id'];
        $branch_country = $_POST['branch_country'];
        $branch_city = $_POST['branch_city'];
        $branch_timezone = $_POST['branch_timezone'];
        $branch_street_address = $_POST['branch_street_address'];
        updateBranch($mysqli, 'manage_records.php', 'Updated record', $branch_id, $branch_country, $branch_city, $branch_timezone, $branch_street_address);
    }
    if (isset($_POST['delete_branch'])) {
        $branch_id = $_POST['branch_id'];
        deleteBranch($mysqli, 'manage_records.php', 'Deleted record', $branch_id, $_SESSION['current_index_branch'], $total_branches);
    }
    if (isset($_POST['prev_shift'])) {
        if ($_SESSION['current_index_shift'] > 0) {
            $_SESSION['current_index_shift']--;
        }
    }
    if (isset($_POST['next_shift'])) {
        if ($_SESSION['current_index_shift'] < $total_shifts - 1) {
            $_SESSION['current_index_shift']++;
        }
    }
    if (isset($_POST['update_shift'])) {
        $shift_id = $_POST['shift_id'];
        $shift_period = $_POST['shift_period'];
        updateShift($mysqli, 'manage_records.php', 'Updated record', $shift_id, $shift_period);
    }
    if (isset($_POST['delete_shift'])) {
        $shift_id = $_POST['shift_id'];
        deleteShift($mysqli,  'manage_records.php', 'Deleted record', $shift_id, $_SESSION['current_index_shift'], $total_shifts);
    }
    if (isset($_POST['prev_employee'])) {
        if ($_SESSION['current_index_employee'] > 0) {
            $_SESSION['current_index_employee']--;
        }
    }
    if (isset($_POST['next_employee'])) {
        if ($_SESSION['current_index_employee'] < $total_employees - 1) {
            $_SESSION['current_index_employee']++;
        }
    }
    if (isset($_POST['update_factory_employees'])) {
        $employee_id = $_POST['employee_id'];
        $employee_branch_id = $_POST['employee_branch_id'];
        $employee_first_name = $_POST['employee_first_name'];
        $employee_last_name = $_POST['employee_last_name'];
        $employee_email_address = $_POST['employee_email_address'];
        $employee_role = $_POST['employee_role'];
        $employee_salary = $_POST['employee_salary'];
        $employee_hourly_rate = $_POST['employee_hourly_rate'];
        updateFactoryEmployee($mysqli, 'manage_records.php', 'Updated record', $employee_id, $employee_branch_id, $employee_first_name, $employee_last_name, $employee_email_address, $employee_role, $employee_salary, $employee_hourly_rate);
    }
    if (isset($_POST['delete_employee'])) {
        $employee_id = $_POST['employee_id'];
        deleteEmployee($mysqli, 'manage_records.php', 'Deleted record', $employee_id, $_SESSION['current_index_employee'], $total_employees);
    }
    if (isset($_POST['prev_stock'])) {
        if ($_SESSION['current_index_stock'] > 0) {
            $_SESSION['current_index_stock']--;
        }
    }
    if (isset($_POST['next_stock'])) {
        if ($_SESSION['current_index_stock'] < $total_inventory - 1) {
            $_SESSION['current_index_stock']++;
        }
    }
    if (isset($_POST['update_stock'])) {
        $stock_id = $_POST['stock_id'];
        $stock_branch_id = $_POST['stock_branch_id'];
        $stock_name = $_POST['stock_name'];
        $stock_quantity = $_POST['stock_quantity'];
        updateStock($mysqli, 'manage_records.php', 'Updated record', $stock_id, $stock_branch_id, $stock_name, $stock_quantity);
    }
    if (isset($_POST['delete_stock'])) {
        $stock_id = $_POST['stock_id'];
        deleteStock($mysqli, 'manage_records.php', 'Deleted record', $stock_id, $_SESSION['current_index_stock'], $total_inventory);
    }
    if (isset($_POST['prev_message'])) {
        if ($_SESSION['current_index_message'] > 0) {
            $_SESSION['current_index_message']--;
        }
    }
    if (isset($_POST['next_message'])) {
        if ($_SESSION['current_index_message'] < $total_messages - 1) {
            $_SESSION['current_index_message']++;
        }
    }
    if (isset($_POST['update_message'])) {
        $message_id = $_POST['message_id'];
        $sender_employee_id = $_POST['sender_employee_id'];
        $recipient_employee_id = $_POST['recipient_employee_id'];
        $conversation_id = $_POST['conversation_id'];
        $message_title = $_POST['message_title'];
        $message_body = $_POST['message_body'];
        $message_date = $_POST['message_date'];
        if ((isset($_FILES['message_attachment'])) && $_FILES['message_attachment']['error'] == UPLOAD_ERR_OK) {
            $message_attachment = $_FILES['message_attachment'];
        } else {
            $message_attachment = NULL;
        }
        updateMessage($mysqli, 'manage_records.php', 'Updated record', $message_id, $sender_employee_id, $recipient_employee_id, $conversation_id, $message_title, $message_body, $message_date, $message_attachment, false);
    }
    if (isset($_POST['delete_message'])) {
        $message_id = $_POST['message_id'];
        deleteMessage($mysqli, 'manage_records.php', 'Deleted record', $message_id, $_SESSION['current_index_message'], $total_messages);
    }
    if (isset($_POST['prev_grievance'])) {
        if ($_SESSION['current_index_grievance'] > 0) {
            $_SESSION['current_index_grievance']--;
        }
    }
    if (isset($_POST['next_grievance'])) {
        if ($_SESSION['current_index_grievance'] < $total_grievances - 1) {
            $_SESSION['current_index_grievance']++;
        }
    }
    if (isset($_POST['update_grievance'])) {
        updateGrievance(
            $mysqli,
            'manage_records.php',
            'Updated record', 
            $_POST['grievance_id'],
            $_POST['grievance_employee_id'],
            $_POST['grievance_date'],
            $_POST['grievance_description'],
            $_POST['grievance_action_taken']
        );
    }
    if (isset($_POST['delete_grievance'])) {
        deleteGrievance(
            $mysqli,
            'manage_records.php', 
            'Updated record', 
            $_POST['grievance_id'],
            $_SESSION['current_index_grievance'],
            $total_grievances
        );
    }
    if (isset($_POST['prev_payment'])) {
        if ($_SESSION['current_index_payment'] > 0) {
            $_SESSION['current_index_payment']--;
        }
    }
    if (isset($_POST['next_payment'])) {
        if ($_SESSION['current_index_payment'] < $total_payments - 1) {
            $_SESSION['current_index_payment']++;
        }
    }
    if (isset($_POST['update_payment'])) {
        $payment_id = $_POST['payment_id'];
        $payment_employee_id = $_POST['payment_employee_id'];
        $payment_amount = $_POST['payment_amount'];
        $payment_date_period_start = $_POST['payment_date_period_start'];
        $payment_date_period_end = $_POST['payment_date_period_end'];
        updatePayment($mysqli, 'manage_records.php', 'Updated record', $payment_id, $payment_employee_id, $payment_amount, $payment_date_period_start, $payment_date_period_end);
    }
    if (isset($_POST['delete_payment'])) {
        $payment_id = $_POST['payment_id'];
        deletePayment($mysqli, 'manage_records.php', 'Deleted record', $payment_id, $_SESSION['current_index_payment'], $total_payments);
    }
    if (isset($_POST['prev_work_period'])) {
        if ($_SESSION['current_index_work_period'] > 0) {
            $_SESSION['current_index_work_period']--;
        }
    }
    if (isset($_POST['next_work_period'])) {
        if ($_SESSION['current_index_work_period'] < $total_work_periods - 1) {
            $_SESSION['current_index_work_period']++;
        }
    }
    if (isset($_POST['update_work_period'])) {
        $work_period_id = $_POST['work_period_id'];
        $working_time_period_employee_id = $_POST['working_time_period_employee_id'];
        $employee_shift_id = $_POST['employee_shift_id'];
        $employee_clock_in_date = $_POST['employee_clock_in_date'];
        $work_period_start = $_POST['work_period_start'];
        $employee_clock_off_date = $_POST['employee_clock_off_date'];
        $work_period_end = $_POST['work_period_end'];
        updateWorkPeriod($mysqli, 'manage_records.php', 'Updated record', $work_period_id, $working_time_period_employee_id, $employee_shift_id, $employee_clock_in_date, $work_period_start, $employee_clock_off_date, $work_period_end);
    }
    if (isset($_POST['delete_work_period'])) {
        $work_period_id = $_POST['work_period_id'];
        deleteWorkPeriod($mysqli, 'manage_records.php', 'Deleted record', $work_period_id, $_SESSION['current_index_work_period'], $total_work_periods);
    }
    if (isset($_POST['prev_break'])) {
        if ($_SESSION['current_index_break'] > 0) {
            $_SESSION['current_index_break']--;
        }
    }
    if (isset($_POST['next_break'])) {
        if ($_SESSION['current_index_break'] < $total_breaks - 1) {
            $_SESSION['current_index_break']++;
        }
    }
    if (isset($_POST['update_break'])) {
        $break_id = $_POST['break_id'];
        $break_taker_employee_id = $_POST['break_taker_employee_id'];
        $break_start_date = $_POST['break_start_date'];
        $break_start_time = $_POST['break_start_time'];
        $break_finish_date = $_POST['break_finish_date'];
        $break_finish_time = $_POST['break_finish_time'];
        $break_type = $_POST['break_type'];
        updateBreak($mysqli, 'manage_records.php', 'Updated record', $break_id, $break_taker_employee_id, $break_start_date, $break_start_time, $break_finish_date, $break_finish_time, $break_type);
    }
    if (isset($_POST['delete_break'])) {
        $break_id = $_POST['break_id'];
        deleteBreak($mysqli, 'manage_records.php', 'Deleted record', $break_id, $_SESSION['current_index_break'], $total_breaks);
    }
    if (isset($_POST['prev_overtime'])) {
        if ($_SESSION['current_index_overtime'] > 0) {
            $_SESSION['current_index_overtime']--;
        }
    }
    if (isset($_POST['next_overtime'])) {
        if ($_SESSION['current_index_overtime'] < $total_overtime - 1) {
            $_SESSION['current_index_overtime']++;
        }
    }
    if (isset($_POST['update_overtime'])) {
        $overtime_id = $_POST['overtime_id'];
        $overtime_employee_id = $_POST['overtime_employee_id'];
        $overtime_start_date = $_POST['overtime_start_date'];
        $overtime_start_time = $_POST['overtime_start_time'];
        $overtime_finish_date = $_POST['overtime_finish_date'];
        $overtime_finish_time = $_POST['overtime_finish_time'];
        updateOvertime($mysqli, 'manage_records.php', 'Updated record', $overtime_id, $overtime_employee_id, $overtime_start_date, $overtime_start_time, $overtime_finish_date, $overtime_finish_time);
    }
    if (isset($_POST['delete_overtime'])) {
        $overtime_id = $_POST['overtime_id'];
        deleteOvertime($mysqli, 'manage_records.php', 'Deleted record', $overtime_id, $_SESSION['current_index_overtime'], $total_overtime);
    }
    if (isset($_POST['prev_safety_incident'])) {
        if ($_SESSION['current_index_safety_incident'] > 0) {
            $_SESSION['current_index_safety_incident']--;
        }
    }
    if (isset($_POST['next_safety_incident'])) {
        if ($_SESSION['current_index_safety_incident'] < $total_safety_incidents - 1) {
            $_SESSION['current_index_safety_incident']++;
        }
    }



    if (isset($_POST['update_machine'])) {
        $machine_id = $_POST['machine_id'];
        $machine_type_id = $_POST['machine_type_id'];
        $branch_id = $_POST['branch_id'];
        updateMachine($mysqli, 'manage_records.php', 'Updated record', $machine_id, $machine_type_id, $branch_id);
    }
    
    if (isset($_POST['delete_machine'])) {
        $machine_id = $_POST['machine_id'];
        deleteMachine($mysqli, 'manage_records.php', 'Deleted record', $machine_id, $_SESSION['current_index_machine'], $total_machines);
    }
    

    if (isset($_POST['update_safety_incident'])) {
        $safety_incident_id = $_POST['safety_incident_id'];
        $incident_employee_id = $_POST['incident_employee_id'];
        $safety_incident_date = $_POST['safety_incident_date'];
        $safety_incident_description = $_POST['incident_description'];
        $incident_outcome = $_POST['incident_outcome'];
        updateSafetyIncident($mysqli, 'manage_records.php', 'Updated record', $safety_incident_id, $incident_employee_id, $safety_incident_date, $safety_incident_description, $incident_outcome);
    }
    if (isset($_POST['delete_safety_incident'])) {
        $safety_incident_id = $_POST['safety_incident_id'];
        deleteSafetyIncident($mysqli, 'manage_records.php', 'Deleted record', $safety_incident_id, $_SESSION['current_index_safety_incident'], $total_safety_incidents);
    }
    if (isset($_POST['prev_job'])) {
        if ($_SESSION['current_index_job'] > 0) {
            $_SESSION['current_index_job']--;
        }
    }
    if (isset($_POST['next_job'])) {
        if ($_SESSION['current_index_job'] < $total_jobs - 1) {
            $_SESSION['current_index_job']++;
        }
    }
    if (isset($_POST['update_job'])) {
        $job_id = $_POST['job_id'];
        $job_employee_id = $_POST['job_employee_id'];
        $job_assigned_date = $_POST['job_assigned_date'];
        $job_completed_date = $_POST['job_completed_date'];
        $job_description = $_POST['job_description'];
        $job_status = $_POST['job_status'];
        $job_task_notes = $_POST['job_task_notes'];
        $job_priority = $_POST['job_priority'];
        updateJob($mysqli, 'manage_records.php', 'Updated record', $job_id, $job_employee_id, $job_assigned_date, $job_completed_date, $job_description, $job_status, $job_task_notes, $job_priority);
    }
    if (isset($_POST['delete_job'])) {
        $job_id = $_POST['job_id'];
        deleteJob($mysqli, 'manage_records.php', 'Deleted record', $job_id, $_SESSION['current_index_job'], $total_jobs);
    }
    if (isset($_POST['prev_machine_status'])) {
        if ($_SESSION['current_index_machine_status'] > 0) {
            $_SESSION['current_index_machine_status']--;
        }
    }
    if (isset($_POST['next_machine_status'])) {
        if ($_SESSION['current_index_machine_status'] < $total_machine_statuses - 1) {
            $_SESSION['current_index_machine_status']++;
        }
    }
    if (isset($_POST['update_machine_status'])) {
        $machine_status_id = $_POST['machine_status_id'];
        $machine_id = $_POST['machine_id'];
        $timestamp = $_POST['timestamp'];
        $temperature = $_POST['temperature'];
        $pressure = $_POST['pressure'];
        $vibration = $_POST['vibration'];
        $humidity = $_POST['humidity'];
        $power_consumption = $_POST['power_consumption'];
        $operational_status = $_POST['operational_status'];
        $error_code = $_POST['error_code'];
        $production_count = $_POST['production_count'];
        $maintenance_log = $_POST['maintenance_log'];
        $speed = $_POST['speed'];
        updateMachineStatus($mysqli, 'manage_records.php', 'Updated record', $machine_status_id, $machine_id, $timestamp, $temperature, $pressure, $vibration, $humidity, $power_consumption, $operational_status, $error_code, $production_count, $maintenance_log, $speed);
    }
    if (isset($_POST['delete_machine_status'])) {
        $machine_status_id = $_POST['machine_status_id'];
        deleteMachineStatus($mysqli, 'manage_records.php', 'Deleted record', $machine_status_id, $_SESSION['current_index_machine_status'], $total_machine_statuses);
    }
    if (isset($_POST['prev_user_account'])) {
        if ($_SESSION['current_index_user_account'] > 0) {
            $_SESSION['current_index_user_account']--;
        }
    }
    if (isset($_POST['next_user_account'])) {
        if ($_SESSION['current_index_user_account'] < $total_user_accounts - 1) {
            $_SESSION['current_index_user_account']++;
        }
    }
    if (isset($_POST['update_user_account'])) {
        $user_account_id = $_POST['user_account_id'];
        $user_employee_id = $_POST['user_employee_id'];
        $user_username = $_POST['user_username'];
        $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
        $user_profile_picture = $_POST['user_profile_picture'];
        updateUserAccount($mysqli, 'manage_records.php', 'Updated record', $user_account_id, $user_employee_id, $user_username, $user_password, $user_profile_picture);
    }
    if (isset($_POST['delete_user_account'])) {
        $user_account_id = $_POST['user_account_id'];
        deleteUserAccount($mysqli, 'manage_records.php', 'Deleted record', $user_account_id, $_SESSION['current_index_user_account'], $total_user_accounts);
    }


    if (isset($_POST['goto_employee'])) {
    $goto_employee_id = $_POST['goto_employee_id'];
    $find_employee_query = "SELECT employee_id FROM factory_employees WHERE employee_id = ?";
    $stmt = $mysqli->prepare($find_employee_query);
    $stmt->bind_param('s', $goto_employee_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employees WHERE employee_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('s', $goto_employee_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_employee'] = $index_row['count'] - 1; 
    } else {
       $_SESSION['message'] = "Employee ID not found";
    }
}
if (isset($_POST['goto_message'])) {
    $goto_message_id = $_POST['goto_message_id'];
    $find_message_query = "SELECT message_id FROM factory_employee_messages WHERE message_id = ?";
    $stmt = $mysqli->prepare($find_message_query);
    $stmt->bind_param('s', $goto_message_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $message_result = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_messages WHERE message_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('s', $goto_message_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_message'] = $index_row['count'] - 1; 
    } else {
       $_SESSION['message'] = "Message ID not found";
    }
}


if (isset($_POST['goto_user_account'])) {
    $goto_user_account_id = $_POST['goto_user_account_id'];
    
    $find_user_account_query = "SELECT user_account_id FROM factory_user_accounts WHERE user_account_id = ?";
    $stmt = $mysqli->prepare($find_user_account_query);
    $stmt->bind_param('i', $goto_user_account_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_account = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_user_accounts WHERE user_account_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_user_account_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        
        $_SESSION['current_index_user_account'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['user_account_message'] = "User Account ID not found";
    }
}




if (isset($_POST['goto_payment'])) {
    $goto_payment_id = $_POST['goto_payment_id'];
    $find_payment_query = "SELECT payment_id FROM factory_employee_payments WHERE payment_id = ?";
    $stmt = $mysqli->prepare($find_payment_query);
    $stmt->bind_param('i', $goto_payment_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_payments WHERE payment_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_payment_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_payment'] = $index_row['count'] - 1; 
    } else {
       $_SESSION['message'] = "Payment ID not found";
    }
}
if (isset($_POST['goto_work_period'])) {
    $goto_work_period_id = $_POST['goto_work_period_id'];
    $find_work_period_query = "SELECT working_time_period_id FROM factory_employee_time_period_worked WHERE working_time_period_id = ?";
    $stmt = $mysqli->prepare($find_work_period_query);
    $stmt->bind_param('i', $goto_work_period_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $work_period = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_time_period_worked WHERE working_time_period_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_work_period_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_work_period'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Work Period ID not found";
    }
}
if (isset($_POST['goto_break'])) {
    $goto_break_id = $_POST['goto_break_id'];
    $find_break_query = "SELECT break_period_id FROM factory_employee_breaks_taken WHERE break_period_id = ?";
    $stmt = $mysqli->prepare($find_break_query);
    $stmt->bind_param('i', $goto_break_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $break = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_breaks_taken WHERE break_period_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_break_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_break'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Break ID not found";
    }
}
if (isset($_POST['goto_overtime'])) {
    $goto_overtime_id = $_POST['goto_overtime_id'];
    $find_overtime_query = "SELECT overtime_period_id FROM factory_employee_overtime WHERE overtime_period_id = ?";
    $stmt = $mysqli->prepare($find_overtime_query);
    $stmt->bind_param('i', $goto_overtime_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $overtime = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_overtime WHERE overtime_period_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_overtime_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_overtime'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Overtime ID not found";
    }
}
if (isset($_POST['goto_job'])) {
    $goto_job_id = $_POST['goto_job_id'];
    $find_job_query = "SELECT job_id FROM factory_jobs WHERE job_id = ?";
    $stmt = $mysqli->prepare($find_job_query);
    $stmt->bind_param('i', $goto_job_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_jobs WHERE job_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_job_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_job'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Job ID not found";
    }
}
if (isset($_POST['goto_machine_type'])) {
    $goto_machine_type_id = $_POST['goto_machine_type_id'];
    $find_machine_query = "SELECT machine_type_id FROM factory_machine_types WHERE machine_type_id = ?";
    $stmt = $mysqli->prepare($find_machine_query);
    $stmt->bind_param('i', $goto_machine_type_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $machine = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_machine_types WHERE machine_type_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_machine_type_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_machine_type'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Machine Type ID not found";
    }
}
if (isset($_POST['goto_branch'])) {
    $goto_branch_id = $_POST['goto_branch_id'];
    $find_branch_query = "SELECT branch_id FROM factory_branches WHERE branch_id = ?";
    $stmt = $mysqli->prepare($find_branch_query);
    $stmt->bind_param('i', $goto_branch_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $branch = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_branches WHERE branch_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_branch_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_branch'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Branch ID not found";
    }
}
if (isset($_POST['goto_shift'])) {
    $goto_shift_id = $_POST['goto_shift_id'];
    $find_shift_query = "SELECT shift_id FROM factory_shifts WHERE shift_id = ?";
    $stmt = $mysqli->prepare($find_shift_query);
    $stmt->bind_param('i', $goto_shift_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $shift = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_shifts WHERE shift_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_shift_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_shift'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Shift ID not found";
    }
}
if (isset($_POST['goto_stock'])) {
    $goto_stock_id = $_POST['goto_stock_id'];
    $find_stock_query = "SELECT stock_id FROM factory_inventory WHERE stock_id = ?";
    $stmt = $mysqli->prepare($find_stock_query);
    $stmt->bind_param('i', $goto_stock_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stock = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_inventory WHERE stock_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_stock_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_stock'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Stock ID not found";
    }
}
if (isset($_POST['goto_grievance'])) {
    $goto_grievance_id = $_POST['goto_grievance_id'];
    $find_grievance_query = "SELECT grievance_id FROM factory_employee_grievances WHERE grievance_id = ?";
    $stmt = $mysqli->prepare($find_grievance_query);
    $stmt->bind_param('i', $goto_grievance_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $grievance = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_employee_grievances WHERE grievance_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_grievance_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_grievance'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Grievance ID not found";
    }
}


if (isset($_POST['goto_machine'])) {
    $goto_machine_id = $_POST['goto_machine_id'];
    $find_machine_query = "SELECT machine_id FROM factory_machines WHERE machine_id = ?";
    $stmt = $mysqli->prepare($find_machine_query);
    $stmt->bind_param('i', $goto_machine_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $machine = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_machines WHERE machine_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_machine_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_machine'] = $index_row['count'] - 1;
    } else {
        $_SESSION['message'] = "Machine ID not found";
    }
}




if (isset($_POST['goto_incident'])) {
    $goto_incident_id = $_POST['goto_incident_id'];
    $find_incident_query = "SELECT incident_id FROM factory_safety_incidents WHERE incident_id = ?";
    $stmt = $mysqli->prepare($find_incident_query);
    $stmt->bind_param('i', $goto_incident_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $incident = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_safety_incidents WHERE incident_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_incident_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_safety_incident'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Incident ID not found";
    }
}
if (isset($_POST['goto_job'])) {
    $goto_job_id = $_POST['goto_job_id'];
    $find_job_query = "SELECT job_id FROM factory_jobs WHERE job_id = ?";
    $stmt = $mysqli->prepare($find_job_query);
    $stmt->bind_param('i', $goto_job_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_jobs WHERE job_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_job_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_job'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Job ID not found";
    }
}
if (isset($_POST['goto_machine_status'])) {
    $goto_machine_status_id = $_POST['goto_machine_status_id'];
    $find_machine_status_query = "SELECT machine_status_id FROM factory_machine_statuses WHERE machine_status_id = ?";
    $stmt = $mysqli->prepare($find_machine_status_query);
    $stmt->bind_param('i', $goto_machine_status_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $machine_status = $result->fetch_assoc();
        $index_query = "SELECT COUNT(*) AS count FROM factory_machine_statuses WHERE machine_status_id <= ?";
        $index_stmt = $mysqli->prepare($index_query);
        $index_stmt->bind_param('i', $goto_machine_status_id);
        $index_stmt->execute();
        $index_result = $index_stmt->get_result();
        $index_row = $index_result->fetch_assoc();
        $_SESSION['current_index_machine_status'] = $index_row['count'] - 1; 
    } else {
        $_SESSION['message'] = "Machine Status ID not found";
    }
}





    $current_index = $_SESSION['current_index_machine_type'];
    $sql = "SELECT * FROM factory_machine_types LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_machine_types_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_branch'];
    $sql = "SELECT * FROM factory_branches LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_branches_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_shift'];
    $sql = "SELECT * FROM factory_shifts LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_shifts_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_employee'];
    $sql = "SELECT * FROM factory_employees LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_employees_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_stock'];
    $sql = "SELECT * FROM factory_inventory LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_inventory_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_message'];
    $sql = "SELECT * FROM factory_employee_messages LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_messages_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_grievance'];
    $sql = "SELECT * FROM factory_employee_grievances LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_grievances_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_payment'];
    $sql = "SELECT * FROM factory_employee_payments LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_payments_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_work_period'];
    $sql = "SELECT * FROM factory_employee_time_period_worked LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_work_periods_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_break'];
    $sql = "SELECT * FROM factory_employee_breaks_taken LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_breaks_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_overtime'];
    $sql = "SELECT * FROM factory_employee_overtime_worked LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_overtimes_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_safety_incident'];
    $sql = "SELECT * FROM factory_safety_incidents LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_safety_incidents_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_job'];
    $sql = "SELECT * FROM factory_jobs LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_jobs_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_machine_status'];
    $sql = "SELECT * FROM factory_machine_statuses LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_machine_statuses_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $current_index = $_SESSION['current_index_user_account'];
    $sql = "SELECT * FROM factory_user_accounts LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_user_accounts_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);


    $current_index = $_SESSION['current_index_machine'];
    $sql = "SELECT * FROM factory_machines LIMIT $current_index, 1";
    $result = mysqli_query($mysqli, $sql);
    $row_of_machines_table = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    
}
