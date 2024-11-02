<!-- Manage tables page -->
<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
require_once($resetMessageFilePath);
include_once($crudOperationsPHPFilePath);
include_once($hotkeysFilePath);


/** Fetch  */
$machine_types = $mysqli->query("SELECT machine_type_id, machine_name FROM factory_machine_types");


if (!$machine_types) {
    throwAnError(__LINE__,__FILE__, $mysqli->error);
} else {
    while ($row = $machine_types->fetch_assoc()) {
        $machine_type_options[] = $row; 
    }
}



$branches = $mysqli->query("SELECT branch_id, branch_street_address, branch_city FROM factory_branches");
$branch_options = []; 

if (!$branches) {
    throwAnError(__LINE__,__FILE__, $mysqli->error);
} else {
    while ($row = $branches->fetch_assoc()) {
        $branch_options[] = $row; 
    }
}



$employees = $mysqli->query("SELECT employee_id, employee_first_name, employee_last_name FROM factory_employees");

if (!$employees) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
} else {
    $employee_options = []; 
    while ($row = $employees->fetch_assoc()) {
        $employee_options[] = $row; 
    }
}


$machines = [];
$query = "
    SELECT m.machine_id, mt.machine_name 
    FROM factory_machines m
    JOIN factory_machine_types mt ON m.machine_type_id = mt.machine_type_id
";
$result = $mysqli->query($query); 

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $machines[] = [
            'machine_id' => $row['machine_id'], 
            'machine_name' => $row['machine_name']
        ]; 
    }
}




$shifts = [];
$query = "SELECT shift_id, shift_period FROM factory_shifts";
$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $shifts[] = $row; 
    }
}




$users = [];
$query = "
    SELECT u.user_username, e.employee_first_name, e.employee_last_name 
    FROM factory_user_accounts u
    JOIN factory_employees e ON u.user_employee_id = e.employee_id
