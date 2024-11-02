<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$areWeShowingAlerts = false;
if (!isset($_SESSION['alertsWillBeShown'])) {
    // Assume that it's checked, the default value if it's not set
    $_SESSION['alertsWillBeShown'] = 1;
} else {
    if (intval($_SESSION['alertsWillBeShown']) === 1) {
        $areWeShowingAlerts = true;
    } else {
        $areWeShowingAlerts = false;
    }
}
if (((int) ($areWeShowingAlerts)) != intval($_SESSION['alertsWillBeShown'])) {
    $additionalErrorDetails = "Mismatch between areWeShowingAlerts JS variable: " . $areWeShowingAlerts . " and alertsWillBeShown will be shown session value: " . intval($_SESSION['alertsWillBeShown'])
    . ". This can be correcting by resetting the alerts slider to your desired setting. You can access the alerts slider via the bottom right hand corner";
    echo "<script>console.log('".addslashes($additionalErrorDetails)."');</script>";
}