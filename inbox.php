<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
?>
<!-- Communication page  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Inbox</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    include_once($employeeNamesFilePath);
    ?>
    <main>
        <h1>Start a new conversation</h1>
        <form id="start_a_new_conversation_form" method="POST" action="start_conversation.php"
            enctype="multipart/form-data">
            <input type="hidden" name="sender_id" value="<?php echo $_SESSION['employee_id']; ?>">
            <label for="crecipient">Conversation recipient:</label><br>
            <?php

usort($admin_staff, function($a, $b) {
    return strcmp($a[1], $b[1]);
});

usort($maintenance_workers, function($a, $b) {
    return strcmp($a[1], $b[1]);
});

usort($factory_managers, function($a, $b) {
    return strcmp($a[1], $b[1]);
});

usort($internal_auditors, function($a, $b) {
    return strcmp($a[1], $b[1]);
});

usort($production_operators, function($a, $b) {
    return strcmp($a[1], $b[1]);
});
?>

<select class="input_field input_class_select_field" name="crecipient" required>
    <option value="" disabled selected>Select a recipient</option>

    <?php
    echo "<optgroup label='Admin staff'>";
    foreach ($admin_staff as $employee) {
        echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . ' (' . htmlspecialchars($employee[0]) . ')</option>';
    }
    echo "</optgroup>";

    echo "<optgroup label='Maintenance workers'>";
    foreach ($maintenance_workers as $employee) {
        echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . ' (' . htmlspecialchars($employee[0]) . ')</option>';
    }
    echo "</optgroup>";

    echo "<optgroup label='Factory managers'>";
    foreach ($factory_managers as $employee) {
        echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . ' (' . htmlspecialchars($employee[0]) . ')</option>';
    }
    echo "</optgroup>";

    echo "<optgroup label='Internal auditors'>";
    foreach ($internal_auditors as $employee) {
        echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . ' (' . htmlspecialchars($employee[0]) . ')</option>';
    }
    echo "</optgroup>";

    echo "<optgroup label='Production operators'>";
    foreach ($production_operators as $employee) {
        echo '<option value="' . htmlspecialchars($employee[0]) . '">' . htmlspecialchars($employee[1]) . ' (' . htmlspecialchars($employee[0]) . ')</option>';
    }
    echo "</optgroup>";
    ?>
</select><br>


            <label for="mdate">Message date:</label><br>
            <input type="date" class="input_field" name="mdate" required><br>
            <label for="mtitle">Message title:</label><br>
            <input type="text" id="bigger_input_field_for_message_title" class="input_field" name="mtitle" required><br>
            <label for="mbody">Message body:</label><br>
            <textarea class="input_field" id="bigger_input_field_for_message_body" name="mbody"
                required></textarea><br><br>
            <label for="mattachment">Message attachment:</label><br>
            <label class="button upload_button">
                <input type="file" class="input_field" id="message_attachment_upload_button" name="mattachment"
                    accept=".pdf,.txt,.xls,.xlsx" />
                Upload
            </label><br><br>
            <input class="button" type="submit" value="Create">
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const messageAttachmentUploadButton = document.getElementById('message_attachment_upload_button');
                messageAttachmentUploadButton.addEventListener('change', function () {
                    if (messageAttachmentUploadButton.files.length > 0) {
                        alert("Selected" + " " + messageAttachmentUploadButton.files[0].name);
                    }
                });
            });
        </script>
        <h1>Continue an existing conversation</h1>
        <?php
        $sql_query_for_messages_current_employee_is_in = "SELECT *
