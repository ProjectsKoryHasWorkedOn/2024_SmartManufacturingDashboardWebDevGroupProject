<?php
include_once($DBconnectionFilePath);
include_once($insertRecordPHPFilePath);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selectedTable'])) {
        $tableSelected = $_POST['selectedTable'];
        echo $tableSelected;
    }
    if (
        isset($_POST['branch_country_field']) &&
        isset($_POST['branch_city_field']) &&
        isset($_POST['branch_timezone_field']) &&
        isset($_POST['branch_street_address_field'])
    ) {
        insertBranchRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            branch_country: $_POST['branch_country_field'],
            branch_city: $_POST['branch_city_field'],
            branch_timezone: $_POST['branch_timezone_field'],
            branch_street_address: $_POST['branch_street_address_field']
        );
    }
    // Factory Machine Types
    if (isset($_POST['machine_name_field'])) {
        insertMachineTypeRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            machine_name: $_POST['machine_name_field']
        );
    }
    // Factory shifts
    if (isset($_POST['shift_period_field'])) {
        insertShiftRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            shift_period: $_POST['shift_period_field']
        );
    }
    // Overtime Records
    if (
        isset($_POST['overtime_employee_id_field']) &&
        isset($_POST['overtime_start_date_field']) &&
        isset($_POST['overtime_start_time_field']) &&
        isset($_POST['overtime_finish_date_field']) &&
        isset($_POST['overtime_finish_time_field'])
    ) {
        insertOvertimeRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            overtime_employee_id: $_POST['overtime_employee_id_field'],
            overtime_start_date: $_POST['overtime_start_date_field'],
            overtime_start_time: $_POST['overtime_start_time_field'],
            overtime_finish_date: $_POST['overtime_finish_date_field'],
            overtime_finish_time: $_POST['overtime_finish_time_field']
        );
    }
    // Factory Employees
    if (
        isset($_POST['employee_branch_id_field']) &&
        isset($_POST['employee_first_name_field']) &&
        isset($_POST['employee_last_name_field']) &&
        isset($_POST['employee_email_address_field']) &&
        isset($_POST['employee_role_field']) &&
        isset($_POST['employee_salary_field']) &&
        isset($_POST['employee_hourly_rate_field'])
    ) {
        insertEmployeeRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            employee_branch_id: $_POST['employee_branch_id_field'],
            employee_first_name: $_POST['employee_first_name_field'],
            employee_last_name: $_POST['employee_last_name_field'],
            employee_email_address: $_POST['employee_email_address_field'],
            employee_role: $_POST['employee_role_field'],
            employee_salary: $_POST['employee_salary_field'],
            employee_hourly_rate: $_POST['employee_hourly_rate_field']
        );
    }
    // Factory Inventory
    if (
        isset($_POST['stock_branch_id_field']) &&
        isset($_POST['stock_name_field']) &&
        isset($_POST['stock_quantity_field'])
    ) {
        insertInventoryRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            stock_branch_id: $_POST['stock_branch_id_field'],
            stock_name: $_POST['stock_name_field'],
            stock_quantity: $_POST['stock_quantity_field']
        );
    }
    // Factory Employee Messages
    if (
        isset($_POST['sender_employee_id_field']) &&
        isset($_POST['recipient_employee_id_field']) &&
        isset($_POST['message_title']) &&
        isset($_POST['message_body']) &&
        isset($_POST['message_date']) &&
        isset($_POST['conversation_id_field'])
    ) {
        if ((isset($_FILES['message_attachment'])) && $_FILES['message_attachment']['error'] == UPLOAD_ERR_OK) {
            $message_attachment = $_FILES['message_attachment'];
        } else {
            $message_attachment = NULL;
        }
        insertEmployeeMessageRecord(
            $mysqli,
            redirectPage: "insert_records.php",
            defaultMessage: "Record inserted",
            sender_employee_id: $_POST['sender_employee_id_field'],
            recipient_employee_id: $_POST['recipient_employee_id_field'],
            message_title: $_POST['message_title'],
            message_body: $_POST['message_body'],
            message_date: $_POST['message_date'],
            conversation_id: $_POST['conversation_id_field'],
            message_attachment: $message_attachment,
            message_attachment_required: false
        );
    }
    // Factory User Accounts
    if (
        isset($_POST['user_employee_id_field']) &&
        isset($_POST['user_username_field']) &&
        isset($_POST['user_password_field']) &&
        isset($_POST['user_profile_picture_field'])
    ) {
        insertUserAccountRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            user_employee_id: $_POST['user_employee_id_field'],
            user_username: $_POST['user_username_field'],
            user_password: $_POST['user_password_field'],
            user_profile_picture: $_POST['user_profile_picture_field']
        );
    }
    // Factory Employee Grievances
    if (
        isset($_POST['grievance_employee_id_field']) &&
        isset($_POST['grievance_date_field']) &&
        isset($_POST['grievance_description_field']) &&
        isset($_POST['grievance_action_taken_field'])
    ) {
        insertEmployeeGrievanceRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            grievance_employee_id: $_POST['grievance_employee_id_field'],
            grievance_date: $_POST['grievance_date_field'],
            grievance_description: $_POST['grievance_description_field'],
            grievance_action_taken: $_POST['grievance_action_taken_field']
        );
    }
    // Factory Employee Payments
    if (
        isset($_POST['payment_employee_id_field']) &&
        isset($_POST['payment_date_period_start_field']) &&
        isset($_POST['payment_date_period_end_field']) &&
        isset($_POST['payment_amount_field'])
    ) {
        insertEmployeePaymentRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            payment_employee_id: $_POST['payment_employee_id_field'],
            payment_date_period_start: $_POST['payment_date_period_start_field'],
            payment_date_period_end: $_POST['payment_date_period_end_field'],
            payment_amount: $_POST['payment_amount_field']
        );
    }
    // Factory Employee Time Period Worked
    if (
        isset($_POST['working_time_period_employee_id_field']) &&
        isset($_POST['employee_clock_in_date_field']) &&
        isset($_POST['employee_shift_id_field']) &&
        isset($_POST['employee_clock_in_time_field']) &&
        isset($_POST['employee_clock_off_date_field']) &&
        isset($_POST['employee_clock_off_time_field'])
    ) {
        insertEmployeeTimePeriodWorkedRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            working_time_period_employee_id: $_POST['working_time_period_employee_id_field'],
            employee_shift_id: $_POST['employee_shift_id_field'],
            employee_clock_in_date: $_POST['employee_clock_in_date_field'],
            employee_clock_in_time: $_POST['employee_clock_in_time_field'],
            employee_clock_off_date: $_POST['employee_clock_off_date_field'],
            employee_clock_off_time: $_POST['employee_clock_off_time_field'],
            clocking_in_or_off: "clocking off"
        );
    }
    // Factory Employee Breaks Taken
    if (
        isset($_POST['break_taker_employee_id_field']) &&
        isset($_POST['break_start_date_field']) &&
        isset($_POST['break_start_time_field']) &&
        isset($_POST['break_end_date_field']) &&
        isset($_POST['break_end_time_field']) &&
        isset($_POST['break_type_field'])
    ) {
        insertEmployeeBreaksTakenRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            break_taker_employee_id: $_POST['break_taker_employee_id_field'],
            break_start_date: $_POST['break_start_date_field'],
            break_start_time: $_POST['break_start_time_field'],
            break_finish_date: $_POST['break_end_date_field'],
            break_finish_time: $_POST['break_end_time_field'],
            break_type: $_POST['break_type_field']
        );
    }
    // Factory Employee Safety Incidents
    if (
        isset($_POST['incident_employee_id_field']) &&
        isset($_POST['incident_date_field']) &&
        isset($_POST['incident_description_field']) &&
        isset($_POST['incident_outcome_field'])
    ) {
        insertEmployeeSafetyIncidentRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            incident_employee_id: $_POST['incident_employee_id_field'],
            incident_date: $_POST['incident_date_field'],
            incident_description: $_POST['incident_description_field'],
            incident_outcome: $_POST['incident_outcome_field']
        );
    }
    // Factory Jobs
    if (
        isset($_POST['job_employee_id_field']) &&
        isset($_POST['job_description_field']) &&
        isset($_POST['job_status_field']) &&
        isset($_POST['job_task_notes_field']) &&
        isset($_POST['job_priority_field'])
    ) {
        insertJobRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            job_employee_id: $_POST['job_employee_id_field'],
            job_description: $_POST['job_description_field'],
            job_status: $_POST['job_status_field'],
            job_task_notes: $_POST['job_task_notes_field'],
            job_priority: $_POST['job_priority_field']
        );
    }

