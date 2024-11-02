<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
require_once($resetMessageFilePath);
require_once($obtainShowingAlertsValueFilePath);
require_once($managingRecordsFilename);



/** SQL added at last minute  */


$branches = []; 
$query = "SELECT branch_id, branch_street_address, branch_city FROM factory_branches"; 
$result = $mysqli->query($query); 

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $branch_name = $row['branch_street_address'] . ', ' . $row['branch_city'];
        $branches[] = [
            'branch_id' => $row['branch_id'],
            'branch_name' => $branch_name,
        ]; 
    }
}


$employees = []; 
$query = "SELECT employee_id, employee_first_name, employee_last_name FROM factory_employees"; 
$result = $mysqli->query($query); 

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = [
            'employee_id' => $row['employee_id'],
            'employee_name' => $row['employee_first_name'] . ' ' . $row['employee_last_name'],
        ]; 
    }
}




$machine_types = [];
$query = "SELECT machine_type_id, machine_name FROM factory_machine_types"; 
$result = $mysqli->query($query); 

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $machine_types[] = $row; 
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
    <title>Manage existing records</title>
    <script src="import/js/chooseWhatTableToshow.js" defer> </script>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
        <?php
        // Check if the message is set and is a string
        if (isset($message) && is_string($message) && ($message != '')) {
            echo 'updateMessage("' . addslashes($message) . '", "manage_records_outcome_banner", "message_text");';
        }
        ?>
    });
    </script>
    <div class="outcome_banner" id="manage_records_outcome_banner">
        <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    </div>

    <main>
        <h1>Viewing existing records in the table</h1>
        <div id="table_selection_container">
            <label class="checkboxes_label">Select tables to show:</label> <br>
            <?php
if($_SESSION['employee_role'] === "admin staff"){
echo '
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employees" onchange="chooseWhatTableToShow();">
<label for="factory_employees">Factory employees</label> <br>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_messages" onchange="chooseWhatTableToShow();">
<label for="factory_employee_messages">Factory employee messages</label> <br>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_user_accounts" onchange="chooseWhatTableToShow();">
<label for="factory_user_accounts">Factory user accounts</label> <br>
</div>
<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employee_payments" onchange="chooseWhatTableToShow();">
<label for="factory_employee_payments">Factory employee payments</label> <br>
</div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_employee_time_period_worked" onchange="chooseWhatTableToShow();">
                <label for="factory_employee_time_period_worked">Factory employee time period worked</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_employee_breaks_taken" onchange="chooseWhatTableToShow();">
                <label for="factory_employee_breaks_taken">Factory employee breaks taken</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_jobs" onchange="chooseWhatTableToShow();">
                <label for="factory_jobs">Factory jobs</label> <br>
            </div>
            <div>
            <input type="checkbox" name="insertion_form_selection" value="factory_employee_overtime" onchange="chooseWhatTableToShow();">
            <label for="factory_employee_overtime">Factory employee overtime</label>
            </div>
';
}
if($_SESSION['employee_role'] === "factory manager"){
    echo '

<div>
<input type="checkbox" name="insertion_form_selection" value="factory_employees" onchange="chooseWhatTableToShow();">
<label for="factory_employees">Factory employees</label> <br>
</div>


  <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_branches" onchange="chooseWhatTableToShow();">
                <label for="factory_branches">Factory branches</label> <br>
            </div>
    <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_machine_types" onchange="chooseWhatTableToShow();">
                <label for="factory_machine_types">Factory machine types</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_inventory" onchange="chooseWhatTableToShow();">
                <label for="factory_inventory">Factory inventory</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_machines" onchange="chooseWhatTableToShow();">
                <label for="factory_machines">Factory machines</label> <br>
            </div>
                <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_shifts" onchange="chooseWhatTableToShow();">
                <label for="factory_shifts">Factory shifts</label>
                </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_employee_grievances" onchange="chooseWhatTableToShow();">
                <label for="factory_employee_grievances">Factory employee grievances</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_employee_safety_incidents" onchange="chooseWhatTableToShow();">
                <label for="factory_employee_safety_incidents">Factory employee safety incidents</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_jobs" onchange="chooseWhatTableToShow();">
                <label for="factory_jobs">Factory jobs</label> <br>
            </div>
            <div>
                <input type="checkbox" name="insertion_form_selection" value="factory_machine_statuses" onchange="chooseWhatTableToShow();">
                <label for="factory_machine_statuses">Factory machine statuses</label> <br>
            </div>
    ';
}
?>
            <button class="button" onclick="toggleCheckboxes()">Check/Uncheck All</button>
        </div>
        <?php
if($_SESSION['employee_role'] === "admin staff"){



// Query to get the minimum and maximum user account IDs
$min_max_query = "SELECT MIN(user_account_id) AS min_id, MAX(user_account_id) AS max_id FROM factory_user_accounts";
$min_max_result = $mysqli->query($min_max_query);

if (!$min_max_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}

$min_max_row = $min_max_result->fetch_assoc();
$min_user_account_id = $min_max_row['min_id'];
$max_user_account_id = $min_max_row['max_id'];

$min_max_result->free();

// Check if there are records in the table
if (is_null($min_user_account_id) || is_null($max_user_account_id)) {
    // No records found
    $user_account_message = "No user account records available.";
    $min_user_account_id = 0; 
    $max_user_account_id = 0; 
} else {
    $user_account_message = ""; // Clear the message if records are found
}






    echo '
    <form class="db_forms" method="post" id="factory_user_accounts_form" action="" style="display: none;">
    <fieldset>
                    <legend>Factory user account record management form</legend>
                <input class="input_field" type="hidden" name="user_account_index" value="' . $_SESSION['current_index_user_account'] . '">
        <label for="user_account_id">User account ID:</label> <br>
        <input class="input_field" type="text" id="user_account_id" name="user_account_id" value="' . $row_of_user_accounts_table['user_account_id'] . '" readonly><br>




        <label for="user_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="user_employee_id" name="user_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_user_accounts_table['user_employee_id']) ? 'selected' : '') . '>' . 
                    htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }



        echo '</select><br>



        <label for="user_username">Username:</label> <br>
        <input class="input_field" type="text" id="user_username" name="user_username" value="' . $row_of_user_accounts_table['user_username'] . '" required><br>
        <!-- Hide password -->
        <input class="input_field" type="hidden" id="user_password" name="user_password" required>


 
<label for="user_profile_picture">Profile picture URL:</label><br>
<select class="input_field input_class_select_field" id="user_profile_picture" name="user_profile_picture" required>
    <option value="">Select profile picture</option>';

foreach ($users as $user) {
    $selected = ($user['profile_picture'] == $row_of_user_accounts_table['user_profile_picture']) ? 'selected' : '';
    echo '<option value="' . htmlspecialchars($user['profile_picture']) . '" ' . $selected . '>' . htmlspecialchars($user['profile_picture']) . '</option>';
}

