<?php
// Have to set this before starting the session
function setSessionLifetime()
{
    // Default lifetime was 1440 / 24 minutes. As seen via echo ini_get('session.gc_maxlifetime');
    ini_set('session.gc_maxlifetime', 60 * 60 * 24); // Set lifetime to one day
}
function setSessionID()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['ip'])) {
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    }
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
}
// Log user out in case it appears to be a different user
function checkSameSession()
{
    if (isset($_SESSION['user_agent']) && isset($_SESSION['ip'])) {
        // Detect browser change
        if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
            error_log("User agent mismatch - Logout");
            header("Location: logout.php");
        }
        // Detect IP change
        if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
            error_log("IP address mismatch - Logout");
            header("Location: logout.php");
        }
    }
}
function clearSession()
{
    if (isset($_SESSION['initiated'])) {
        // Unset all session variables
        $_SESSION = [];
        // If you want to destroy the session as well
        session_destroy();
    }
}
