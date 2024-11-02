<?php 
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($DBconnectionFilePath);
include_once($insertRecordPHPFilePath);
require_once($errorThrowerFilePath);
require_once($resetMessageFilePath);
require_once($obtainShowingAlertsValueFilePath);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        (isset($_POST['mtitle'])) && (isset($_POST['mbody'])) && (isset($_POST['mdate']))
        && (isset($_POST['sender_id'])) && (isset($_POST['crecipient'])) 
    ) {
        $message_title = $_POST['mtitle'];
        $message_body = $_POST['mbody'];
        $message_date = $_POST['mdate'];
        $sender_id = $_POST['sender_id'];
        $recipient_id = $_POST['crecipient']; 
        $conversation_id = getNextHighestID($mysqli, 'conversation_id', 'factory_employee_messages');
        if (isset($_FILES['mattachment']) && $_FILES['mattachment']['error'] == UPLOAD_ERR_OK) {
            $message_attachment = $_FILES['mattachment'];
        } else {
            $message_attachment = NULL;
        }
        $page_name = 'conversation.php?id=' . $conversation_id;
        insertEmployeeMessageRecord($mysqli, $page_name, "Created the conversation", $sender_id, $recipient_id, $message_title, $message_body, $message_date, $conversation_id, $message_attachment, false);
    } else {
        throwAnError(__LINE__, __FILE__, NULL);
    }
}