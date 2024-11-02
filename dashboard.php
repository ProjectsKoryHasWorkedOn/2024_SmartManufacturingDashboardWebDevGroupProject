<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($resetMessageFilePath);
require_once($errorThrowerFilePath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <script src="<?php echo $bannerMessagePath; ?>"> </script>
    <title>Dashboard</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($hotkeysFilePath);
    if (!isset($_SESSION['employee_role'])) {
        throwAnError(__LINE__, __FILE__, "Missing employee role");
    }
    if (!isset($_SESSION['employee_first_name'])) {
        throwAnError(__LINE__, __FILE__, "Missing employee first name");
    }
    if (!isset($_SESSION['employee_last_name'])) {
        throwAnError(__LINE__, __FILE__, "Missing employee last name");
    }
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dashboardLinks = document.getElementsByClassName('dashboard_link');
            const dashboardLinkTexts = document.getElementsByClassName('dashboard_link_text')
            Array.from(dashboardLinks).forEach(dashboardLink => {
                dashboardLink.addEventListener("mouseover", () => {
                    dashboardLink.classList.add("dashboard_link_and_text_hovered");
                });
                dashboardLink.addEventListener("mouseout", () => {
                    dashboardLink.classList.remove("dashboard_link_and_text_hovered");
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php
            if (isset($message) && ($message != '')) {
                echo 'updateMessage("' . addslashes($message) . '", "dashboard_outcome_banner", "message_text");';
            }
            ?>
        });
    </script>
    <div class="outcome_banner" id="dashboard_outcome_banner">
        <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    </div>
    <div class="dashboard_container">
        <h1>Welcome <?php echo $_SESSION['employee_first_name'] . " " . $_SESSION['employee_last_name']; ?></h1>
        <!-- Show links based on what the role is -->
        <?php
        $array_of_dashboard_link_text;
        $array_of_dashboard_link_filename;
        switch ($_SESSION['employee_role']) {
            case 'production operator':
            case 'maintenance worker':
                $production_operator_links = array("Machine statuses", "Jobs to do", "Manage inventory", "Inbox", "Start/Stop working", "Site user guide");
                $production_operator_page_names = array('machine_status.php', 'jobs_to_do.php', 'manage_inventory.php', 'inbox.php', 'start_stop_working.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $production_operator_links;
                $array_of_dashboard_link_filename = $production_operator_page_names;
                // Adjust grid
                $_SESSION['startingGridValue'] = '5';
                $_SESSION['mediaQueryOneValue'] = '4';
                $_SESSION['mediaQueryTwoValue'] = '3';
                $_SESSION['mediaQueryThreeValue'] = '2';
                $_SESSION['mediaQueryFourValue'] = '1';
                $_SESSION['dashboardLinkWidthValue'] = '200px';
                break;
            case 'factory manager':
                $factory_manager_links = array("Machine statuses", "Add records", "Change records", "Employee metrics", "Job notes", "Inbox", "Site user guide");
                $factory_manager_page_names = array('machine_status.php', "insert_records.php", 'manage_records.php', 'factory_floor_attendance_and_productivity.php', 'job_notes.php', 'inbox.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $factory_manager_links;
                $array_of_dashboard_link_filename = $factory_manager_page_names;
                // Adjust grid
                $_SESSION['startingGridValue'] = '5';
                $_SESSION['mediaQueryOneValue'] = '4';
                $_SESSION['mediaQueryTwoValue'] = '3';
                $_SESSION['mediaQueryThreeValue'] = '2';
                $_SESSION['mediaQueryFourValue'] = '1';
                $_SESSION['dashboardLinkWidthValue'] = '200px';
                break;
            case 'admin staff':
                $admin_staff_links = array("Payroll", "Manage inventory", "Add records", "Change records", "Site user guide");
                $admin_staff_page_names = array('payroll.php', 'manage_inventory.php', 'insert_records.php', 'manage_records.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $admin_staff_links;
                $array_of_dashboard_link_filename = $admin_staff_page_names;
                // Adjust grid
                $_SESSION['startingGridValue'] = '1';
                $_SESSION['mediaQueryOneValue'] = '1';
                $_SESSION['mediaQueryTwoValue'] = '1';
                $_SESSION['mediaQueryThreeValue'] = '1';
                $_SESSION['mediaQueryFourValue'] = '1';
                $_SESSION['dashboardLinkWidthValue'] = '99%';
                break;
            case 'internal auditor':
                $internal_auditor_links = array("Report generator", "Site user guide");
                $internal_auditor_page_names = array('report_generator.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $internal_auditor_links;
                $array_of_dashboard_link_filename = $internal_auditor_page_names;
                // Adjust grid
                $_SESSION['startingGridValue'] = '1';
                $_SESSION['mediaQueryOneValue'] = '1';
                $_SESSION['mediaQueryTwoValue'] = '1';
                $_SESSION['mediaQueryThreeValue'] = '1';
                $_SESSION['mediaQueryFourValue'] = '1';
                $_SESSION['dashboardLinkWidthValue'] = '99%';
                break;
            default:
                throwAnError(__LINE__, __FILE__, "Role not recognized");
                break;
        }
        // Loop that creates links for each array element based on the role of the employee
        echo '<div id="role_container">';
        for ($i = 0; $i < count($array_of_dashboard_link_text); $i++) {
            echo '<div class="dashboard_link" onclick="window.location=\'' . $array_of_dashboard_link_filename[$i] . '\'">';
            echo '<p class="dashboard_link_text">';
            echo $array_of_dashboard_link_text[$i];
            echo '</p>';
            echo '</div>';
        }
        echo '</div>';
        ?>
        <!-- Override vars in style.css -->
        <!-- Needs to be after styles.css since vars don't have a value before this point in time -->
        <link rel="stylesheet" href="import/css/dashboard_style.php?<?php echo time(); ?>">
    </div>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>