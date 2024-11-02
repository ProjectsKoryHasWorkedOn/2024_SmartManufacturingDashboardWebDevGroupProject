<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($DBconnectionFilePath);
require_once($errorThrowerFilePath);
if (isset($_GET['id'])) {
    $message_id = intval($_GET['id']);
    $sql_query_for_getting_message_attachment_for_a_particular_message = "SELECT message_attachment FROM factory_employee_messages WHERE message_id = ?";
    $stmtfem = $mysqli->prepare($sql_query_for_getting_message_attachment_for_a_particular_message);
    $stmtfem->bind_param('i', $message_id);
    $stmtfem->execute();
    $result = $stmtfem->get_result();
    $row_of_factory_employee_messages_table = $result->fetch_assoc();
    $result->free();
    $stmtfem->close();
    $blobData = $row_of_factory_employee_messages_table['message_attachment'];
    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->buffer($blobData);
    switch ($mimeType) {
        case 'application/pdf':
            $fileExtension = 'pdf';
            $fileName = 'attachment.pdf';
            break;
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            $fileExtension = 'xlsx';
            $fileName = 'attachment.xlsx';
            break;
        default:
            throwAnError(__LINE__, __FILE__, "Data type not recognized");
            break;
    }
    header("Content-Type: $mimeType");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Content-Length: " . strlen($blobData));
    echo $blobData;
    exit;
} else {
    throwAnError(__LINE__, __FILE__, "ID not found");
}
