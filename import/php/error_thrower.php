<?php
function throwAnError($line, $page, $optional_further_details)
{
    $error_message = "<b>Description of the problem:</b> Encountered a scenario that shouldn't be happening. See line: " . $line . " in file: " . $page;
    if ($optional_further_details) {
        $error_message = $error_message . '.' . ' ' . $optional_further_details;
    }
    echo "<script>window.location.href = 'error_found.php?error_message="
        . urlencode($error_message) . "';</script>";
    exit();
}