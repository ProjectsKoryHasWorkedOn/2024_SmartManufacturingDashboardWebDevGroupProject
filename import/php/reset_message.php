<?php
if (isset($_SESSION['message'])) {
    error_log("Message in SESSION['message']: " . $_SESSION['message']);
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = '';
}
