<?php
// If user hasn't logged in (value is set to false or value not set) then redirect the user to the login page
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] == false) {
    header("Location: login.php");
    exit();
}
// If user has logged in but isn't on dashboard or logged in page
else if (
    ((isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] == true))
    && (basename($_SERVER['SCRIPT_NAME']) != 'dashboard.php') && (basename($_SERVER['SCRIPT_NAME']) != 'logged_in_page.php')
) {
    switch ($_SESSION['employee_role']) {
        case "maintenance worker":
        case "production operator":
            // If production operator hasn't clocked in
            // Redirect them to start_stop_working.php page
            // But not if on this page in case that'd keep triggering a redirect
            if (
                ((!isset($_SESSION['clockInDate'])) || (!isset($_SESSION['clockInTime'])))
                && (basename($_SERVER['SCRIPT_NAME']) != 'start_stop_working.php')
            ) {
                $_SESSION['message'] = 'Need to clock in first';
                header("Location: start_stop_working.php");
                exit();
            }
            // Restrict production operator to these pages
            switch (basename($_SERVER['SCRIPT_NAME'])) {
                case "machine_status.php":
                case "conversation.php":
                case "jobs_to_do.php":
                case "individual_machine.php":
                case "manage_inventory.php":
                case "inbox.php":
                case "start_stop_working.php":
                case "site_user_guide.php":
                    break;
                default:
                    $_SESSION['message'] = "Don't have appropriate privileges to access this page";
                    header("Location: dashboard.php");
                    exit();
            }
            break;
        case "factory manager":
            // Restrict factory manager to these pages
            switch (basename($_SERVER['SCRIPT_NAME'])) {
                case "machine_status.php":
                case "factory_floor_attendance_and_productivity.php":
                case "jobs_to_do.php":
                case "conversation.php":
                case "manage_records.php":
                case "individual_machine.php":
                case "insert_records.php":
                case "job_notes.php":
                case "inbox.php":
                case "site_user_guide.php":
                    break;
                default:
                    $_SESSION['message'] = "Don't have appropriate privileges to access this page";
                    header("Location: dashboard.php");
                    exit();
            }
            break;
        case "internal auditor":
            // Restrict internal auditor to these pages
            switch (basename($_SERVER['SCRIPT_NAME'])) {
                case "site_user_guide.php":
                case "report_generator.php":
                    break;
                default:
                    $_SESSION['message'] = "Don't have appropriate privileges to access this page";
                    header("Location: dashboard.php");
                    exit();
            }
            break;
        case "admin staff":
            // Restrict admin staff to these pages
            switch (basename($_SERVER['SCRIPT_NAME'])) {
                case "payroll.php":
                case "manage_inventory.php":
                case "insert_records.php":
                case "manage_records.php":
                case "managing_records.php":
                case "site_user_guide.php":
                    break;
                default:
                    $_SESSION['message'] = "Don't have appropriate privileges to access this page";
                    header("Location: dashboard.php");
                    exit();
            }
            break;
    }
}