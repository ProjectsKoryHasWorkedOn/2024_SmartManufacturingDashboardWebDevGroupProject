<?php
session_start();
if (!isset($_SESSION['clockInTime'])) {
    error_log("Clock in time not set");
    exit;
}
if (!isset($_SESSION['clockInDate'])) {
    error_log("Clock in date not set");
    exit;
}
if (!isset($_SESSION['branch_timezone'])) {
    error_log('Timezone not found');
    exit;
}
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
$timePeriodToWaitBeforeSendingTime = 2;
$break_duration_minutes = 45;
$remainingTimeHasReachedZero = false;
while (true) {
    $currentTime = new DateTime();
    if ($remainingTimeHasReachedZero == false) {
        $clock_in_datetime_str = $_SESSION['clockInDate'] . ' ' . $_SESSION['clockInTime'];
        $clock_in_datetime_formatted = DateTime::createFromFormat('Y-m-d H:i:s', $clock_in_datetime_str);
        /* Add 8 hours to the clock-in time to get the projected clock off time */
        $interval = new DateInterval('PT8H');
        /* START of use of AI logic **/
        /* Prompt was: How come this isn't working how I expected it work and I described the expected logic */
        $projected_clock_off_time = clone $clock_in_datetime_formatted;
        $projected_clock_off_time->add($interval);
        $total_duration = $clock_in_datetime_formatted->diff($projected_clock_off_time);
        $total_seconds = $total_duration->h * 3600 + $total_duration->i * 60 + $total_duration->s;
        $total_seconds_with_break = $total_seconds + ($break_duration_minutes * 60);
        $worked_duration = $clock_in_datetime_formatted->diff($currentTime);
        $worked_seconds = $worked_duration->h * 3600 + $worked_duration->i * 60 + $worked_duration->s;
        $remaining_duration = $currentTime->diff($projected_clock_off_time);
        $remaining_seconds = $remaining_duration->h * 3600 + $remaining_duration->i * 60 + $remaining_duration->s;
        if ($remaining_seconds < 0) {
            $remaining_seconds = 0;
        }
        $break_seconds = $break_duration_minutes * 60;
        $break_percentage = ($break_seconds / $total_seconds_with_break) * 100;
        $worked_percentage = $total_seconds_with_break > 0 ? ($worked_seconds / $total_seconds_with_break) * 100 : 0;
        $remaining_percentage = max(0, 100 - $worked_percentage - $break_percentage);
        $remaining_time_str = gmdate('H:i:s', $remaining_seconds);
        /* END of use of AI logic **/
        if ($remaining_time_str == '00:00:00') {
            $remainingTimeHasReachedZero = true;
            error_log('$remainingTimeHasReachedZero: ' . $remainingTimeHasReachedZero);
        }
    } else {
        $remaining_time_str = '00:00:00';
        $remaining_percentage = 0;
    }
    echo "data: " . json_encode(array(
        'current_time' => $currentTime->format('d/M/Y h:i a'),
        'clock_in_time' => $clock_in_datetime_formatted->format('Y-m-d H:i:s'),
        'projected_clock_off_time' => $projected_clock_off_time->format('Y-m-d H:i:s'),
        'remaining_time' => $remaining_time_str,
        'worked_percentage' => round($worked_percentage, 2),
        'break_percentage' => round($break_percentage, 2),
        'remaining_percentage' => round($remaining_percentage, 2)
    )) . "\n\n";
    ob_flush();
    flush();
    sleep($timePeriodToWaitBeforeSendingTime);
}
