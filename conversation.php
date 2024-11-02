<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
include_once($insertRecordPHPFilePath);
require_once($errorThrowerFilePath);
require_once($resetMessageFilePath);
require_once($obtainShowingAlertsValueFilePath);
// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $conversation_id = intval($_GET['id']);
    $page_name = 'conversation.php?id=' . $conversation_id;
} else {
    throwAnError(__LINE__, __FILE__, "Expected ID of conversation in page URL");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        (isset($_POST['mtitle'])) && (isset($_POST['mbody'])) && (isset($_POST['mdate']))
        && (isset($_POST['sender_id'])) && (isset($_POST['recipient_id'])) && (isset($_POST['conv_id']))
    ) {
        $message_title = $_POST['mtitle'];
        $message_body = $_POST['mbody'];
        $message_date = $_POST['mdate'];
        $sender_id = $_POST['sender_id'];
        $recipient_id = $_POST['recipient_id'];
        $conv_id = $_POST['conv_id'];
        if (isset($_FILES['mattachment']) && $_FILES['mattachment']['error'] == UPLOAD_ERR_OK) {
            $message_attachment = $_FILES['mattachment'];
        } else {
            /* If nothing was uploaded, DB expects a NULL value */
            $message_attachment = NULL;
        }
        insertEmployeeMessageRecord($mysqli, $page_name, "Updated the conversation", $sender_id, $recipient_id, $message_title, $message_body, $message_date, $conv_id, $message_attachment, false);
    } else {
        throwAnError(__LINE__, __FILE__, NULL);
    }
}
?>
<!-- Communication page  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <script src="<?php echo $bannerMessagePath; ?>" defer> </script>
    <title>Conversation</title>
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
            if ($message != '' && $areWeShowingAlerts == true) {
                echo 'updateMessage("' . addslashes($message) . '", "conversation_outcome_banner", "message_text");';
            }
            ?>
        });
    </script>
    <div class="outcome_banner" id="conversation_outcome_banner">
        <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    </div>
    <main>
        <script>
            function returnToInboxPage() {
                window.location.href = 'inbox.php';
            }
        </script>
        <button class="button" id="return_to_inbox_button" onclick="returnToInboxPage();">Return</button>
        <h1>Selected conversation</h1>
        <?php
        $sql_query_for_employee_messages_table = "SELECT * FROM factory_employee_messages
    WHERE 
        conversation_id = '" . $conversation_id . "'";
        $result_of_query_for_employee_messages_table = $mysqli->query($sql_query_for_employee_messages_table);
        if (!$result_of_query_for_employee_messages_table) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        /* Show profile picture of the sender */
        echo "<div id=\"messages_container\">";
        while ($row_of_employee_messages_table = $result_of_query_for_employee_messages_table->fetch_assoc()) {
            $sender_id_of_conversation = $row_of_employee_messages_table['sender_employee_id'];
            /* Get ID that isn't of employee logged in */
            if ((intval($_SESSION['employee_id'])) === $sender_id_of_conversation) {
                $recipient_id_of_conversation = $row_of_employee_messages_table['recipient_employee_id'];
            } else {
                $recipient_id_of_conversation = $sender_id_of_conversation;
            }
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
            echo "<div class=\"message_container\">";
            echo "<div class=\"sender_container\">";
            "<div class=\"sender_profile_picture_container\">";
            echo "<img class=\"conversation_photo\" src=\"" . $row_of_factory_user_accounts_table['user_profile_picture'] . "\">";
            echo "</div>";
            echo "<div class=\"message_contents_container\">";
            echo "<p class=\"sender_name\">" . $row_of_factory_employees_table['employee_first_name'] . " " . $row_of_factory_employees_table['employee_last_name'] . " " . "sent:" . "</p>";
            echo "<p class=\"message_title\">" . $row_of_employee_messages_table['message_title'] . "</p>";
            echo "<p class=\"message_body\">" . $row_of_employee_messages_table['message_body'] . "</p>";
            if ($row_of_employee_messages_table['message_attachment'] != null) {
                echo "<a href='download_attachment.php?id=" . $row_of_employee_messages_table['message_id'] . "'>Download attachment</a>";
            }
            echo "<p class=\"message_date\">" . $row_of_employee_messages_table['message_date'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        ?>
        <?php
        ?>
        <h1>Continue conversation</h1>
       <form id="conversation_form" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="sender_id" value="<?php echo $_SESSION['employee_id']; ?>">
            <input type="hidden" name="recipient_id" value="<?php echo $recipient_id_of_conversation; ?>">
            <input type="hidden" name="conv_id" value="<?php echo $conversation_id; ?>">
            <!-- DB is expecting YYYY-MM-DD -->
            <input type="hidden" name="mdate" value="<?php
            /* It's inaccurate without timezone set. Have to keep setting this **/
            date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
            echo date("Y-m-d"); ?>">
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
            <hr id="message_sender_separator">
            <input class="button" type="submit" value="Send">
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
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>