";
$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $filename = 'import/img/employee_profile_pictures/' . strtolower($row['user_username']) . '_' . strtolower($row['employee_last_name']) . '_' . strtolower($row['employee_first_name']) . '.jpg';
        $users[] = [
            'username' => $row['user_username'],
            'first_name' => $row['employee_first_name'],
            'last_name' => $row['employee_last_name'],
            'profile_picture' => $filename
        ];
    }
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <script src="<?php echo $bannerMessagePath; ?>"> </script>
    <title>Inserting new records</title>
    <script src="import/js/chooseWhatTableToshow.js" defer> </script>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    ?>
    <main>
        <h1>Inserting records into the database</h1>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                <?php
                if (isset($message) && ($message != '')) {
                    echo 'updateMessage("' . addslashes($message) . '", "insertion_of_records_outcome", "message_text");';
                }
                ?>
            });
        </script>
        <div class="outcome_banner" id="insertion_of_records_outcome">
            <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
        </div>
        <div id="table_selection_container">
            <label class="checkboxes_label">Select tables to show:</label>
            <br> 
            <?php
            if ($_SESSION['employee_role'] === "admin staff") {
                echo '
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_employees" onchange="chooseWhatTableToShow();">
    <label for="factory_employees">Factory employees</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_employee_messages" onchange="chooseWhatTableToShow();">
    <label for="factory_employee_messages">Factory employee messages</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_user_accounts" onchange="chooseWhatTableToShow();">
<label for="factory_user_accounts">Factory user accounts</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_payments" onchange="chooseWhatTableToShow();">
<label for="factory_employee_payments">Factory employee payments</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_time_period_worked" onchange="chooseWhatTableToShow();">
<label for="factory_employee_time_period_worked">Factory employee time period worked</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_breaks_taken" onchange="chooseWhatTableToShow();">
<label for="factory_employee_breaks_taken">Factory employee breaks taken</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_jobs" onchange="chooseWhatTableToShow();">
<label for="factory_jobs">Factory jobs</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_overtime" onchange="chooseWhatTableToShow();">
<label for="factory_employee_overtime">Factory employee overtime</label>
</div>
';
            }
            if ($_SESSION['employee_role'] === "factory manager") {
                echo '
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_branches" onchange="chooseWhatTableToShow();">
    <label for="factory_branches">Factory branches</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_machines" onchange="chooseWhatTableToShow();">
    <label for="factory_machines">Factory machines</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_machine_types" onchange="chooseWhatTableToShow();">
    <label for="factory_machine_types">Factory machine types</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_shifts" onchange="chooseWhatTableToShow();">
    <label for="factory_shifts">Factory shifts</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_inventory" onchange="chooseWhatTableToShow();">
    <label for="factory_inventory">Factory inventory</label>
    </div>
    <div>
    <input type="checkbox" name="insertion_form_selection" value="factory_employee_grievances" onchange="chooseWhatTableToShow();">
    <label for="factory_employee_grievances">Factory employee grievances</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_safety_incidents" onchange="chooseWhatTableToShow();">
<label for="factory_employee_safety_incidents">Factory employee safety incidents</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_jobs" onchange="chooseWhatTableToShow();">
<label for="factory_jobs">Factory jobs</label>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_machine_statuses" onchange="chooseWhatTableToShow();">
<label for="factory_machine_statuses">Factory machine statuses</label>
</div>
';
            }
            ?>
            <button class="button" onclick="toggleCheckboxes()">Check/Uncheck All</button>
        </div>
        <?php
        if ($_SESSION['employee_role'] === "admin staff") {
            echo '
    <form class="db_forms" id="factory_employees_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory employee insertion form</legend>
            <!-- ID field calculated -->

             </select><br>
            
                        <label for="employee_branch_id_field">Branch:</label><br>
                        <select class="input_field input_class_select_field" name="employee_branch_id_field" required>
                            <option value="">Select branch</option>';

                            foreach ($branch_options as $branch) {
                                echo '<option value="' . $branch["branch_id"] . '">' . $branch["branch_street_address"] . ', ' . $branch["branch_city"] . ' (ID: ' . $branch['branch_id'] . ')</option>';
                            
                            
                            
                            }

            echo '      </select><br>

            <label for="employee_first_name_field">Employee first name:</label><br>
            <input class="input_field"  type="text" name="employee_first_name_field" required><br>
            <label for="employee_last_name_field">Employee last name:</label><br>
            <input class="input_field"  type="text" name="employee_last_name_field" required><br>
            <label for="employee_email_address_field">Employee e-mail address:</label> <br>
            <input class="input_field"  type="email" name="employee_email_address_field" required> <br>
            <label for="employee_role_field">Employee role:</label><br>
            <select class="input_field input_class_select_field" name="employee_role_field" required>
                <option value="production operator">Production operator</option>
                <option value="admin staff">Admin staff</option>
                <option value="maintenance worker">Maintenance worker</option>
                <option value="factory manager">Factory manager</option>
                <option value="internal auditor">Internal auditor</option>
            </select> <br>
            <label for="employee_salary_field">Employee salary:</label><br>
            <input class="input_field"  type="number" name="employee_salary_field" step="0.01" min="0"><br>
            <label for="employee_hourly_rate_field" oninput="validity.valid || (value=\'\');">Employee hourly rate:</label><br>
            <input class="input_field"  type="number" name="employee_hourly_rate_field" step="0.01" min="0" oninput="validity.valid || (value=\'\');"><br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';



            echo '
    <form class="db_forms" id="factory_employee_messages_form" method="POST" enctype="multipart/form-data" style="display: none">
        <fieldset>
            <legend>Factory message record insertion form</legend>


<label for="sender_employee_id_field">Sender employee:</label><br>
<select class="input_field input_class_select_field" name="sender_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>';


echo '
<label for="recipient_employee_id_field">Recipient employee:</label><br>
<select class="input_field input_class_select_field" name="recipient_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


            <label for="message_title">Message title:</label><br>
            <input class="input_field"  type="text" name="message_title" required><br>
            <label for="message_body">Message body:</label><br>
            <textarea class="input_field" name="message_body" required></textarea><br>
            <label for="message_date">Message date:</label><br>
            <input class="input_field"  type="date" name="message_date" required><br>
            <label for="conversation_id_field">Conversation ID:</label><br>
            <input class="input_field"  type="number" name="conversation_id_field" step="1" min="1" required><br>

            <label for="message_attachment">Message attachment:</label><br>
            <label class="button upload_button">
                <input type="file" class="input_field" name="message_attachment"
                    accept=".pdf,.txt,.xls,.xlsx" />
                Upload
            </label><br><br>




            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_user_accounts_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory user account record insertion form</legend>


<label for="user_employee_id_field">User employee:</label><br>
<select class="input_field input_class_select_field" name="user_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


            <label for="user_username_field">User username:</label><br>
            <input class="input_field"  type="text" name="user_username_field" required><br>
            <label for="user_password_field">User password:</label><br>
            <input class="input_field"  type="password" name="user_password_field" required><br>


<label for="user_profile_picture_field">User profile picture:</label><br>
<select class="input_field input_class_select_field" name="user_profile_picture_field" required>
    <option value="">Select profile picture</option>';

foreach ($users as $user) {
    echo '<option value="' . htmlspecialchars($user['profile_picture']) . '">' . htmlspecialchars($user['profile_picture']) . '</option>';
}

echo '
</select><br>

            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_employee_payments_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory employee payments insertion form</legend>


<label for="payment_employee_id_field">Payment employee:</label><br>
<select class="input_field input_class_select_field" name="payment_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


            <label for="payment_date_period_start_field">Payment date period start:</label><br>
            <input class="input_field"  type="date" name="payment_date_period_start_field" required><br>
            <label for="payment_date_period_end_field">Payment date period end:</label><br>
            <input class="input_field"  type="date" name="payment_date_period_end_field" required><br>
            <label for="payment_amount_field">Payment amount:</label><br>
            <input class="input_field"  type="number" name="payment_amount_field" step="0.01" min="0" required><br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_employee_time_period_worked_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory employee time period worked for record insertion form</legend>


<label for="working_time_period_employee_id_field">Working time period employee:</label><br>
<select class="input_field input_class_select_field" name="working_time_period_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


<label for="employee_shift_id_field">Shift:</label><br>
<select class="input_field input_class_select_field" id="employee_shift_id_field" name="employee_shift_id_field" required>
    <option value="">Select shift</option>'; 

foreach ($shifts as $shift) {
    echo '<option value="' . htmlspecialchars($shift['shift_id']) . '" ' . 
        (($shift['shift_id'] == $row_of_work_periods_table['employee_shift_id']) ? 'selected' : '') . '>' . 
        htmlspecialchars($shift['shift_period']) . '</option>';
}

echo '
</select><br>


            <label for="employee_clock_in_date_field">Employee clock in date:</label><br>
            <input class="input_field"  type="date" name="employee_clock_in_date_field" required><br>
            <label for="employee_clock_in_time_field">Employee clock in time:</label><br>
            <input class="input_field"  type="time" name="employee_clock_in_time_field" required><br>
            <label for="employee_clock_off_date_field">Employee clock off date:</label><br>
            <input class="input_field"  type="date" name="employee_clock_off_date_field" required><br>
            <label for="employee_clock_off_time_field">Employee clock off time:</label><br>
            <input class="input_field"  type="time" name="employee_clock_off_time_field" required><br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_employee_breaks_taken_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory employee breaks taken record insertion form</legend>


<label for="break_taker_employee_id_field">Break taker employee:</label><br>
<select class="input_field input_class_select_field" name="break_taker_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>



            <label for="break_start_date_field">Break start date:</label><br>
            <input class="input_field"  type="date" name="break_start_date_field" required><br>
            <label for="break_start_time_field">Break start time:</label><br>
            <input class="input_field"  type="time" name="break_start_time_field" required><br>
            <label for="break_end_date_field">Break end date:</label><br>
            <input class="input_field"  type="date" name="break_end_date_field" required><br>
            <label for="break_end_time_field">Break end time:</label><br>
            <input class="input_field"  type="time" name="break_end_time_field" required><br>
            <label for="break_type_field">Break type:</label><br>
            <select class="input_field input_class_select_field" name="break_type_field" required>
                <option value="rest break">Rest break</option>
                <option value="meal break">Meal break</option>
            </select> <br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_jobs_form" method="POST" style="display: none">
    <fieldset>
        <legend>Factory job record insertion form</legend>



<label for="job_employee_id_field">Job employee:</label><br>
<select class="input_field input_class_select_field" name="job_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>

        <label for="job_description_field">Job description:</label><br>
        <textarea class="input_field" name="job_description_field" required></textarea><br>
        <label for="job_status_field">Job status:</label><br>
        <select class="input_field input_class_select_field" name="job_status_field" required>
            <option value="active">Active</option>
            <option value="finished">Finished</option>
            <option value="postponed">Postponed</option>
        </select><br>
        <label for="job_task_notes_field">Job task notes:</label><br>
        <textarea class="input_field" name="job_task_notes_field" required></textarea><br>
        <label for="job_priority_field">Job priority (with 1 being highest priority and 5 lowest):</label><br>
        <input class="input_field"  type="number" name="job_priority_field" step="1" min="1" max="5" required><br>
        <input class="button"  type="submit" value="Submit">
    </fieldset>
</form>
    ';
            echo '
<form class="db_forms" id="factory_employee_overtime_form" method="POST" style="display: none">
<fieldset>
    <legend>Factory employee overtime record insertion form</legend>


<label for="overtime_employee_id_field">Overtime employee:</label><br>
<select class="input_field input_class_select_field" name="overtime_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


    <label for="overtime_start_date_field">Overtime start date:</label><br>
    <input class="input_field" type="date" name="overtime_start_date_field" required><br>
    <label for="overtime_start_time_field">Overtime start time:</label><br>
    <input class="input_field" type="time" name="overtime_start_time_field" required><br>
    <label for="overtime_finish_date_field">Overtime finish date:</label><br>
    <input class="input_field" type="date" name="overtime_finish_date_field" required><br>
    <label for="overtime_finish_time_field">Overtime finish time:</label><br>
    <input class="input_field" type="time" name="overtime_finish_time_field" required><br>
    <input class="button" type="submit" value="Submit">
</fieldset>
</form>
    ';
        }
        if ($_SESSION['employee_role'] === "factory manager") {
          
          
          
            echo '
                <form class="db_forms" id="factory_machines_form" method="POST" style="display: none">
                    <fieldset>
                        <legend>Factory machine record insertion form</legend>
            
                        <label for="machine_type_id_field">Machine type:</label><br>
                        <select class="input_field input_class_select_field"  name="machine_type_id_field" required>
                            <option value="">Select machine type</option>';

                            foreach ($machine_type_options as $machine_type) {
                                echo '<option value="' .  $machine_type["machine_type_id"] . '">' . $machine_type["machine_name"] . ' (ID: ' . $machine_type['machine_type_id'] . ')</option>';
                            }




            echo '      </select><br>
            
                        <label for="branch_id_field">Branch:</label><br>
                        <select class="input_field input_class_select_field" name="branch_id_field" required>
                            <option value="">Select branch</option>';

                            foreach ($branch_options as $branch) {
                                echo '<option value="' . $branch["branch_id"] . '">' . $branch["branch_street_address"] . ', ' . $branch["branch_city"] . ' (ID: ' . $branch['branch_id'] . ')</option>';
                            
                            
                            
                            }

            echo '      </select><br>
            
                        <input class="button" type="submit" value="Submit">
            
                    </fieldset>
                </form>
            ';
            
        
          
          
          
          
          
          
          
            echo '
    <form class="db_forms" id="factory_branches_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory branches record insertion form</legend>
            <label for="branch_country_field">Branch country:</label><br>
            <input class="input_field"  type="text" name="branch_country_field" required><br>
            <label for="branch_city_field">Branch city:</label><br>
            <input class="input_field"  type="text" name="branch_city_field" required><br>
            <label for="branch_timezone_field">Branch timezone:</label><br>
            <input class="input_field"  type="text" name="branch_timezone_field" required><br>
            <label for="branch_street_address_field">Branch street address:</label><br>
            <input class="input_field"  type="text" name="branch_street_address_field" required><br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_machine_types_form" method="POST" style="display: none">
        <fieldset>
            <legend>Factory machine types record insertion form</legend>
            <label for="machine_name_field">Machine name:</label><br>
            <input class="input_field"  type="text" name="machine_name_field" required><br>
            <input class="button"  type="submit" value="Submit">
        </fieldset>
    </form>
    ';
            echo '
    <form class="db_forms" id="factory_shifts_form" method="POST" style="display: none">
    <p>Because of how DB is set-up, have constraint on what shifts can be added. Only "day", "afternoon", and "night"</p>
    <fieldset>
        <legend>Factory shift record insertion form</legend>
        <label for="shift_period_field">Shift period:</label><br>
        <input class="input_field"  type="text" name="shift_period_field" required><br>
        <input class="button"  type="submit" value="Submit">
    </fieldset>
</form>
';
            echo '
<form class="db_forms" id="factory_inventory_form" method="POST" style="display: none">
    <fieldset>
        <legend>Factory inventory record insertion form</legend>


        <label for="stock_branch_id_field">Stock branch:</label><br>
        <select class="input_field input_class_select_field" name="stock_branch_id_field" required>
            <option value="">Select branch</option>';
            
            foreach ($branch_options as $branch) {
                echo '<option value="' . $branch["branch_id"] . '">' . $branch["branch_street_address"] . ', ' . $branch["branch_city"] . ' (ID: ' . $branch['branch_id'] . ')</option>';
            }
    echo '  </select><br>



        <label for="stock_name_field">Stock name:</label><br>
        <input class="input_field"  type="text" name="stock_name_field" required><br>
        <label for="stock_quantity_field">Stock quantity:</label><br>
        <input class="input_field"  type="number" name="stock_quantity_field" step="0.01" min="0" required oninput="validity.valid || (value=\'\');"><br>
        <input class="button"  type="submit" value="Submit">
    </fieldset>
</form>
';
            echo '
<form class="db_forms" id="factory_employee_grievances_form" method="POST" style="display: none">
    <fieldset>
        <legend>Factory employee grievances insertion form</legend>

<label for="grievance_employee_id_field">Grievance employee:</label><br>
<select class="input_field input_class_select_field" name="grievance_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>



        <label for="grievance_date_field">Grievance date:</label><br>
        <input class="input_field"  type="date" name="grievance_date_field" required><br>
        <label for="grievance_description_field">Grievance description:</label><br>
        <textarea class="input_field" name="grievance_description_field" required></textarea><br>
        <label for="grievance_action_taken_field">Grievance action taken:</label><br>
        <textarea class="input_field" name="grievance_action_taken_field" required></textarea><br>
        <input class="button"  type="submit" value="Submit">
    </fieldset>
</form>
';
            echo '
<form class="db_forms" id="factory_employee_safety_incidents_form" method="POST" style="display: none">
<fieldset>
    <legend>Factory safety incidents record insertion form</legend>


<label for="incident_employee_id_field">Incident employee:</label><br>
<select class="input_field input_class_select_field" name="incident_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>


    <label for="incident_date_field">Incident date:</label><br>
    <input class="input_field"  type="date" name="incident_date_field" required><br>
    <label for="incident_description_field">Incident description:</label><br>
    <textarea class="input_field" name="incident_description_field" required></textarea><br>
    <label for="incident_outcome_field">Outcome:</label><br>
    <textarea class="input_field" name="incident_outcome_field" required></textarea><br>
    <input class="button"  type="submit" value="Submit">
</fieldset>
</form>
';
            echo '
<form class="db_forms" id="factory_jobs_form" method="POST" style="display: none">
<fieldset>
    <legend>Factory job record insertion form</legend>


<label for="job_employee_id_field">Job employee:</label><br>
<select class="input_field input_class_select_field" name="job_employee_id_field" required>
    <option value="">Select employee</option>';

foreach ($employee_options as $employee) {
    echo '<option value="' . $employee["employee_id"] . '">' . $employee["employee_first_name"] . ' ' . $employee["employee_last_name"] . ' (ID: ' . $employee['employee_id'] . ')</option>';
}

echo '
</select><br>



    <label for="job_description_field">Job description:</label><br>
    <textarea class="input_field" name="job_description_field" required></textarea><br>
    <label for="job_status_field">Job status:</label><br>
    <select class="input_field input_class_select_field" name="job_status_field" required>
        <option value="active">Active</option>
        <option value="finished">Finished</option>
        <option value="postponed">Postponed</option>
    </select><br>
    <label for="job_task_notes_field">Job task notes:</label><br>
    <textarea class="input_field" name="job_task_notes_field" required></textarea><br>
    <label for="job_priority_field">Job priority (with 1 being highest priority and 5 lowest):</label><br>
    <input class="input_field"  type="number" name="job_priority_field" step="1" min="1" max="5" required><br>
    <input class="button"  type="submit" value="Submit">
</fieldset>
</form>
';
            echo '
<form class="db_forms" id="factory_machine_statuses_form" method="POST" style="display: none">
<fieldset>
    <legend>Factory machine status record insertion form</legend>

            <!-- ID field calculated -->

<label for="machine_id_field">Machine:</label><br>
<select class="input_field input_class_select_field" id="machine_id_field" name="machine_id_field" required>
    <option value="">Select machine</option>'; 

foreach ($machines as $machine) {
    echo '<option value="' . htmlspecialchars($machine['machine_id']) . '" ' . 
        (($machine['machine_id'] == $row_of_machine_statuses_table['machine_id']) ? 'selected' : '') . '>' . 
        htmlspecialchars($machine['machine_name']) . ' (ID: ' . htmlspecialchars($machine['machine_id']) . ')</option>';
}

echo '
</select><br>



    <label for="timestamp_field">Timestamp:</label><br>
    <input class="input_field"  type="datetime-local" name="timestamp_field" required><br>




    
    <label for="temperature_field">Machine temperature:</label><br>
    <input class="input_field"  type="number" name="temperature_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="pressure_field">Machine pressure:</label><br>
    <input class="input_field"  type="number" name="pressure_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="vibration_field">Machine vibration:</label><br>
    <input class="input_field"  type="number" name="vibration_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="humidity_field">Machine humidity:</label><br>
    <input class="input_field"  type="number" name="humidity_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="power_consumption_field">Machine power consumption:</label><br>
    <input class="input_field"  type="number" name="power_consumption_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="operational_status_field">Machine operational status:</label><br>
    <select class="input_field input_class_select_field" name="operational_status_field" required>
        <option value="active">Active</option>
        <option value="maintenance">Maintenance</option>
        <option value="idle">Idle</option>
    </select><br>
    <label for="error_code_field">Machine error code:</label><br>
    <input class="input_field"  type="text" name="error_code_field"><br>
    <label for="production_count_field">Machine production count:</label><br>
    <input class="input_field"  type="number" name="production_count_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <label for="maintenance_log_field">Machine maintenance log:</label><br>
    <input class="input_field"  type="text" name="maintenance_log_field" step=0.01><br>
    <label for="speed_field">Machine speed:</label><br>
    <input class="input_field"  type="number" name="speed_field" required step=0.01 min="0" oninput="validity.valid || (value=\'\');"><br>
    <input class="button"  type="submit" value="Submit">
</fieldset>
</form>
';
        }
        ?>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>