FROM 
factory_employee_messages fem
WHERE 
fem.message_date = (
    /* Get newest message in conversation via MAX */
    SELECT MAX(fem_subquery.message_date)
    FROM factory_employee_messages fem_subquery
    WHERE fem_subquery.conversation_id = fem.conversation_id
    AND (fem_subquery.sender_employee_id = ? OR fem_subquery.recipient_employee_id = ?)
) AND (fem.sender_employee_id = ? OR fem.recipient_employee_id = ?);";
        $stmtfem = $mysqli->prepare($sql_query_for_messages_current_employee_is_in);
        $employee_id = $_SESSION['employee_id'];
        /* Cleaner way to provide it with same value lots of times */
        $array_of_employee_ids = array_fill(0, 4, $employee_id);
        $stmtfem->bind_param('iiii', ...$array_of_employee_ids);
        $stmtfem->execute();
        $resultsfem = $stmtfem->get_result();
        $row_number = $resultsfem->num_rows;
        if ($row_number > 0) {
            echo "<div id=\"description_of_columns_of_the_conversations_container\">";
            echo "<div class=\"description_of_columns_of_the_conversations_col\" id=\"last_person_col\">";
            echo "<p id=\"last_person_header\">Last sender</p>";
            echo "</div>";
            echo "<div class=\"description_of_columns_of_the_conversations_col\" id=\"last_message_title_col\">";
            echo "<p>Last message title <span class=\"hide_unless_small_screen_size\">and sender</span></p>";
            echo "</div>";
            echo "<div class=\"description_of_columns_of_the_conversations_col\" id=\"last_message_title_date\">";
            echo "<p>Last message date</p>";
            echo "</div>";
            echo "</div>";
            echo "<div id=\"conversations_container\">";
            while ($row_of_factory_employee_messages_table = $resultsfem->fetch_assoc()) {
                echo "<div class=\"conversation_container\" onclick=\"window.location.href='conversation.php?id=" . urlencode($row_of_factory_employee_messages_table['conversation_id']) . "'\">";
                echo "<div class=\"conversation\">";
                $sender_id_of_conversation = $row_of_factory_employee_messages_table['sender_employee_id'];
                /* Factory employees table */
                $sql_query_for_sender_employee_of_conversation = "SELECT *
            FROM 
            factory_employees
            WHERE
            employee_id = ?
            GROUP BY 
            employee_id;";
                $stmtfe = $mysqli->prepare($sql_query_for_sender_employee_of_conversation);
                $stmtfe->bind_param('i', $sender_id_of_conversation);
                $stmtfe->execute();
                $resultsfe = $stmtfe->get_result();
                $row_of_factory_employees_table = $resultsfe->fetch_assoc();
                $resultsfe->free();
                $stmtfe->close();
                /* Factory user accounts table */
                $sql_query_for_sender_user_account = "SELECT *
            FROM 
            factory_user_accounts
            WHERE
            user_employee_id = ?
            GROUP BY 
            user_employee_id;";
                $stmtfua = $mysqli->prepare($sql_query_for_sender_user_account);
                $stmtfua->bind_param('i', $sender_id_of_conversation);
                $stmtfua->execute();
                $resultsfua = $stmtfua->get_result();
                $row_of_factory_user_accounts_table = $resultsfua->fetch_assoc();
                $resultsfua->free();
                $stmtfua->close();
                echo "<div class=\"last_person_container\">";
                if (
                    ($row_of_factory_employees_table['employee_first_name'] == $_SESSION['employee_first_name']) &&
                    ($row_of_factory_employees_table['employee_last_name'] == $_SESSION['employee_last_name'])
                ) {
                    echo "<p class=\"last_person_name\">You</p>";
                } else {
                    echo "<p class=\"last_person_name\">" . $row_of_factory_employees_table['employee_first_name'] . ' ' . $row_of_factory_employees_table['employee_last_name'] . "</p>";
                }
                echo "<p class=\"last_person_role\">" . ucwords($row_of_factory_employees_table['employee_role']) . "</p>";
                echo "<div class=\"last_person_profile_picture_container\">";
                echo "<img class=\"conversation_photo\" src=\"" . $row_of_factory_user_accounts_table['user_profile_picture'] . "\">";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"conversation_details_container conversation_last_message_title_container\">";
                echo "<p><span class=\"last_message_title\">" . $row_of_factory_employee_messages_table['message_title'] . "</span></p>";
                echo "<p class=\"hide_unless_small_screen_size last_person_name\">" . $row_of_factory_employees_table['employee_first_name'] . ' ' . $row_of_factory_employees_table['employee_last_name'] . "</p>";
                echo "</div>";
                echo "<div class=\"conversation_details_container conversation_last_message_date_container\">";
                echo "<p><span class=\"last_message_date\">" . $row_of_factory_employee_messages_table['message_date'] . "</span></p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No conversations to continue</p>";
        }
        ?>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>