echo '
</select><br>
        
        <input class="button smaller_button" type="submit" name="first_user_account" value="First" ' . 
            (($_SESSION['current_index_user_account'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_user_account" value="Previous" ' . 
            (($_SESSION['current_index_user_account'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_user_account" value="Next" ' . 
            (($_SESSION['current_index_user_account'] == $total_user_accounts - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_user_account" value="Last" ' . 
            (($_SESSION['current_index_user_account'] == $total_user_accounts - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" value="Update" name="update_user_account">
        <input class="button smaller_button delete_button" type="submit" name="delete_user_account" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
      
       <br>
                  <p>Viewing record ' . $_SESSION['current_index_user_account'] + 1  . ' of ' . $max_user_account_id . '</p>



        <input class="input_field" type="number" id="goto_user_account_id" name="goto_user_account_id" placeholder="User account ID" 
               min="' . ($min_user_account_id ? $min_user_account_id : 0) . '" max="' . ($max_user_account_id ? $max_user_account_id : 0) . '"' . 
               ($user_account_message ? ' disabled' : '') . '> 
        <input class="button smaller_button" type="submit" name="goto_user_account" value="Jump to"' . 
               ($user_account_message ? ' disabled' : '') . '>
      
      
      
        </fieldset>
    </form>';


        // Query to get the minimum and maximum employee IDs
        $min_max_query = "SELECT MIN(employee_id) AS min_id, MAX(employee_id) AS max_id FROM factory_employees";
        $min_max_result = $mysqli->query($min_max_query);

        if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
        }

        $min_max_row = $min_max_result->fetch_assoc();
        $min_employee_id = $min_max_row['min_id'];
        $max_employee_id = $min_max_row['max_id'];

        $min_max_result->free();








    echo '
    <form class="db_forms" id="factory_employees_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee record management form</legend>
        <input class="input_field" type="hidden" name="employee_index" value="' . $_SESSION['current_index_employee'] . '">
        <label for="employee_id">Employee ID:</label> <br>
        <input class="input_field" type="text" id="employee_id" name="employee_id" value="' . $row_of_employees_table['employee_id'] . '" readonly><br>
        
        
<label for="employee_branch_id">Branch:</label> <br>
<select class="input_field input_class_select_field" id="employee_branch_id" name="employee_branch_id" required>
    ';
    foreach ($branches as $branch) {
        echo '<option value="' . htmlspecialchars($branch['branch_id']) . '" ' . 
            (($branch['branch_id'] == $row_of_employees_table['employee_branch_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($branch['branch_name']) . ' (ID: ' . htmlspecialchars($branch['branch_id']) . ')</option>';





    }
echo '
</select><br>
        
        
        
        <label for="employee_first_name">First name:</label> <br>
        <input class="input_field" type="text" id="employee_first_name" name="employee_first_name" value="' . $row_of_employees_table['employee_first_name'] . '" required><br>
        <label for="employee_last_name">Last name:</label> <br>
        <input class="input_field" type="text" id="employee_last_name" name="employee_last_name" value="' . $row_of_employees_table['employee_last_name'] . '" required><br>
        <label for="employee_email_address">Email address:</label> <br>
        <input class="input_field" type="email" id="employee_email_address" name="employee_email_address" value="' . $row_of_employees_table['employee_email_address'] . '" required><br>
        <label for="employee_role">Role:</label> <br>
        <select class="input_field input_class_select_field" id="employee_role" name="employee_role" required>
            <option value="production operator" ' . (($row_of_employees_table['employee_role'] == 'production operator') ? 'selected' : '') . '>Production Operator</option>
            <option value="admin staff" ' . (($row_of_employees_table['employee_role'] == 'admin staff') ? 'selected' : '') . '>Admin Staff</option>
            <option value="maintenance worker" ' . (($row_of_employees_table['employee_role'] == 'maintenance worker') ? 'selected' : '') . '>Maintenance Worker</option>
            <option value="factory manager" ' . (($row_of_employees_table['employee_role'] == 'factory manager') ? 'selected' : '') . '>Factory Manager</option>
            <option value="internal auditor" ' . (($row_of_employees_table['employee_role'] == 'internal auditor') ? 'selected' : '') . '>Internal Auditor</option>
        </select> <br>
        <label for="employee_salary_field">Employee salary:</label><br>
        <input class="input_field" type="number" name="employee_salary" step="0.01" min="0" value="' . htmlspecialchars($row_of_employees_table['employee_salary']) . '"><br>
        <label for="employee_hourly_rate_field" oninput="validity.valid || (value=\'\');">Employee hourly rate:</label><br>
        <input class="input_field" type="number" name="employee_hourly_rate" step="0.01" min="0" oninput="validity.valid || (value=\'\');" value="' . htmlspecialchars($row_of_employees_table['employee_hourly_rate']) . '"><br>
        

        <input class="button smaller_button" type="submit" name="first_employee" value="First" ' . 
                (($_SESSION['current_index_employee'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_employee" value="Previous" ' . 
                (($_SESSION['current_index_employee'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_employee" value="Next" ' . 
                (($_SESSION['current_index_employee'] == $total_employees - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_employee" value="Last" ' . 
                (($_SESSION['current_index_employee'] == $total_employees - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_factory_employees" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_employee" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
 

        <br>
            <p>Viewing record ' . $_SESSION['current_index_employee'] + 1  . ' of ' . $max_employee_id . '</p>


           <input class="input_field" type="number" id="goto_employee_id" name="goto_employee_id" placeholder="Employee ID" 
           min="' . $min_employee_id . '" max="' . $max_employee_id . '"> 
    <input class="button smaller_button" type="submit" name="goto_employee" value="Jump to">
        
        </fieldset>
    </form>';


    $min_max_query = "SELECT MIN(message_id) AS min_id, MAX(message_id) AS max_id FROM factory_employee_messages";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_message_id = $min_max_row['min_id'];
    $max_message_id = $min_max_row['max_id'];

    if (is_null($min_message_id) || is_null($max_message_id)) {
        $min_message_id = 0; 
        $max_message_id = 0; 
        $_SESSION['message'] = "No messages available.";
    }
    
    $min_max_result->free();

    echo '
    <form class="db_forms" id="factory_employee_messages_form" method="post" action="" enctype="multipart/form-data" style="display: none">
    <fieldset>
        <legend>Factory message record management form</legend>
        <input class="input_field" type="hidden" name="message_index" value="' . $_SESSION['current_index_message'] . '">
        <label for="message_id">Message ID:</label> <br>
        <input class="input_field" type="text" id="message_id" name="message_id" value="' . $row_of_messages_table['message_id'] . '" readonly><br>



           <label for="sender_employee_id">Sender employee:</label> <br>
        <select class="input_field input_class_select_field" id="sender_employee_id" name="sender_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_messages_table['sender_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>



           <label for="recipient_employee_id">Recipient employee:</label> <br>
        <select class="input_field input_class_select_field" id="recipient_employee_id" name="recipient_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_messages_table['recipient_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>

        <label for="conversation_id">Conversation ID:</label> <br>
        <input class="input_field" type="text" id="conversation_id" name="conversation_id" value="' . $row_of_messages_table['conversation_id'] . '" required><br>
        <label for="message_title">Message title:</label> <br>
        <input class="input_field" type="text" id="message_title" name="message_title" value="' . $row_of_messages_table['message_title'] . '" required><br>
        <label for="message_body">Message body:</label> <br>
        <textarea class="input_field" id="message_body" name="message_body" required>' . htmlspecialchars($row_of_messages_table['message_body']) . '</textarea><br>
        <label for="message_date">Message date:</label> <br>
        <input class="input_field" type="date" id="message_date" name="message_date" value="' . $row_of_messages_table['message_date'] . '" required><br>
        <label for="message_attachment">Attachment:</label> <br>
        <input class="input_field" type="file" id="message_attachment" name="message_attachment"><br>
        <input class="button smaller_button" type="submit" name="first_message" value="First" ' . 
                (($_SESSION['current_index_message'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_message" value="Previous" ' . 
                (($_SESSION['current_index_message'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_message" value="Next" ' . 
                (($_SESSION['current_index_message'] == $total_messages - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_message" value="Last" ' . 
                (($_SESSION['current_index_message'] == $total_messages - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_message" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_message" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
        
            <br>

            
                   <p>Viewing record ' . $_SESSION['current_index_message'] + 1  . ' of ' . $max_message_id . '</p>



    <input class="input_field" type="number" id="goto_message_id" name="goto_message_id" placeholder="Message ID" 
           min="<?php echo ($min_message_id ? $min_message_id : 0); ?>" max="<?php echo ($max_message_id ? $max_message_id : 0); ?>"> 
    <input class="button smaller_button" type="submit" name="goto_message" value="Jump to">
    
        
        
        </fieldset>
    </form>
    ';


   // Query to get the minimum and maximum payment IDs
   $min_max_query = "SELECT MIN(payment_id) AS min_id, MAX(payment_id) AS max_id FROM factory_employee_payments";
   $min_max_result = $mysqli->query($min_max_query);
   
   if (!$min_max_result) {
       throwAnError(__LINE__, __FILE__, $mysqli->error);
   }
   
   $min_max_row = $min_max_result->fetch_assoc();
   $min_payment_id = $min_max_row['min_id'];
   $max_payment_id = $min_max_row['max_id'];
   
   $min_max_result->free();
    

    echo '
    <form class="db_forms" id="factory_employee_payments_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee payments record management form</legend>
        <input class="input_field" type="hidden" name="payment_index" value="' . $_SESSION['current_index_payment'] . '">

        <label for="payment_id">Payment ID:</label> <br>
        <input class="input_field" type="text" id="payment_id" name="payment_id" value="' . $row_of_payments_table['payment_id'] . '" readonly><br>


        <label for="payment_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="payment_employee_id" name="payment_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_payments_table['payment_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>


        <label for="payment_amount">Amount:</label> <br>
        <input class="input_field" type="text" id="payment_amount" name="payment_amount" value="' . $row_of_payments_table['payment_amount'] . '" required><br>
        <label for="payment_date_period_start">Payment period start:</label> <br>
        <input class="input_field" type="date" id="payment_date_period_start" name="payment_date_period_start" value="' . $row_of_payments_table['payment_date_period_start'] . '" required><br>
        <label for="payment_date_period_end">Payment period end:</label> <br>
        <input class="input_field" type="date" id="payment_date_period_end" name="payment_date_period_end" value="' . $row_of_payments_table['payment_date_period_end'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_payment" value="First" ' . 
                (($_SESSION['current_index_payment'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_payment" value="Previous" ' . 
                (($_SESSION['current_index_payment'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_payment" value="Next" ' . 
                (($_SESSION['current_index_payment'] == $total_payments - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_payment" value="Last" ' . 
                (($_SESSION['current_index_payment'] == $total_payments - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_payment" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_payment" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    <br>

                   <p>Viewing record ' . $_SESSION['current_index_payment'] + 1  . ' of ' . $max_payment_id . '</p>



    <input class="input_field" type="number" id="goto_payment_id" name="goto_payment_id" placeholder="Payment ID" 
           min="<?php echo $min_payment_id; ?>" max="<?php echo $max_payment_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_payment" value="Jump to">
    

        </fieldset>
    </form>
    ';






    // Query to get the minimum and maximum work period IDs
    $min_max_query = "SELECT MIN(working_time_period_id) AS min_id, MAX(working_time_period_id) AS max_id FROM factory_employee_time_period_worked";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_work_period_id = $min_max_row['min_id'];
    $max_work_period_id = $min_max_row['max_id'];
    
    $min_max_result->free();




    echo '
    <form class="db_forms" id="factory_employee_time_period_worked_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee time period worked for record management form</legend>
        <input class="input_field" type="hidden" name="work_period_index" value="' . $_SESSION['current_index_work_period'] . '">
        <label for="work_period_id">Work period ID:</label> <br>
        <input class="input_field" type="text" id="work_period_id" name="work_period_id" value="' . $row_of_work_periods_table['working_time_period_id'] . '" readonly><br>


       <label for="working_time_period_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="working_time_period_employee_id" name="working_time_period_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_work_periods_table['working_time_period_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>




<label for="employee_shift_id">Shift:</label> <br>
<select class="input_field input_class_select_field" id="employee_shift_id" name="employee_shift_id" required>
    ';
    foreach ($shifts as $shift) {
        echo '<option value="' . htmlspecialchars($shift['shift_id']) . '" ' . 
            (($shift['shift_id'] == $row_of_work_periods_table['employee_shift_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($shift['shift_period']) . '</option>';
    }
echo '
</select><br>



        <label for="employee_clock_in_date">Clock in date:</label> <br>
        <input class="input_field" type="date" id="employee_clock_in_date" name="employee_clock_in_date" value="' . $row_of_work_periods_table['employee_clock_in_date'] . '" required><br>
        <label for="work_period_start">Clock In Time:</label> <br>
        <input class="input_field" type="time" id="work_period_start" name="work_period_start" value="' . $row_of_work_periods_table['employee_clock_in_time'] . '" required><br>
        <label for="employee_clock_off_date">Clock off date:</label> <br>
        <input class="input_field" type="date" id="employee_clock_off_date" name="employee_clock_off_date" value="' . $row_of_work_periods_table['employee_clock_off_date'] . '" required><br>
        <label for="work_period_end">Clock off time:</label> <br>
        <input class="input_field" type="time" id="work_period_end" name="work_period_end" value="' . $row_of_work_periods_table['employee_clock_off_time'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_work_period" value="First" ' . 
                (($_SESSION['current_index_work_period'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_work_period" value="Previous" ' . 
                (($_SESSION['current_index_work_period'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_work_period" value="Next" ' . 
                (($_SESSION['current_index_work_period'] == $total_work_periods - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_work_period" value="Last" ' . 
                (($_SESSION['current_index_work_period'] == $total_work_periods - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_work_period" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_work_period" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    

    <br>
       
               <p>Viewing record ' . $_SESSION['current_index_work_period'] + 1  . ' of ' . $max_work_period_id . '</p>


    <input class="input_field" type="number" id="goto_work_period_id" name="goto_work_period_id" placeholder="Work period ID" 
           min="<?php echo $min_work_period_id; ?>" max="<?php echo $max_work_period_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_work_period" value="Jump to">
    

    
    
        </fieldset>
    </form>
    ';







    // Query to get the minimum and maximum break IDs
    $min_max_query = "SELECT MIN(break_period_id) AS min_id, MAX(break_period_id) AS max_id FROM factory_employee_breaks_taken";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_break_id = $min_max_row['min_id'];
    $max_break_id = $min_max_row['max_id'];
    
    $min_max_result->free();






    echo '
    <form class="db_forms" id="factory_employee_breaks_taken_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee breaks taken record management form</legend>
        <input class="input_field" type="hidden" name="break_index" value="' . $_SESSION['current_index_break'] . '">
        <label for="break_id">Break ID:</label> <br>
        <input class="input_field" type="text" id="break_id" name="break_id" value="' . $row_of_breaks_table['break_period_id'] . '" readonly><br>



       <label for="break_taker_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="break_taker_employee_id" name="break_taker_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_breaks_table['break_taker_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>




        <label for="break_start_date">Start date:</label> <br>
        <input class="input_field" type="date" id="break_start_date" name="break_start_date" value="' . $row_of_breaks_table['break_start_date'] . '" required><br>
        <label for="break_start_time">Start time:</label> <br>
        <input class="input_field" type="time" id="break_start_time" name="break_start_time" value="' . $row_of_breaks_table['break_start_time'] . '" required><br>
        <label for="break_finish_date">Finish date:</label> <br>
        <input class="input_field" type="date" id="break_finish_date" name="break_finish_date" value="' . $row_of_breaks_table['break_finish_date'] . '" required><br>
        <label for="break_finish_time">Finish time:</label> <br>
        <input class="input_field" type="time" id="break_finish_time" name="break_finish_time" value="' . $row_of_breaks_table['break_finish_time'] . '" required><br>
        <label for="break_type">Break type:</label> <br>
        <select class="input_field input_class_select_field" id="break_type" name="break_type" required>
            <option value="rest break" ' . 
                ($row_of_breaks_table['break_type'] == 'rest break' ? 'selected' : '') . '>Rest break</option>
            <option value="meal break" ' . 
                ($row_of_breaks_table['break_type'] == 'meal break' ? 'selected' : '') . '>Meal break</option>
        </select><br>
        <input class="button smaller_button" type="submit" name="first_break" value="First" ' . 
                (($_SESSION['current_index_break'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_break" value="Previous" ' . 
                (($_SESSION['current_index_break'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_break" value="Next" ' . 
                (($_SESSION['current_index_break'] == $total_breaks - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_break" value="Last" ' . 
                (($_SESSION['current_index_break'] == $total_breaks - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_break" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_break" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    
    <br>
    
               <p>Viewing record ' . $_SESSION['current_index_break'] + 1  . ' of ' . $max_break_id . '</p>


    <input class="input_field" type="number" id="goto_break_id" name="goto_break_id" placeholder="Break ID" 
           min="<?php echo $min_break_id; ?>" max="<?php echo $max_break_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_break" value="Jump to">
    

    
    
        </fieldset>
    </form>
    ';





// Query to get the minimum and maximum overtime IDs
$min_max_query = "SELECT MIN(overtime_period_id) AS min_id, MAX(overtime_period_id) AS max_id FROM factory_employee_overtime_worked";
$min_max_result = $mysqli->query($min_max_query);

if (!$min_max_result) {
    throwAnError(__LINE__, __FILE__, $mysqli->error);
}

$min_max_row = $min_max_result->fetch_assoc();
$min_overtime_id = $min_max_row['min_id'];
$max_overtime_id = $min_max_row['max_id'];

$min_max_result->free();

// Check if there are records in the table
if (is_null($min_overtime_id) || is_null($max_overtime_id)) {
    // No records found
    $overtime_message = "No overtime records available.";
    $min_overtime_id = 0; 
    $max_overtime_id = 0; 
} else {
    $overtime_message = ""; // Clear the message if records are found
}



    echo '
<form class="db_forms" id="factory_employee_overtime_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee overtime record management Form</legend>
        <input class="input_field" type="hidden" name="overtime_index" value="' . $_SESSION['current_index_overtime'] . '">
        <label for="overtime_id">Overtime ID:</label> <br>
        <input class="input_field" type="text" id="overtime_id" name="overtime_id" value="' . ($row_of_overtime_table['overtime_period_id'] ?? '') . '" readonly><br>



<label for="overtime_employee_id">Employee:</label> <br>
<select class="input_field input_class_select_field" id="overtime_employee_id" name="overtime_employee_id" required>';
    foreach ($employees as $employee) {
        echo '<option value="' . htmlspecialchars($employee['employee_id']) . '" ' . 
            (($employee['employee_id'] == $row_of_overtime_table['overtime_employee_id']) ? 'selected' : '') . '>' . 
                                htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
    }
echo '</select><br>


        <label for="overtime_start_date">Start date:</label> <br>
        <input class="input_field" type="date" id="overtime_start_date" name="overtime_start_date" value="' . ($row_of_overtime_table['overtime_start_date'] ?? '') . '" required><br>
        <label for="overtime_start_time">Start time:</label> <br>
        <input class="input_field" type="time" id="overtime_start_time" name="overtime_start_time" value="' . ($row_of_overtime_table['overtime_start_time'] ?? '') . '" required><br>
        <label for="overtime_finish_date">Finish date:</label> <br>
        <input class="input_field" type="date" id="overtime_finish_date" name="overtime_finish_date" value="' . ($row_of_overtime_table['overtime_finish_date'] ?? '') . '" required><br>
        <label for="overtime_finish_time">Finish time:</label> <br>
        <input class="input_field" type="time" id="overtime_finish_time" name="overtime_finish_time" value="' . ($row_of_overtime_table['overtime_finish_time'] ?? '') . '" required><br>
        <input class="button smaller_button" type="submit" name="first_overtime" value="First" ' . 
                (($_SESSION['current_index_overtime'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_overtime" value="Previous" ' . 
                (($_SESSION['current_index_overtime'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_overtime" value="Next" ' . 
                (($_SESSION['current_index_overtime'] == $total_overtime - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_overtime" value="Last" ' . 
                (($_SESSION['current_index_overtime'] == $total_overtime - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_overtime" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_overtime" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    
<br>


               <p>Viewing record ' . $_SESSION['current_index_overtime'] + 1  . ' of ' . $max_overtime_id . '</p>


<input class="input_field" type="number" id="goto_overtime_id" name="goto_overtime_id" placeholder="Overtime ID" 
       min="<?php echo $min_overtime_id; ?>" max="<?php echo $max_overtime_id; ?>"> 
<input class="button smaller_button" type="submit" name="goto_overtime" value="Jump to">


    
    
        </fieldset>
</form>
';







    // Query to get the minimum and maximum job IDs
    $min_max_query = "SELECT MIN(job_id) AS min_id, MAX(job_id) AS max_id FROM factory_jobs";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_job_id = $min_max_row['min_id'];
    $max_job_id = $min_max_row['max_id'];
    
    $min_max_result->free();






    echo '
    <form class="db_forms" id="factory_jobs_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory job record management form</legend>
        <input class="input_field" type="hidden" name="job_index" value="' . $_SESSION['current_index_job'] . '">
        <label for="job_id">Job ID:</label> <br>
        <input class="input_field" type="text" id="job_id" name="job_id" value="' . $row_of_jobs_table['job_id'] . '" readonly><br>

             <label for="job_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="job_employee_id" name="job_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_jobs_table['job_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>




        <label for="job_assigned_date">Assigned date:</label> <br>
        <input class="input_field" type="date" id="job_assigned_date" name="job_assigned_date" value="' . $row_of_jobs_table['job_assigned_date'] . '" required><br>
        <label for="job_completed_date">Completed date:</label> <br>
        <input class="input_field" type="date" id="job_completed_date" name="job_completed_date" value="' . $row_of_jobs_table['job_completed_date'] . '"><br>
        <label for="job_description">Description:</label> <br>
        <textarea class="input_field" id="job_description" name="job_description" required>' . $row_of_jobs_table['job_description'] . '</textarea><br>
        <label for="job_status">Job status:</label> <br>
        <input class="input_field" type="text" id="job_status" name="job_status" value="' . $row_of_jobs_table['job_status'] . '" required><br>
        <label for="job_task_notes">Task notes:</label> <br>
        <textarea class="input_field" id="job_task_notes" name="job_task_notes" required>' . $row_of_jobs_table['job_task_notes'] . '</textarea><br>
        <label for="job_priority">Priority (with 1 being highest priority and 5 lowest):</label> <br>
        <input class="input_field" type="number" id="job_priority" name="job_priority" min="1" max="5" value="' . $row_of_jobs_table['job_priority'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_job" value="First" ' . 
                (($_SESSION['current_index_job'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_job" value="Previous" ' . 
                (($_SESSION['current_index_job'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_job" value="Next" ' . 
                (($_SESSION['current_index_job'] == $total_jobs - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_job" value="Last" ' . 
                (($_SESSION['current_index_job'] == $total_jobs - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_job" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_job" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    
    <br>

               <p>Viewing record ' . $_SESSION['current_index_job'] + 1  . ' of ' . $max_job_id . '</p>


    <input class="input_field" type="number" id="goto_job_id" name="goto_job_id" placeholder="Job ID" 
           min="<?php echo $min_job_id; ?>" max="<?php echo $max_job_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_job" value="Jump to">
    
    
    
        </fieldset>
    </form>
    ';











}
if($_SESSION['employee_role'] === "factory manager"){




        // Query to get the minimum and maximum employee IDs
        $min_max_query = "SELECT MIN(employee_id) AS min_id, MAX(employee_id) AS max_id FROM factory_employees";
        $min_max_result = $mysqli->query($min_max_query);

        if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
        }

        $min_max_row = $min_max_result->fetch_assoc();
        $min_employee_id = $min_max_row['min_id'];
        $max_employee_id = $min_max_row['max_id'];

        $min_max_result->free();






    echo '
    <form class="db_forms" id="factory_employees_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee record management form</legend>
        <input class="input_field" type="hidden" name="employee_index" value="' . $_SESSION['current_index_employee'] . '">
        <label for="employee_id">Employee ID:</label> <br>
        <input class="input_field" type="text" id="employee_id" name="employee_id" value="' . $row_of_employees_table['employee_id'] . '" readonly><br>
      
<label for="employee_branch_id">Branch:</label> <br>
<select class="input_field input_class_select_field" id="employee_branch_id" name="employee_branch_id" required>
    ';
    foreach ($branches as $branch) {
        echo '<option value="' . htmlspecialchars($branch['branch_id']) . '" ' . 
            (($branch['branch_id'] == $row_of_employees_table['employee_branch_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($branch['branch_name']) . ' (ID: ' . htmlspecialchars($branch['branch_id']) . ')</option>';
    }
echo '
</select><br>


        
        <label for="employee_first_name">First name:</label> <br>
        <input class="input_field" type="text" id="employee_first_name" name="employee_first_name" value="' . $row_of_employees_table['employee_first_name'] . '" required><br>
        <label for="employee_last_name">Last name:</label> <br>
        <input class="input_field" type="text" id="employee_last_name" name="employee_last_name" value="' . $row_of_employees_table['employee_last_name'] . '" required><br>
        <label for="employee_email_address">Email address:</label> <br>
        <input class="input_field" type="email" id="employee_email_address" name="employee_email_address" value="' . $row_of_employees_table['employee_email_address'] . '" required><br>
        <label for="employee_role">Role:</label> <br>
        <select class="input_field input_class_select_field" id="employee_role" name="employee_role" required>
            <option value="production operator" ' . (($row_of_employees_table['employee_role'] == 'production operator') ? 'selected' : '') . '>Production Operator</option>
            <option value="admin staff" ' . (($row_of_employees_table['employee_role'] == 'admin staff') ? 'selected' : '') . '>Admin Staff</option>
            <option value="maintenance worker" ' . (($row_of_employees_table['employee_role'] == 'maintenance worker') ? 'selected' : '') . '>Maintenance Worker</option>
            <option value="factory manager" ' . (($row_of_employees_table['employee_role'] == 'factory manager') ? 'selected' : '') . '>Factory Manager</option>
            <option value="internal auditor" ' . (($row_of_employees_table['employee_role'] == 'internal auditor') ? 'selected' : '') . '>Internal Auditor</option>
        </select> <br>
        <label for="employee_salary_field">Employee salary:</label><br>
        <input class="input_field" type="number" name="employee_salary" step="0.01" min="0" value="' . htmlspecialchars($row_of_employees_table['employee_salary']) . '"><br>
        <label for="employee_hourly_rate_field" oninput="validity.valid || (value=\'\');">Employee hourly rate:</label><br>
        <input class="input_field" type="number" name="employee_hourly_rate" step="0.01" min="0" oninput="validity.valid || (value=\'\');" value="' . htmlspecialchars($row_of_employees_table['employee_hourly_rate']) . '"><br>
        

        <input class="button smaller_button" type="submit" name="first_employee" value="First" ' . 
                (($_SESSION['current_index_employee'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_employee" value="Previous" ' . 
                (($_SESSION['current_index_employee'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_employee" value="Next" ' . 
                (($_SESSION['current_index_employee'] == $total_employees - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_employee" value="Last" ' . 
                (($_SESSION['current_index_employee'] == $total_employees - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_factory_employees" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_employee" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
 

        <br>
            <p>Viewing record ' . $_SESSION['current_index_employee'] + 1  . ' of ' . $max_employee_id . '</p>


           <input class="input_field" type="number" id="goto_employee_id" name="goto_employee_id" placeholder="Employee ID" 
           min="' . $min_employee_id . '" max="' . $max_employee_id . '"> 
    <input class="button smaller_button" type="submit" name="goto_employee" value="Jump to">
        
        </fieldset>
    </form>';






        $min_max_query = "SELECT MIN(machine_id) AS min_id, MAX(machine_id) AS max_id FROM factory_machines";
        $min_max_result = $mysqli->query($min_max_query);
        
        if (!$min_max_result) {
            throwAnError(__LINE__, __FILE__, $mysqli->error);
        }
        
        $min_max_row = $min_max_result->fetch_assoc();
        $min_machine_id = $min_max_row['min_id'];
        $max_machine_id = $min_max_row['max_id'];
        
        $min_max_result->free();
        







        echo '
        <form class="db_forms" id="factory_machines_form" method="post" action="" style="display: none">
            <fieldset>
                <legend>Factory machine record management form</legend>
                
                <label for="machine_id">Machine ID:</label> <br>
                <input class="input_field" type="text" id="machine_id" name="machine_id" value="' . $row_of_machines_table['machine_id'] . '" readonly><br>
                
    



<label for="machine_type_id">Machine type:</label> <br>
<select class="input_field input_class_select_field" id="machine_type_id" name="machine_type_id" required>
    ';
    foreach ($machine_types as $machine_type) {
        echo '<option value="' . htmlspecialchars($machine_type['machine_type_id']) . '" ' . 
            (($machine_type['machine_type_id'] == $row_of_machines_table['machine_type_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($machine_type['machine_name']) . ' (ID: ' . htmlspecialchars($machine_type['machine_type_id']) . ')</option>';


    }
echo '
</select><br>



        <label for="stock_branch_id">Branch:</label> <br>
<select class="input_field input_class_select_field" id="branch_id" name="branch_id" required>
    ';
    foreach ($branches as $branch) {
        echo '<option value="' . htmlspecialchars($branch['branch_id']) . '" ' . 
            (($branch['branch_id'] == $row_of_machines_table['stock_branch_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($branch['branch_name']) . ' (ID: ' . htmlspecialchars($branch['branch_id']) . ')</option>';
    }
echo '
</select><br>
        
                <input class="button smaller_button" type="submit" name="first_machine" value="First" ' . 
                    (($_SESSION['current_index_machine'] == 0) ? 'disabled' : '') . '>
                <input class="button smaller_button" type="submit" name="prev_machine" value="Previous" ' . 
                    (($_SESSION['current_index_machine'] == 0) ? 'disabled' : '') . '>
                <input class="button smaller_button" type="submit" name="next_machine" value="Next" ' . 
                    (($_SESSION['current_index_machine'] == $total_machines - 1) ? 'disabled' : '') . '>
                <input class="button smaller_button" type="submit" name="last_machine" value="Last" ' . 
                    (($_SESSION['current_index_machine'] == $total_machines - 1) ? 'disabled' : '') . '><br>
                
                <input class="button smaller_button" type="submit" name="update_machine" value="Update">
                <input class="button smaller_button delete_button" type="submit" name="delete_machine" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');"><br>
                
<br>

                 <p>Viewing record ' . $_SESSION['current_index_machine'] + 1  . ' of ' . $max_machine_id . '</p>


                <input class="input_field" type="number" id="goto_machine_id" name="goto_machine_id" placeholder="Machine ID" 
                    min="' . $min_machine_id . '" max="' . $max_machine_id . '"> 
                <input class="button smaller_button" type="submit" name="goto_machine" value="Jump to"><br>
            </fieldset>
        </form>
        ';
        





 $min_max_query = "SELECT MIN(machine_type_id) AS min_id, MAX(machine_type_id) AS max_id FROM factory_machine_types";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_machine_id = $min_max_row['min_id'];
    $max_machine_id = $min_max_row['max_id'];
    
    $min_max_result->free();



    echo '
    <form class="db_forms" id="factory_machine_types_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory machine types record management form</legend>
        <input type="hidden" name="update_factory_machine_types" value="1">
        <input class="input_field" type="hidden" name="machine_type_index" value="' . $_SESSION['current_index_machine_type'] . '">
      
      
        <label for="machine_type_id">Machine type ID:</label> <br>
        <input class="input_field" type="text" id="machine_type_id" name="machine_type_id" value="' . $row_of_machine_types_table['machine_type_id'] . '" readonly><br>


        
        <label for="machine_name">Machine name:</label> <br>
        <input class="input_field" type="text" id="machine_name" name="machine_name" value="' . $row_of_machine_types_table['machine_name'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_machine_type" value="First" ' . 
                (($_SESSION['current_index_machine_type'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_machine_type" value="Previous" ' . 
                (($_SESSION['current_index_machine_type'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_machine_type" value="Next" ' . 
                (($_SESSION['current_index_machine_type'] == $total_machine_types - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_machine_type" value="Last" ' . 
                (($_SESSION['current_index_machine_type'] == $total_machine_types - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_machine_type" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_machine_type" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
    


        <br>

                            <p>Viewing record ' . $_SESSION['current_index_machine_type']+ 1  . ' of ' . $max_machine_id . '</p>

        
        


    <input class="input_field" type="number" id="goto_machine_type_id" name="goto_machine_type_id" placeholder="Machine type ID" 
           min="<?php echo $min_machine_id; ?>" max="<?php echo $max_machine_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_machine_type" value="Jump to">
    

    

    
        </fieldset>
    </form>
    ';



   $min_max_query = "SELECT MIN(branch_id) AS min_id, MAX(branch_id) AS max_id FROM factory_branches";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_branch_id = $min_max_row['min_id'];
    $max_branch_id = $min_max_row['max_id'];
    
    $min_max_result->free();



    echo '
    <form class="db_forms" id="factory_branches_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory branches record management form</legend>
        <input class="input_field" type="hidden" name="branch_index" value="' . $_SESSION['current_index_branch'] . '">
        <label for="branch_id">Branch ID:</label> <br> 
        <input class="input_field" type="text" id="branch_id" name="branch_id" value="' . $row_of_branches_table['branch_id'] . '" readonly> <br>
        <label for="branch_country">Country:</label> <br>
        <input class="input_field" type="text" id="branch_country" name="branch_country" value="' . $row_of_branches_table['branch_country'] . '" required><br>
        <label for="branch_city">City:</label> <br>
        <input class="input_field" type="text" id="branch_city" name="branch_city" value="' . $row_of_branches_table['branch_city'] . '" required><br>
        <label for="branch_timezone">Timezone:</label> <br>
        <input class="input_field" type="text" id="branch_timezone" name="branch_timezone" value="' . $row_of_branches_table['branch_timezone'] . '" required><br>
        <label for="branch_street_address">Street address:</label> <br>
        <input class="input_field" type="text" id="branch_street_address" name="branch_street_address" value="' . $row_of_branches_table['branch_street_address'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_branch" value="First" ' . 
                (($_SESSION['current_index_branch'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_branch" value="Previous" ' . 
                (($_SESSION['current_index_branch'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_branch" value="Next" ' . 
                (($_SESSION['current_index_branch'] == $total_branches - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_branch" value="Last" ' . 
                (($_SESSION['current_index_branch'] == $total_branches - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_branch" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_branch" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
    
        <br>


    <p>Viewing record ' . $_SESSION['current_index_branch']+ 1  . ' of ' . $max_branch_id . '</p>

    <input class="input_field" type="number" id="goto_branch_id" name="goto_branch_id" placeholder="Branch ID" 
           min="<?php echo $min_branch_id; ?>" max="<?php echo $max_branch_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_branch" value="Jump to">
    

    
    
    
    
        </fieldset>
    </form>
    ';


    $min_max_query = "SELECT MIN(shift_id) AS min_id, MAX(shift_id) AS max_id FROM factory_shifts";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_shift_id = $min_max_row['min_id'];
    $max_shift_id = $min_max_row['max_id'];
    
    $min_max_result->free();


    echo '
    <form class="db_forms" id="factory_shifts_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory shift record management form</legend>
        <input class="input_field" type="hidden" name="shift_index" value="' . $_SESSION['current_index_shift'] . '">
        <label for="shift_id">Shift ID:</label> <br>
        <input class="input_field" type="text" id="shift_id" name="shift_id" value="' . $row_of_shifts_table['shift_id'] . '" readonly><br>
        <label for="shift_period">Shift period:</label> <br>
        <select class="input_field input_class_select_field" id="shift_period" name="shift_period" required>
            <option value="day" ' . (($row_of_shifts_table['shift_period'] == 'day') ? 'selected' : '') . '>Day</option>
            <option value="afternoon" ' . (($row_of_shifts_table['shift_period'] == 'afternoon') ? 'selected' : '') . '>Afternoon</option>
            <option value="night" ' . (($row_of_shifts_table['shift_period'] == 'night') ? 'selected' : '') . '>Night</option>
        </select><br>
        <input class="button smaller_button" type="submit" name="first_shift" value="First" ' . 
                (($_SESSION['current_index_shift'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_shift" value="Previous" ' . 
                (($_SESSION['current_index_shift'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_shift" value="Next" ' . 
                (($_SESSION['current_index_shift'] == $total_shifts - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_shift" value="Last" ' . 
                (($_SESSION['current_index_shift'] == $total_shifts - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_shift" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_shift" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
   
       <br>


    <p>Viewing record ' . $_SESSION['current_index_shift']+ 1  . ' of ' . $max_shift_id . '</p>


    <input class="input_field" type="number" id="goto_shift_id" name="goto_shift_id" placeholder="Shift ID" 
           min="<?php echo $min_shift_id; ?>" max="<?php echo $max_shift_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_shift" value="Jump to">
    

   
   
   
        </fieldset>
    </form>
    ';



         $min_max_query = "SELECT MIN(stock_id) AS min_id, MAX(stock_id) AS max_id FROM factory_inventory";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_stock_id = $min_max_row['min_id'];
    $max_stock_id = $min_max_row['max_id'];
    
    $min_max_result->free();


    echo '
    <form class="db_forms" id="factory_inventory_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory inventory record management form</legend>
        <input class="input_field" type="hidden" name="stock_index" value="' . $_SESSION['current_index_stock'] . '">
        <label for="stock_id">Stock ID:</label> <br>
        <input class="input_field" type="text" id="stock_id" name="stock_id" value="' . $row_of_inventory_table['stock_id'] . '" readonly><br>
        
        
        <label for="stock_branch_id">Branch:</label> <br>
<select class="input_field input_class_select_field" id="stock_branch_id" name="stock_branch_id" required>
    ';
    foreach ($branches as $branch) {
        echo '<option value="' . htmlspecialchars($branch['branch_id']) . '" ' . 
            (($branch['branch_id'] == $row_of_inventory_table['stock_branch_id']) ? 'selected' : '') . '>' . 
            htmlspecialchars($branch['branch_name']) . ' (ID: ' . htmlspecialchars($branch['branch_id']) . ')</option>';
    }
echo '
</select><br>
       
       
        <label for="stock_name">Stock name:</label> <br>
        <input class="input_field" type="text" id="stock_name" name="stock_name" value="' . $row_of_inventory_table['stock_name'] . '" required><br>
        <label for="stock_quantity">Stock Quantity:</label> <br>
        <input class="input_field" type="number" id="stock_quantity" name="stock_quantity" value="' . $row_of_inventory_table['stock_quantity'] . '" step="0.01" required><br>
        <input class="button smaller_button" type="submit" name="first_stock" value="First" ' . 
                (($_SESSION['current_index_stock'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_stock" value="Previous" ' . 
                (($_SESSION['current_index_stock'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_stock" value="Next" ' . 
                (($_SESSION['current_index_stock'] == $total_inventory - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_stock" value="Last" ' . 
                (($_SESSION['current_index_stock'] == $total_inventory - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_stock" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_stock" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
   
       <br>

    <p>Viewing record ' . $_SESSION['current_index_stock']+ 1  . ' of ' . $max_stock_id . '</p>


    <input class="input_field" type="number" id="goto_stock_id" name="goto_stock_id" placeholder="Stock ID" 
           min="<?php echo $min_stock_id; ?>" max="<?php echo $max_stock_id; ?>"> 
    <input class="button smaller_button" type="submit" name="goto_stock" value="Jump to">
    
   
   
   
        </fieldset>
    </form>
    ';



    $min_max_query = "SELECT MIN(grievance_id) AS min_id, MAX(grievance_id) AS max_id FROM factory_employee_grievances";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_grievance_id = $min_max_row['min_id'];
    $max_grievance_id = $min_max_row['max_id'];
    
    $min_max_result->free();








    echo '
    <form class="db_forms" id="factory_employee_grievances_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory employee grievances record management form</legend>
        <input class="input_field" type="hidden" name="grievance_index" value="' . $_SESSION['current_index_grievance'] . '">
        <label for="grievance_id">Grievance ID:</label> <br>
        <input class="input_field" type="text" id="grievance_id" name="grievance_id" value="' . $row_of_grievances_table['grievance_id'] . '" readonly><br>


        <label for="grievance_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="grievance_employee_id" name="grievance_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_grievances_table['incident_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>


        <label for="grievance_date">Grievance date:</label> <br>
        <input class="input_field" type="date" id="grievance_date" name="grievance_date" value="' . $row_of_grievances_table['grievance_date'] . '" required><br>
        <label for="grievance_description">Description:</label> <br>
        <textarea class="input_field" id="grievance_description" name="grievance_description" required>' . htmlspecialchars($row_of_grievances_table['grievance_description']) . '</textarea><br>
        <label for="grievance_action_taken">Action taken:</label> <br>
        <textarea class="input_field" id="grievance_action_taken" name="grievance_action_taken" required>' . htmlspecialchars($row_of_grievances_table['grievance_action_taken']) . '</textarea><br>
        <input class="button smaller_button" type="submit" name="first_grievance" value="First" ' . 
                (($_SESSION['current_index_grievance'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_grievance" value="Previous" ' . 
                (($_SESSION['current_index_grievance'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_grievance" value="Next" ' . 
                (($_SESSION['current_index_grievance'] == $total_grievances - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_grievance" value="Last" ' . 
                (($_SESSION['current_index_grievance'] == $total_grievances - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_grievance" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_grievance" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
        <br>


            <p>Viewing record ' . $_SESSION['current_index_grievance']+ 1  . ' of ' . $max_grievance_id . '</p>


            <input class="input_field" type="number" id="goto_grievance_id" name="goto_grievance_id" placeholder="Grievance ID" 
                   min="' . $min_grievance_id . '" max="' . $max_grievance_id . '"> 
            <input class="button smaller_button" type="submit" name="goto_grievance" value="Jump to"><br>
    
    
    
        </fieldset>
    </form>
    ';




    $min_max_query = "SELECT MIN(incident_id) AS min_id, MAX(incident_id) AS max_id FROM factory_safety_incidents";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_incident_id = $min_max_row['min_id'];
    $max_incident_id = $min_max_row['max_id'];
    
    $min_max_result->free();




    echo '
    <form class="db_forms" id="factory_employee_safety_incidents_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory safety incidents record management form</legend>
        <input class="input_field" type="hidden" name="safety_incident_index" value="' . $_SESSION['current_index_safety_incident'] . '">
        <label for="safety_incident_id">Incident ID:</label> <br>
        <input class="input_field" type="text" id="safety_incident_id" name="safety_incident_id" value="' . $row_of_safety_incidents_table['incident_id'] . '" readonly><br>



        <label for="incident_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="incident_employee_id" name="incident_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_safety_incidents_table['incident_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>



        <label for="safety_incident_date">Incident date:</label> <br>
        <input class="input_field" type="date" id="safety_incident_date" name="safety_incident_date" value="' . $row_of_safety_incidents_table['incident_date'] . '" required><br>
        <label for="safety_incident_description">Description:</label> <br>
        <textarea class="input_field" id="safety_incident_description" name="incident_description" required>' . htmlspecialchars($row_of_safety_incidents_table['incident_description']) . '</textarea><br>
        <label for="incident_outcome">Outcome:</label> <br>
        <textarea class="input_field" id="incident_outcome" name="incident_outcome" required>' . htmlspecialchars($row_of_safety_incidents_table['incident_outcome']) . '</textarea><br>
        <input class="button smaller_button" type="submit" name="first_safety_incident" value="First" ' . 
                (($_SESSION['current_index_safety_incident'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_safety_incident" value="Previous" ' . 
                (($_SESSION['current_index_safety_incident'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_safety_incident" value="Next" ' . 
                (($_SESSION['current_index_safety_incident'] == $total_safety_incidents - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_safety_incident" value="Last" ' . 
                (($_SESSION['current_index_safety_incident'] == $total_safety_incidents - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_safety_incident" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_safety_incident" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
        
        <br>

                    <p>Viewing record ' . $_SESSION['current_index_safety_incident']+ 1  . ' of ' . $max_incident_id . '</p>

        
            <input class="input_field" type="number" id="goto_incident_id" name="goto_incident_id" placeholder="Incident ID" 
                   min="' . $min_incident_id . '" max="' . $max_incident_id . '"> 
            <input class="button smaller_button" type="submit" name="goto_incident" value="Jump to"><br>
    
    
    
    
        </fieldset>
    </form>
    ';




    // Query to get the minimum and maximum job IDs
    $min_max_query = "SELECT MIN(job_id) AS min_id, MAX(job_id) AS max_id FROM factory_jobs";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_job_id = $min_max_row['min_id'];
    $max_job_id = $min_max_row['max_id'];
    
    $min_max_result->free();




    



    echo '
    <form class="db_forms" id="factory_jobs_form" method="post" action="" style="display: none">
    <fieldset>
        <legend>Factory job record management form</legend>
        <input class="input_field" type="hidden" name="job_index" value="' . $_SESSION['current_index_job'] . '">
        <label for="job_id">Job ID:</label> <br>
        <input class="input_field" type="text" id="job_id" name="job_id" value="' . $row_of_jobs_table['job_id'] . '" readonly><br>


     <label for="job_employee_id">Employee:</label> <br>
        <select class="input_field input_class_select_field" id="job_employee_id" name="job_employee_id" required>';
            foreach ($employees as $employee) {
                echo '<option value="' . $employee['employee_id'] . '" ' . 
                    (($employee['employee_id'] == $row_of_jobs_table['job_employee_id']) ? 'selected' : '') . '>' . 
                                        htmlspecialchars($employee['employee_name']) . ' (ID: ' . htmlspecialchars($employee['employee_id']) . ')</option>';
            }
        echo '</select><br>


      <label for="job_assigned_date">Assigned date:</label> <br>
        <input class="input_field" type="date" id="job_assigned_date" name="job_assigned_date" value="' . $row_of_jobs_table['job_assigned_date'] . '" required><br>
        <label for="job_completed_date">Completed date:</label> <br>
        <input class="input_field" type="date" id="job_completed_date" name="job_completed_date" value="' . $row_of_jobs_table['job_completed_date'] . '"><br>
        <label for="job_description">Description:</label> <br>

        <textarea class="input_field" id="job_description" name="job_description" required>' . htmlspecialchars($row_of_jobs_table['job_description']) . '</textarea><br>


<label for="job_status">Job status:</label> <br>
<select class="input_field input_class_select_field" id="job_status" name="job_status" required>
    <option value="active" ' . ($row_of_jobs_table['job_status'] == 'active' ? 'selected' : '') . '>Active</option>
    <option value="finished" ' . ($row_of_jobs_table['job_status'] == 'finished' ? 'selected' : '') . '>Finished</option>
    <option value="postponed" ' . ($row_of_jobs_table['job_status'] == 'postponed' ? 'selected' : '') . '>Postponed</option>
</select><br>





        <label for="job_task_notes">Task notes:</label> <br>
        <textarea class="input_field" id="job_task_notes" name="job_task_notes" required>' . htmlspecialchars($row_of_jobs_table['job_task_notes']) . '</textarea><br>
        <label for="job_priority">Priority (with 1 being highest priority and 5 lowest):</label><br>

        <input class="input_field" type="number" id="job_priority" name="job_priority" min="1" max="5" value="' . $row_of_jobs_table['job_priority'] . '" required><br>
        <input class="button smaller_button" type="submit" name="first_job" value="First" ' . 
                (($_SESSION['current_index_job'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="prev_job" value="Previous" ' . 
                (($_SESSION['current_index_job'] == 0) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="next_job" value="Next" ' . 
                (($_SESSION['current_index_job'] == $total_jobs - 1) ? 'disabled' : '') . '>
        <input class="button smaller_button" type="submit" name="last_job" value="Last" ' . 
                (($_SESSION['current_index_job'] == $total_jobs - 1) ? 'disabled' : '') . '>
        <br>
        <input class="button smaller_button" type="submit" name="update_job" value="Update">
        <input class="button smaller_button delete_button" type="submit" name="delete_job" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
    
        
        <br>


        
                    <p>Viewing record ' . $_SESSION['current_index_job']+ 1  . ' of ' . $max_job_id . '</p>

        
     
            <input class="input_field" type="number" id="goto_job_id" name="goto_job_id" placeholder="Job ID" 
                   min="' . $min_job_id . '" max="' . $max_job_id . '"> 
            <input class="button smaller_button" type="submit" name="goto_job" value="Jump to"><br>
    
    
    
        </fieldset>
    </form>
    ';



    // Query to get the minimum and maximum machine status IDs
    $min_max_query = "SELECT MIN(machine_status_id) AS min_id, MAX(machine_status_id) AS max_id FROM factory_machine_statuses";
    $min_max_result = $mysqli->query($min_max_query);
    
    if (!$min_max_result) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    
    $min_max_row = $min_max_result->fetch_assoc();
    $min_machine_status_id = $min_max_row['min_id'];
    $max_machine_status_id = $min_max_row['max_id'];
    
    $min_max_result->free();

    echo '
    <form class="db_forms" id="factory_machine_statuses_form" method="post" action="" style="display: none">
        <fieldset>
            <legend>Factory machine status record management form</legend>
            <input class="input_field" type="hidden" name="machine_status_index" value="' . $_SESSION['current_index_machine_status'] . '">


            <label for="machine_status_id">Status ID:</label> <br>
            <input class="input_field" type="text" id="machine_status_id" name="machine_status_id" value="' . $row_of_machine_statuses_table['machine_status_id'] . '" readonly><br>




<label for="machine_id">Machine:</label> <br>
<select class="input_field input_class_select_field" id="machine_id" name="machine_id" required>
    <option value="">Select Machine</option>'; 

foreach ($machines as $machine) {
    echo '<option value="' . htmlspecialchars($machine['machine_id']) . '" ' . 
        (($machine['machine_id'] == $row_of_machine_statuses_table['machine_id']) ? 'selected' : '') . '>' . 
        htmlspecialchars($machine['machine_name']) . ' (ID: ' . htmlspecialchars($machine['machine_id']) . ')</option>';
}

echo '
</select><br>






<label for="timestamp">Timestamp:</label> <br>
<input class="input_field" type="datetime-local" id="timestamp" name="timestamp" 
       value="' . htmlspecialchars($formatted_timestamp) . '" required><br>




            <label for="temperature">Temperature:</label> <br>
            <input class="input_field" type="number" step="0.01" id="temperature" name="temperature" value="' . $row_of_machine_statuses_table['temperature'] . '" required><br>
            <label for="pressure">Pressure:</label> <br>
            <input class="input_field" type="number" step="0.01" id="pressure" name="pressure" value="' . $row_of_machine_statuses_table['pressure'] . '" required><br>
            <label for="vibration">Vibration:</label> <br>
            <input class="input_field" type="number" step="0.01" id="vibration" name="vibration" value="' . $row_of_machine_statuses_table['vibration'] . '" required><br>
            <label for="humidity">Humidity:</label> <br>
            <input class="input_field" type="number" step="0.01" id="humidity" name="humidity" value="' . $row_of_machine_statuses_table['humidity'] . '" required><br>
            <label for="power_consumption">Power consumption:</label> <br>
            <input class="input_field" type="number" step="0.01" id="power_consumption" name="power_consumption" value="' . $row_of_machine_statuses_table['power_consumption'] . '" required><br>
            <label for="operational_status">Operational status:</label> <br>
            <input class="input_field" type="text" id="operational_status" name="operational_status" value="' . $row_of_machine_statuses_table['operational_status'] . '" required><br>
            <label for="error_code">Error code:</label> <br>
            <input class="input_field" type="text" id="error_code" name="error_code" value="' . $row_of_machine_statuses_table['error_code'] . '"><br>
            <label for="production_count">Production count:</label> <br>
            <input class="input_field" type="number" step="0.01" id="production_count" name="production_count" value="' . $row_of_machine_statuses_table['production_count'] . '" required><br>
            <label for="maintenance_log">Maintenance log:</label> <br>
            <input class="input_field" type="text" id="maintenance_log" name="maintenance_log" value="' . $row_of_machine_statuses_table['maintenance_log'] . '"><br>
            <label for="speed">Speed:</label> <br>
            <input class="input_field" type="number" step="0.01" id="speed" name="speed" value="' . $row_of_machine_statuses_table['speed'] . '"><br>
            

            <input class="button smaller_button" type="submit" name="first_machine_status" value="First" ' . 
                    (($_SESSION['current_index_machine_status'] == 0) ? 'disabled' : '') . '>
            <input class="button smaller_button" type="submit" name="prev_machine_status" value="Previous" ' . 
                    (($_SESSION['current_index_machine_status'] == 0) ? 'disabled' : '') . '>
            <input class="button smaller_button" type="submit" name="next_machine_status" value="Next" ' . 
                    (($_SESSION['current_index_machine_status'] == $total_machine_statuses - 1) ? 'disabled' : '') . '>
            <input class="button smaller_button" type="submit" name="last_machine_status" value="Last" ' . 
                    (($_SESSION['current_index_machine_status'] == $total_machine_statuses - 1) ? 'disabled' : '') . '>
            <br>
            <input class="button smaller_button" type="submit" name="update_machine_status" value="Update">
            <input class="button smaller_button delete_button" type="submit" name="delete_machine_status" value="Delete" onclick="return confirm(\'Are you sure you want to delete this record?\');">
        
            
        <br>

                            <p>Viewing record ' . $_SESSION['current_index_machine_status']+ 1  . ' of ' . $max_machine_status_id . '</p>

        
        
            <input class="input_field" type="number" id="goto_machine_status_id" name="goto_machine_status_id" placeholder="Status ID" 
                   min="' . $min_machine_status_id . '" max="' . $max_machine_status_id . '"> 
            <input class="button smaller_button" type="submit" name="goto_machine_status" value="Jump to"><br>
    
        
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