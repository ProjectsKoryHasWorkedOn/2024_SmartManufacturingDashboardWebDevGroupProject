<?php
function checkThatOneOfTwoInputsHasAValue($valueA, $valueB, $redirectPage)
{
    if (empty($valueA) && empty($valueB)) {
        if (empty($valueA)) {
            $valueA = "Missing";
        }
        if (empty($valueA)) {
            $valueB = "Missing";
        }
        $_SESSION['message'] = "Missing a value for one of two values";
        header('Location: ' . $redirectPage);
        exit();
    }
    $valueA = trim($valueA);
    $valueA = htmlspecialchars($valueA);
    $valueB = trim($valueB);
    $valueB = htmlspecialchars($valueB);
    return array($valueA, $valueB);
}
function checkNotBlank($value, $redirectPage)
{
    if (empty($value)) {
        $value = "Missing";
        $_SESSION['message'] = "Missing a value";
        header('Location: ' . $redirectPage);
        exit();
    } else {
        /* for debugging purposes. process of elimination as to which one isn't blank */
        error_log("Found value for: " . $value);
    }
    return $value;
}

function checkDateValid($date, $redirectPage) {
    $dateParts = explode('-', $date);
    
    $year = (int)$dateParts[0];
    $month = (int)$dateParts[1];
    $day = (int)$dateParts[2];

    if (checkdate($month, $day, $year) == false) {
        $message = "Date of: " . $day . "/" . $month . "/" . $year . " is invalid. Put in: " . $date;
        $_SESSION['message'] = $message;
        header('Location: ' . $redirectPage);
        exit();
    }

    return $date;
}


function checkEmail($email, $redirectPage)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid e-mail address";
        header('Location: ' . $redirectPage);
        exit();
    }
    return $email;
}
function checkImageFileType($file, $redirectPage)
{
    $fileType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $file_types_allowed = 'image/';
    // Corrected conditional statement
    if (strpos($fileType, $file_types_allowed) !== 0) {
        $_SESSION['message'] = "File type not allowed";
        header('Location: ' . $redirectPage);
        exit();
    }
    return $file;
}
/* Used for e-mail attachment uploads */
function checkFile($file, $redirectPage, $fileUploadOptional)
{
    // $message_attachment_required = false
    // So file upload optional is true
    $fileUploadOptional = !$fileUploadOptional;
    if (!is_array($file) || empty($file)) {
        if ($fileUploadOptional === false) {
            $_SESSION['message'] = "No file provided";
            header('Location: ' . $redirectPage);
            exit();
        } else {
            return NULL;
        }
    }
    if (!empty($file['name'])) {
        $fileSize = $file['size'];
        /* Check file size is less than 100 MB */
        $max_mb = 100;
        if ($fileSize > $max_mb * 1024 * 1024) {
            $_SESSION['message'] = "File is too big";
            header('Location: ' . $redirectPage);
            exit();
        }
        /* Check if file type is acceptable */
        $fileType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $file_types_allowed = array("application/vnd.ms-excel", "application/pdf", "application/octet-stream", "text/plain");
       if (!in_array($fileType, $file_types_allowed)) {
            $_SESSION['message'] = "File type: " . $fileType . ' ' . "not allowed";
            header('Location: ' . $redirectPage);
            exit();
        }
    }
    return $file['tmp_name'];
}

function checkIfExists($mysqli, $table, $field, $value, $type)
{
    $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
    $stmt = $mysqli->prepare($sql);
    $count = NULL;
    if (!$stmt) {
        throwAnError(__LINE__, __FILE__, $mysqli->error);
    }
    switch ($type) {
        case "i":
            $stmt->bind_param("i", $value);
            break;
        case "s":
            $stmt->bind_param("s", $value);
            break;
    }
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}