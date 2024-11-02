<?php
session_start();
if (!isset($_SESSION['branch_timezone'])) {
    error_log('Timezone not found');
}
// These headers ensure that the client knows it’s receiving a stream of events and not a regular HTTP response.
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
$timePeriodToWaitBeforeSendingTime = 2; // 2 seconds
// Wait some time before repeating the loop
// If it's 1 second, too fast. Shows lots of errors
// With readyState: 0 (Connecting)
// Making it a longer period, shows more occassional errors
// With readyState: 2 (Closed)
// Seems to be quite an immediate update to latest time for foreign timezone
while (true) {
    $currentTime = date("d/M/Y h:i a");
    $_SESSION['current_time'] = $currentTime;
    // What output to send
    echo "data: $currentTime\n\n";
    error_log("Sent time: $currentTime");
    // Flush the output buffer
    ob_flush();
    // Send output
    flush();
    sleep($timePeriodToWaitBeforeSendingTime);
}