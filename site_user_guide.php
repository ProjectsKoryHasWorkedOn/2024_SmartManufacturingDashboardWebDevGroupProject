<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Site user guide</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Site user guide</h1>
        <h2>Requirements</h2>
        <ul>
            <li>Modern web browser - Firefox, Chrome</li>
            <li>Stable Internet connection</li>
        </ul>
        <h2>Induction video</h2>
        <?php
        echo '<video class="induction_video" controls>';
        switch ($_SESSION['employee_role']) {
            case 'production operator':
                $videoSrc = $productionOperatorInductionVideoFilePath;
                break;
            case 'maintenance_worker':
                $videoSrc = $maintenanceWorkerInductionVideoFilePath;
                break;
            case 'factory manager':
                $videoSrc = $factoryManagerInductionVideoFilePath;
                break;
            case 'admin staff':
                $videoSrc = $adminStaffInductionVideoFilePath;
                break;
            case 'internal auditor':
                $videoSrc = $internalAuditorInductionVideoFilePath;
                break;
            default:
                throwAnError(__LINE__, __FILE__, "Role not recognized");
                break;
        }
        echo '<source src="' . htmlspecialchars($videoSrc) . '" type="video/mp4">';
        echo "</video>";
        ?>
        <h2>Tips &amp; tricks</h2>
        <h3>Hotkeys</h3>
        <ul>
            <li>ALT + S: Open/Close sidebar</li>
            <li>ALT + D: Dashboard</li>
            <?php
            switch ($_SESSION['employee_role']) {
                case "maintenance worker":
                case "production operator":
                    echo "<li>ALT + M: Machine statuses</li>";
                    echo "<li>ALT + J: Jobs to do</li>";
                    echo "<li>ALT + O: Oversee inventory</li>";
                    echo "<li>ALT + I: Inbox</li>";
                    echo "<li>ALT + W: Start stop working</li>";
                    break;
                case "internal auditor":
                    echo "<li>ALT + R: Report generator</li>";
                    break;
                case "factory manager":
                    echo "<li>ALT + J: Job notes</li>";
                    echo "<li>ALT + M: Machine statuses</li>";
                    echo "<li>ALT + E: Employee metrics</li>";
                    echo "<li>ALT + A: Add records</li>";
                    echo "<li>ALT + C: Change records</li>";
                    break;
                case "admin staff":
                    echo "<li>ALT + P: Payroll</li>";
                    echo "<li>ALT + O: Oversee inventory</li>";
                    echo "<li>ALT + A: Add records</li>";
                    echo "<li>ALT + C: Change records</li>";
                    break;
                default:
                    break;
            }
            ?>
            <li>ALT + U: Site user guide</li>
        </ul>
        <h2>FAQ</h2>
        <div class="faq_container">
            <p class="faq_question">How do I change my password?</p>
            <p class="faq_answer">Via <em>"Profile"</em> in the topmost menu</p>
        </div>
        <div class="faq_container_end">
            <p class="faq_question">How do I customize the appearance of the site?</p>
            <p class="faq_answer">Via <em>"Settings"</em> in the bottommost menu</p>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>