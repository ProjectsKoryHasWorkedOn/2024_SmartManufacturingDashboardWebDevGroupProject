<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($resetMessageFilePath);
?>
<?php
$job_notes = [];
$notes_onward_date = $_SESSION['notes_onward_period'] ?? NULL;
$notes_job_status = $_SESSION['notes_job_status'] ?? NULL;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear_note_employee_id'])) {
        $employee_id_for_job_note = $_POST['clear_note_employee_id'];
        $stmt = $mysqli->prepare("UPDATE factory_jobs SET job_task_notes = NULL WHERE job_employee_id = ?");
        $stmt->bind_param('s', $employee_id_for_job_note);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Cleared note";
        header("Location: job_notes.php");
        exit();
    }
    if (isset($_POST['notes_onward_period']) && isset($_POST['notes_job_status'])) {
        $notes_onward_date = $_POST['notes_onward_period'];
        $notes_job_status = $_POST['notes_job_status'];
        $_SESSION['notes_onward_period'] = $notes_onward_date;
        $_SESSION['notes_job_status'] = $notes_job_status;
        date_default_timezone_set($_SESSION['branch_timezone'] ?? 'UTC');
        if (empty($notes_onward_date)) {
            $sql_query_for_the_earliest_date = "SELECT MIN(job_assigned_date) FROM factory_jobs";
            $stmt = $mysqli->prepare($sql_query_for_the_earliest_date);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_row();
            if (isset($row[0])) {
                $notes_onward_date = $row[0];
            } else {
                $notes_onward_date = date('Y-m-d');
            }
            $stmt->close();
        }
        $stmt = $mysqli->prepare("SELECT job_employee_id, job_task_notes 
FROM factory_jobs
JOIN factory_employees ON factory_jobs.job_employee_id = factory_employees.employee_id
WHERE job_assigned_date >= ? AND factory_jobs.job_status = ?
AND factory_employees.employee_role = 'production operator'
 AND job_task_notes != ''");
        $stmt->bind_param('ss', $notes_onward_date, $notes_job_status);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $job_employee_id = $row['job_employee_id'];
            $job_task_notes = $row['job_task_notes'];
            $stmtfe = $mysqli->prepare("SELECT employee_first_name, employee_last_name, employee_role FROM factory_employees WHERE employee_id = ? AND employee_role = 'production operator'");
            $stmtfe->bind_param('s', $job_employee_id);
            $stmtfe->execute();
            $resultfe = $stmtfe->get_result();
            $row = $resultfe->fetch_assoc();
            $employee_full_name = $row['employee_first_name'] . ' ' . $row['employee_last_name'];
            $employee_role = $row['employee_role'];
            $job_notes[] = [
                'job_employee_name' => $employee_full_name,
                'job_task_notes' => $job_task_notes,
                'job_employee_role' => $employee_role,
                'job_employee_id' => $job_employee_id
            ];
            $stmtfe->close();
        }
        $stmt->close();
    }
}
?>
<!-- Job notes page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <script src="<?php echo $bannerMessagePath; ?>" defer> </script>
    <title>Job notes</title>
</head>
<body>
    <body>
        <?php
        include_once($headerFilePath);
        include_once($sidebarFilePath);
        include_once($hotkeysFilePath);
        ?>
        <div class="outcome_banner" id="job_notes_outcome_banner">
            <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
        </div>
        <main>
            <h1>Job notes</h1>
            <div id="job_notes_filter_container">
                <form action="" method="post">
                    <label for="notes_onward_period">Show notes from this period onward:</label> <br>
                    <input class="input_field" type="date" name="notes_onward_period"
                        value="<?php echo isset($_SESSION['notes_onward_period']) ? $_SESSION['notes_onward_period'] : date('Y-m-d'); ?>">
                    <br>
                    <label for="notes_job_status">Show notes with this status:</label> <br>
                    <select class="input_field input_class_select_field" name="notes_job_status" class="input_field">
                        <option value="active" <?php echo (isset($_SESSION['notes_job_status']) && $_SESSION['notes_job_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="finished" <?php echo (isset($_SESSION['notes_job_status']) && $_SESSION['notes_job_status'] == 'finished') ? 'selected' : ''; ?>>Finished</option>
                        <option value="postponed" <?php echo (isset($_SESSION['notes_job_status']) && $_SESSION['notes_job_status'] == 'postponed') ? 'selected' : ''; ?>>Postponed</option>
                    </select><br>
                    <input class="button smaller_button" type="submit" value="Submit">
                </form>
            </div>
            <?php
            if (!empty($job_notes)) {
                echo "<h2>Filtered notes</h2>";
                echo "<p>Showing notes from " . $notes_onward_date . " onwards with status of " . $notes_job_status . "</p>";
            }
            foreach ($job_notes as $note) {
                $employee_name = $note['job_employee_name'];
                // $employee_role = $note['job_employee_role'];
                $employee_note = $note['job_task_notes'];
                $job_employee_id = $note['job_employee_id'];
                echo "<div class='job_notes_container'>";
                echo "<p class='job_note_employee'>$employee_name</p>";
                // echo "<p class='job_note_employee_roles'>$employee_role</p>";
                echo "<p class='job_notes_text'>$employee_note</p>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='clear_note_employee_id' value='$job_employee_id'>";
                echo "<button class='button smaller_button' type='submit' >Clear</button>";
                echo "</form>";
                echo '</div>';
            }
            ?>
        </main>
        <?php
        include_once($footerFilePath);
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                <?php
                if (isset($message) && ($message != '')) {
                    echo 'updateMessage("' . addslashes($message) . '", "job_notes_outcome_banner", "message_text");';
                }
                ?>
            });
        </script>
    </body>
</html>