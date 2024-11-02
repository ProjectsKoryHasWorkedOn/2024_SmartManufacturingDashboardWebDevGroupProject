<script>
    function toggleSidebar() {
        document.body.classList.toggle('open');
    }
    document.addEventListener("keydown", (event) => {
        if (event.altKey && event.key === "s") {
            event.preventDefault();
            toggleSidebar();
        }
    });
    /* Make it so sidebar is below the header */
    window.addEventListener('scroll', function () {
        const headerHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-height'));
        const differenceBetweenHeaderAndSideBarHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-to-sidebar-gap'));
        const sidebar = document.getElementById('sidebar');
        const scrollY = window.scrollY;
        if (scrollY < headerHeight + differenceBetweenHeaderAndSideBarHeight) {
            /* Make it below header if header is in view */
            sidebar.style.top = `calc(${headerHeight - scrollY}px + ${differenceBetweenHeaderAndSideBarHeight}px)`;
        } else {
            /* Make it on the top of the page if header is out of view */
            sidebar.style.top = '0px';
        }
    });
</script>
<nav id="sidebar">
    <div class="sidebar_inner">
        <div id="sidebar_open_close_button_container">
            <button type="button" id="sidebar_button_opened" onclick="toggleSidebar()"></button>
        </div>
        <div id="sidebar_user_profile_picture_container">
            <img src="<?php echo $_SESSION['user_profile_picture']; ?>" alt="User Profile Picture"
                id="user_profile_picture_image" />
        </div>
        <!-- Show links based on what the role is -->
        <?php
        $array_of_dashboard_link_text;
        $array_of_dashboard_link_filename;
        switch ($_SESSION['employee_role']) {
            case 'production operator':
            case 'maintenance worker':
                $production_operator_links = array("Dashboard", "Machine statuses", "Jobs to do", "Oversee inventory", "Inbox", "Start/Stop working", "User guide");
                $production_operator_page_names = array('dashboard.php', 'machine_status.php', 'jobs_to_do.php', 'manage_inventory.php', 'inbox.php', 'start_stop_working.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $production_operator_links;
                $array_of_dashboard_link_filename = $production_operator_page_names;
                break;
            case 'factory manager':
                $factory_manager_links = array("Dashboard", "Machine statuses", "Add records", "Change records", "Employee metrics", "Job notes", "Inbox", "User guide");
                $factory_manager_page_names = array('dashboard.php', 'machine_status.php', "insert_records.php", 'manage_records.php', 'factory_floor_attendance_and_productivity.php', 'job_notes.php', 'inbox.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $factory_manager_links;
                $array_of_dashboard_link_filename = $factory_manager_page_names;
                break;
            case 'admin staff':
                $admin_staff_links = array("Dashboard", "Payroll", "Oversee inventory", "Add records", "Change records", "User guide");
                $admin_staff_page_names = array('dashboard.php', 'payroll.php', 'manage_inventory.php', 'insert_records.php', 'manage_records.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $admin_staff_links;
                $array_of_dashboard_link_filename = $admin_staff_page_names;
                break;
            case 'internal auditor':
                $internal_auditor_links = array("Dashboard", "Report generator", "User guide");
                $internal_auditor_page_names = array('dashboard.php', 'report_generator.php', 'site_user_guide.php');
                $array_of_dashboard_link_text = $internal_auditor_links;
                $array_of_dashboard_link_filename = $internal_auditor_page_names;
                break;
            default:
                throwAnError(__LINE__, __FILE__, "Role not recognized");
                break;
        }
        // Loop that creates buttons for each array element based on the role of the employee
        echo '<nav id="sidebar_menu">';
        for ($i = 0; $i < count($array_of_dashboard_link_text); $i++) {
            if ($array_of_dashboard_link_text[$i] != "Start/Stop working") {
                $underlined_character_of_link_text = mb_substr($array_of_dashboard_link_text[$i], 0, 1);
                $remaining_characters = mb_substr($array_of_dashboard_link_text[$i], 1);
                echo '<button class="sidebar_button" onclick="window.location=\'' . $array_of_dashboard_link_filename[$i] . '\'">';
                echo '<u>' . $underlined_character_of_link_text . '</u>' . $remaining_characters;
                echo '</button>';
            } else {
                /* Find position of the w */
                $index = mb_strpos($array_of_dashboard_link_text[$i], 'w');
                /* Extract this character */
                $underlined_character_of_link_text = mb_substr($array_of_dashboard_link_text[$i], $index, 1);
                /* Extract all characters before and after it */
                $text_before = mb_substr($array_of_dashboard_link_text[$i], 0, $index);
                $text_after = mb_substr($array_of_dashboard_link_text[$i], $index + 1);
                echo '<button class="sidebar_button" onclick="window.location=\'' . $array_of_dashboard_link_filename[$i] . '\'">';
                echo $text_before . '&nbsp;' . '<u>' . $underlined_character_of_link_text . '</u>' . $text_after;
                echo '</button>';
            }
        }
        echo '</nav>';
        ?>
    </div>
</nav>
<!-- Menu button visible outside of the sidebar -->
<button type="button" id="sidebar_button_unopened" onclick="toggleSidebar()"></button>