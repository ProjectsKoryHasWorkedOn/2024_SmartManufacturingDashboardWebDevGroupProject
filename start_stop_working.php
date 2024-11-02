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
if ($mysqli->connect_error) {
    $errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
    throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
}
if (!isset($_SESSION['breakImageSource'])) {
    $_SESSION['breakImageSource'] = 'url(\'../img/15_minute_break.png\')';
}
if (isset($_POST['button_clicked'])) {
    switch ($_POST['button_clicked']) {
        case 'Select shift':
            if (isset($_SESSION['clockInDate']) && isset($_SESSION['clockInTime'])) {
                if ($areWeShowingAlerts) {
                    $message = "Can't change shift after having clocked in";
                    break;
                }
            } else if (!isset($_SESSION['clockInDate']) && !isset($_SESSION['clockInTime'])) {
                $selectedShift = $_POST['shift_selector'];
                if ($areWeShowingAlerts) {
                    $message = "Selected " . $selectedShift . ' ' . 'shift';
                }
                $sql = "SELECT shift_id FROM factory_shifts WHERE shift_period = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("s", $selectedShift);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $shift_id = $row['shift_id'];
                $stmt->close();
                $_SESSION['shift_id'] = $shift_id;
                break;
            }
        case 'Clock in':
            if (!isset($_SESSION['shift_id'])) {
                if ($areWeShowingAlerts) {
                    $message = "Need to have selected a shift first";
                    break;
                }
            } else {
                if ($areWeShowingAlerts) {
                    $message = "Clocked in";
                }
                date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
                $_SESSION['clockInDate'] = date("Y-m-d");
                $_SESSION['clockInTime'] = date("H:i:s");
                /* Need to put in this time into DB cause it's used for attendance */
                insertEmployeeTimePeriodWorkedRecord($mysqli, 'start_stop_working.php', $message, $_SESSION['employee_id'], $_SESSION['shift_id'], $_SESSION['clockInDate'], $_SESSION['clockInTime'], NULL, NULL, 'clocking in');
            }
            break;
        case 'Overtime start':
            if ($areWeShowingAlerts) {
                $message = "Overtime started";
            }
            date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
            $_SESSION['overtimeStartDate'] = date("Y-m-d");
            $_SESSION['overtimeStartTime'] = date("H:i:s");
            break;
        case 'Clock off':
            if ($areWeShowingAlerts) {
                $message = "Clocked off";
            }
            date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
            $_SESSION['clockOffDate'] = date("Y-m-d");
            $_SESSION['clockOffTime'] = date("H:i:s");
            updateEmployeeClockOffTimePeriodWorkedRecord($mysqli, 'start_stop_working.php', $message, $_SESSION['shift_id'], $_SESSION['clockInDate'], $_SESSION['clockInTime'], $_SESSION['clockOffDate'], $_SESSION['clockOffTime']);
            unset($_SESSION['shift_id']);
            unset($_SESSION['clockInDate']);
            unset($_SESSION['clockInTime']);
            unset($_SESSION['clockOffDate']);
            unset($_SESSION['clockOffTime']);
            break;
        case 'Overtime end':
            if ($areWeShowingAlerts) {
                $message = "Overtime finished";
            }
            date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
            $_SESSION['overtimeEndDate'] = date("Y-m-d");
            $_SESSION['overtimeEndTime'] = date("H:i:s");
            // Insert all of this data into the DB
            insertEmployeeOvertimeWorkedRecord($mysqli, 'start_stop_working.php', $message, $_SESSION['employee_id'], $_SESSION['overtimeStartDate'], $_SESSION['overtimeStartTime'], $_SESSION['overtimeEndDate'], $_SESSION['overtimeEndTime']);
            unset($_SESSION['overtimeStartDate']);
            unset($_SESSION['overtimeStartTime']);
            unset($_SESSION['overtimeEndDate']);
            unset($_SESSION['overtimeEndTime']);
            break;
        case 'Break start':
            if (isset($_SESSION['clockInDate']) && isset($_SESSION['clockInTime'])) {
                if ($areWeShowingAlerts) {
                    $message = "Break started";
                }
                $breakType = "invalid";
                if ($_SESSION['breakImageSource'] == 'url(\'../img/15_minute_break.png\')') {
                    $breakType = "rest break";
                } else if ($_SESSION['breakImageSource'] == 'url(\'../img/30_minute_break.png\')') {
                    $breakType = "meal break";
                } else {
                    throwAnError(__LINE__, __FILE__, NULL);
                }
                $_SESSION['breakType'] = $breakType;
                date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
                $_SESSION['breakStartDate'] = date("Y-m-d");
                $_SESSION['breakStartTime'] = date("H:i:s");
            } else {
                $message = "Need to have clocked in before starting a break";
                break;
            }
            break;
        case 'Break end':
            if (isset($_SESSION['clockInDate']) && isset($_SESSION['clockInTime'])) {
                if ($areWeShowingAlerts) {
                    $message = "Break ended";
                }
                date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
                $_SESSION['breakEndDate'] = date("Y-m-d");
                $_SESSION['breakEndTime'] = date("H:i:s");
                // Insert all of this data into the DB
                insertEmployeeBreaksTakenRecord($mysqli, 'start_stop_working.php', $message, $_SESSION['employee_id'], $_SESSION['breakStartDate'], $_SESSION['breakStartTime'], $_SESSION['breakEndDate'], $_SESSION['breakEndTime'], $_SESSION['breakType']);
                unset($_SESSION['breakStartDate']);
                unset($_SESSION['breakStartTime']);
                unset($_SESSION['breakType']);
                unset($_SESSION['breakEndDate']);
                unset($_SESSION['breakEndTime']);
            } else {
                $message = "Need to have clocked in before starting a break";
                break;
            }
            break;
        case 'Reset clock in':
            if (isset($_SESSION['clockInDate']) && isset($_SESSION['clockInTime'])) {
                $sql = "DELETE FROM factory_employee_time_period_worked 
                WHERE working_time_period_employee_id = ? 
                AND employee_shift_id = ? 
                AND employee_clock_in_date = ?";

                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    throwAnError(__LINE__, __FILE__, $mysqli->error);
                }
                $stmt->bind_param('iis', $employee_id, $shift_id, $clock_in_date);
                $stmt->execute();
                $stmt->close();
                
                unset($_SESSION['shift_id']);
                unset($_SESSION['clockInDate']);
                unset($_SESSION['clockInTime']);
                if ($areWeShowingAlerts) {
                    $message = "No longer considered to be clocked in";
                }
            }
            break;
        case 'Reset break start':
            if (isset($_SESSION['breakStartDate']) && isset($_SESSION['breakStartTime'])) {
                unset($_SESSION['breakStartDate']);
                unset($_SESSION['breakStartTime']);
                if ($areWeShowingAlerts) {
                    $message = "No longer considered to have started a break";
                }
            }
            break;
        case 'Reset overtime start':
            if (isset($_SESSION['overtimeStartDate']) && isset($_SESSION['overtimeStartTime'])) {
                unset($_SESSION['overtimeStartDate']);
                unset($_SESSION['overtimeStartTime']);
                if ($areWeShowingAlerts) {
                    $message = "No longer considered to have started overtime";
                }
            }
            break;
        default:
            throwAnError(__LINE__, __FILE__, NULL);
            break;
    }
}
?>
<!-- Communication page  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Start/Stop working</title>
    <script src="<?php echo $bannerMessagePath; ?>" defer> </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const root = document.documentElement;
        const storedImageSource = sessionStorage.getItem('breakImageSource');
        if (storedImageSource) {
            root.style.setProperty('--break-image-src', storedImageSource);
        }
        <?php
            if ($message != '' && $areWeShowingAlerts == true) {
                echo 'updateMessage("' . addslashes($message) . '", "insertion_of_records_outcome", "message_text");';
            }
            ?>
    });
    </script>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <div class="outcome_banner" id="insertion_of_records_outcome">
        <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    </div>
    <script>
    function changeDuration() {
        const root = document.documentElement;
        const style = getComputedStyle(root);
        const breakImageSource = style.getPropertyValue('--break-image-src').trim();
        var sendImageSource = breakImageSource;
        let darkMode = JSON.parse(sessionStorage.getItem("darkModeEnabled"));
        if (darkMode) {
            if (breakImageSource == 'url(\'../img/15_minute_break_invert.png\')') {
                sendImageSource = 'url(\'../img/30_minute_break_invert.png\')';
            } else {
                sendImageSource = 'url(\'../img/15_minute_break_invert.png\')';
            }
        } else {
            if (breakImageSource == 'url(\'../img/15_minute_break.png\')') {
                sendImageSource = 'url(\'../img/30_minute_break.png\')';
            } else {
                sendImageSource = 'url(\'../img/15_minute_break.png\')';
            }
        }
        root.style.setProperty('--break-image-src', sendImageSource);
        sessionStorage.setItem('breakImageSource', sendImageSource);
        fetch('<?php echo $pathToSessionStorageFile ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sendImageSource: sendImageSource
            })
        });
    }
    </script>
    <main>
        <div id="rectangle_with_notched_corners">
            <div class="start_stop_working_section_container">
                <h1 style="margin-top: 0px; padding-top: 20px;">Starting/Finishing shift</h1>
                <form method="post" class="block" id="select_shift_form">
                    <label for="shift_selector" class="form_label">Select shift:</label> <br>
                    <select name="shift_selector" class="input_field input_class_select_field"
                        onchange="updateShiftSelection(this)">
                        <option disabled selected>-- Select a shift --</option>
                        <option value="day">Day</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="night">Night</option>
                    </select>
                </form>
                <script>
                function updateShiftSelection(selectElement) {
                    const form = selectElement.form;
                    // Create a hidden input button so that I can update shift without user having to press a button or change existing logic for form handling
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'button_clicked';
                    actionInput.value = 'Select shift';
                    form.appendChild(actionInput);
                    form.submit();
                }
                </script>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Clock in" />
                </form>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Clock off" />
                </form>
            </div>
            <div class="start_stop_working_section_container">
                <hr class="container_separator">
                <h1>Taking a work break</h1>
                <div id="break_duration_container">
                    <button id="break_duration" name="button_clicked" onclick="changeDuration();"></button>
                </div>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Break start" />
                </form>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Break end" />
                </form>
            </div>
            <div class="start_stop_working_section_container">
                <hr class="container_separator">
                <h1>Starting/Finishing overtime</h1>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Overtime start" />
                </form>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Overtime end" />
                </form>
            </div>
            <div class="start_stop_working_section_container" ">
<hr class=" container_separator">
                <h1>Reverting a mistake</h1>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Reset clock in" />
                </form>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Reset break start" />
                </form>
                <form method="post" class="inline">
                    <input type="submit" class="button" name="button_clicked" value="Reset overtime start" />
                </form>
            </div>
        </div>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>