if (
    isset($_POST['machine_type_id_field']) &&
    isset($_POST['branch_id_field'])
) {
    insertMachineRecord(
        $mysqli,
        "insert_records.php",
        "Record inserted",
        machine_type_id: $_POST['machine_type_id_field'],
        branch_id: $_POST['branch_id_field']
    );
}
    // Factory machine status
    if (
        isset($_POST['machine_id_field']) &&
        isset($_POST['timestamp_field']) &&
        isset($_POST['temperature_field']) &&
        isset($_POST['pressure_field']) &&
        isset($_POST['vibration_field']) &&
        isset($_POST['humidity_field']) &&
        isset($_POST['power_consumption_field']) &&
        isset($_POST['operational_status_field']) &&
        isset($_POST['error_code_field']) &&
        isset($_POST['production_count_field']) &&
        isset($_POST['maintenance_log_field']) &&
        isset($_POST['speed_field'])
    ) {
        insertMachineStatusesRecord(
            $mysqli,
            "insert_records.php",
            "Record inserted",
            machine_id: $_POST['machine_id_field'],
            timestamp: $_POST['timestamp_field'],
            temperature: $_POST['temperature_field'],
            pressure: $_POST['pressure_field'],
            vibration: $_POST['vibration_field'],
            humidity: $_POST['humidity_field'],
            power_consumption: $_POST['power_consumption_field'],
            operational_status: $_POST['operational_status_field'],
            error_code: $_POST['error_code_field'],
            production_count: $_POST['production_count_field'],
            maintenance_logs: $_POST['maintenance_log_field'],
            speed: $_POST['speed_field']
        );
    }
}