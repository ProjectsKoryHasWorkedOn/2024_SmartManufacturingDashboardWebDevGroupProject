<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($resetMessageFilePath);
require_once($obtainShowingAlertsValueFilePath);
require_once($sortColumnFilePath);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ((isset($_POST['job_id'])) && (isset($_POST['job_status'])) && (isset($_POST['job_task_notes']))) {
        $job_id = $_POST['job_id'];
        $job_status = $_POST['job_status'];
        $job_task_notes = $_POST['job_task_notes'];
        $stmt = $mysqli->prepare("UPDATE factory_jobs SET job_status = ?, job_task_notes = ? WHERE job_id = ?");
        $stmt->bind_param('ssi', $job_status, $job_task_notes, $job_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Updated job";
    }
}
?>
<!-- Jobs to do page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Jobs to do</title>
    <script src="<?php echo $bannerMessagePath; ?>" defer> </script>
</head>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if (!empty($message) && $areWeShowingAlerts == true): ?>
            updateMessage("<?php echo addslashes($message); ?>", "jobs_to_do_outcome", "message_text");
        <?php endif; ?>
    });
</script>
<body>
    <?php
    include_once($headerJobsToDoFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <div class="outcome_banner" id="jobs_to_do_outcome">
        <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    </div>
    <main>
        <h1>Jobs to do</h1>
        <h2>Overview</h2>
        <?php
        $sql = "SELECT 
            job_status, 
            COUNT(*) AS status_count
        FROM 
            factory_jobs
        WHERE
            job_employee_id = ?
        GROUP BY 
            job_status;";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $_SESSION['employee_id']);
        $stmt->execute();
        $results = $stmt->get_result();
        $active_count = 0;
        $finished_count = 0;
        $postponed_count = 0;
        $cancelled_count = 0;
        $statuses = ['active', 'finished', 'postponed', 'cancelled'];
        while ($row = $results->fetch_assoc()) {
            if ($row['job_status'] == $statuses[0]) {
                $active_count = $row['status_count'];
            } elseif ($row['job_status'] == $statuses[1]) {
                $finished_count = $row['status_count'];
            } elseif ($row['job_status'] == $statuses[2]) {
                $postponed_count = $row['status_count'];
            } elseif ($row['job_status'] == $statuses[3]) {
                $cancelled_count = $row['status_count'];
            }
        }
        $totalCount = $active_count + $finished_count + $postponed_count + $cancelled_count;
        $_SESSION['activeCountPercentage'] = ($active_count / $totalCount) * 100;
        $_SESSION['finishedCountPercentage'] = ($finished_count / $totalCount) * 100;
        $_SESSION['postponedCountPercentage'] = ($postponed_count / $totalCount) * 100;
        $_SESSION['cancelledCountPercentage'] = ($cancelled_count / $totalCount) * 100;
        $results->free();
        $stmt->close();
        ?>
        <link rel="stylesheet" href="import/css/jobs_to_do_style.php?<?php echo time(); ?>">
        <div id="job_progress_bar_container">
            <div class="rectangle" id="cancelled_jobs_rectangle"
                style="<?php if ($cancelled_count == 0) {
                    echo "display: none;";
                } ?> ">
                <p class="jobs_text" id="cancelled_jobs_text"><?php echo $cancelled_count . ' cancelled' ?></p>
            </div>
            <div class="rectangle" id="postponed_jobs_rectangle"
                style="<?php if ($postponed_count == 0) {
                    echo "display: none;";
                } ?> ">
                <p class="jobs_text" id="postponed_jobs_text"><?php echo $postponed_count . ' postponed' ?></p>
            </div>
            <div class="rectangle" id="finished_jobs_rectangle"
                style="<?php if ($finished_count == 0) {
                    echo "display: none;";
                } ?> ">
                <p class="jobs_text" id="finished_jobs_text"><?php echo $finished_count . ' finished' ?></p>
            </div>
            <div class="rectangle" id="active_jobs_rectangle"
                style="<?php if ($active_count == 0) {
                    echo "display: none;";
                } ?> ">
                <p class="jobs_text" id="active_jobs_text"><?php echo $active_count . ' active' ?></p>
            </div>
        </div>
        <h2>Time Remaining</h2>
        <div id="my-pie-chart-container">
            <div id="my-pie-chart"></div>
            <div id="legenda">
                <div class="entry">
                    <div id="color-worked" class="entry-color"></div>
                    <div class="entry-text">Time spent working</div>
                </div>
                <div class="entry">
                    <div id="color-break" class="entry-color"></div>
                    <div class="entry-text">Time allocated for breaks</div>
                </div>
                <div class="entry">
                    <div id="color-remaining" class="entry-color"></div>
                    <div class="entry-text">Remaining time to finish tasks</div>
                </div>
            </div>
        </div>
        <h2>Specifics</h2>
        <div class="full_width_table_container">
        <table class="full_width_table">
    <thead>
        <tr>
            <th><a class="order_by_link" href="?order_by=job_id&sort=<?= getCurrentOrderingOfColumn('job_id') ?>">ID</a></th>
            <th><a class="order_by_link" href="?order_by=job_description&sort=<?= getCurrentOrderingOfColumn('job_description') ?>">Description</a></th>
            <th><a class="order_by_link" href="?order_by=job_status&sort=<?= getCurrentOrderingOfColumn('job_status') ?>">Status</a></th>
            <th>Task notes</th> 
            <th><a class="order_by_link" href="?order_by=job_priority&sort=<?= getCurrentOrderingOfColumn('job_priority') ?>">Priority</a></th>
        </tr>
    </thead>
    <tbody>

                <?php
 $order_by = $_GET['order_by'] ?? 'job_priority'; 
  $sort = $_GET['sort'] ?? 'ASC'; 

  $allowed_columns = ['job_id', 'job_description', 'job_status', 'job_priority'];
  
  if (!in_array($order_by, $allowed_columns)) {
      $order_by = 'job_priority';
  }
  
  $sql = "SELECT 
      fj.job_id,
      fj.job_description,
      fj.job_status,
      fj.job_task_notes,
      fj.job_priority
  FROM factory_jobs fj
  WHERE fj.job_employee_id = ? AND fj.job_status IN (?, ?)
  ORDER BY $order_by $sort;";
  
  $stmt = $mysqli->prepare($sql);
  $statuses_to_search_for = ['active', 'postponed'];
  $stmt->bind_param('iss', $_SESSION['employee_id'], $statuses_to_search_for[0], $statuses_to_search_for[1]);
  $stmt->execute();
  $results = $stmt->get_result();  




                while ($row = $results->fetch_assoc()) {
                    echo "<tr>
        <form method=\"post\" action=\"\">
            <input type=\"hidden\" name=\"job_id\" value=\"" . htmlspecialchars($row['job_id']) . "\">
            <td><span class=\"numbers_font\">" . htmlspecialchars($row['job_id']) . "</span></td>
            <td>" . htmlspecialchars($row['job_description']) . "</td>
            <td>
                <select class=\"input_field input_class_select_field\" name=\"job_status\">";
                    // Generate select options
                    foreach ($statuses as $status) {
                        $selected = ($status == htmlspecialchars($row['job_status'])) ? ' selected' : '';
                        echo "<option value=\"$status\"$selected>$status</option>";
                    }
                    echo "    </select>
            </td>
            <td>
                <textarea class=\"input_field\" name=\"job_task_notes\">" . htmlspecialchars($row['job_task_notes']) . "</textarea>
            </td>
            <td><span class=\"numbers_font\">" . htmlspecialchars($row['job_priority']) . "</span></td>
            <td><button class=\"button\" type=\"submit\">Update</button></td>
        </form>
    </tr>";
                }
                $results->free();
                $stmt->close();
                ?>
            </tbody>
        </table>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>