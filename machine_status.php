<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($errorThrowerFilePath);
require_once($sortColumnFilePath);
?>
<!-- Machine status page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Machine statuses</title>
    <script src="import/js/dateValidator.js"></script>
    <script src="import/js/loadingIn.js" defer></script>
</script>


</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Machine statuses</h1>

        <button class="button" id="show_hide_overview_button" onclick="toggleVisibilityOfOverviewForLogsContainer()">Show overview</button>
        <button class="button" id="show_hide_filters_button" onclick="toggleVisibilityOfFiltersContainer()">Show filters</button>



    <div id="overviews_for_logs_container" style="display: none;">
        <h2>Overview for unique machines at the current branch</h2>




        <?php
        $sql = "SELECT 
        ms.operational_status,
        COUNT(*) AS status_count
        FROM 
        factory_machine_statuses ms
        JOIN (
        SELECT 
            machine_id,
            MAX(timestamp) AS most_recent_timestamp
        FROM 
            factory_machine_statuses
        GROUP BY 
            machine_id
        ) AS latest ON ms.machine_id = latest.machine_id 
            AND ms.timestamp = latest.most_recent_timestamp
        JOIN 
        factory_machines m ON ms.machine_id = m.machine_id
        WHERE 
        m.branch_id = '" . $_SESSION['branch_id'] . "'
        GROUP BY 
        ms.operational_status;";
        $result = $mysqli->query($sql);
        if (!$result) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        $active_count = 0;
        $idle_count = 0;
        $maintenance_count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($row['operational_status'] == 'active') {
                $active_count = $row['status_count'];
            } elseif ($row['operational_status'] == 'idle') {
                $idle_count = $row['status_count'];
            } elseif ($row['operational_status'] == 'maintenance') {
                $maintenance_count = $row['status_count'];
            }
        }
        echo '<div id="machine_overview_container">';
        echo '<div class="machine_statuses_container">';
        echo '<p><b>Total active machines:</b> ' . '<span class="numbers_font">' . $active_count . '</span>' . '</p>';
        echo '<p><b>Total idle machines:</b> ' . '<span class="numbers_font">' . $idle_count . '</span>' . '</p>';
        echo '<p><b>Total machines that need maintenance:</b> ' . '<span class="numbers_font">' . $maintenance_count . '</span>' . '</p>';
        echo '</div>';
        echo '<br>';
        $result->free();
        $sql = "SELECT 
        ms.maintenance_log,
        COUNT(*) AS log_count
    FROM 
        factory_machine_statuses ms
    JOIN (
        SELECT 
            machine_id,
            MAX(timestamp) AS most_recent_timestamp
        FROM 
            factory_machine_statuses
        GROUP BY 
            machine_id
    ) AS latest ON ms.machine_id = latest.machine_id 
               AND ms.timestamp = latest.most_recent_timestamp
    JOIN 
        factory_machines m ON ms.machine_id = m.machine_id
    WHERE 
        m.branch_id = '" . $_SESSION['branch_id'] . "'
    GROUP BY 
        ms.maintenance_log;";    
        $result = $mysqli->query($sql);
        if (!$result) {
            throwAnError(__LINE__, __FILE__, NULL);
        }
        $part_replacement_count = 0;
        $software_update_count = 0;
        $catastrophic_failure_count = 0;
        $routine_check_count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($row['maintenance_log'] == 'Catastrophic failure') {
                $catastrophic_failure_count = $row['log_count'];
            } elseif ($row['maintenance_log'] == 'Part Replacement') {
                $part_replacement_count = $row['log_count'];
            } elseif ($row['maintenance_log'] == 'Routine Check') {
                $routine_check_count = $row['log_count'];
            } elseif ($row['maintenance_log'] == 'Software Update') {
                $software_update_count = $row['log_count'];
            }
        }
        echo '<div class="machine_statuses_container">';
        echo '<p><b>Machines needing a part replacement:</b> ' . '<span class="numbers_font">' . $part_replacement_count . '</span>' . '</p>';
        echo '<p><b>Machines needing a software update:</b> ' . '<span class="numbers_font">' . $software_update_count . '</span>' . '</p>';
        echo '<p><b>Machines suffering a catastrophic failure:</b> ' . '<span class="numbers_font">' . $catastrophic_failure_count . '</span>' . '</p>';
        echo '<p><b>Machines needing a routine check:</b> ' . '<span class="numbers_font">' . $routine_check_count . '</span>' . '</p>';
        echo '</div>';
        echo '</div>';
        $result->free();
        ?>

</div>


 
    

        <div class="filters_container" id="machine_logs_filters_container" style="display: none;">
        <h2>Filter what's shown:</h2>
     
        <p>Filters can be applied on top of each other / Filters if left to their default uninitialized value aren't applied</p>

    <div class="filter_row">
        <label for="msid">Status ID:</label>
        <input type="text" class="input_field input_field_smaller" id="msid" placeholder="None">

        <label for="mid">Machine ID:</label>
        <input type="text" class="input_field input_field_smaller" id="mid" placeholder="None">
    </div>

    <div class="filter_row">
        <label for="start_date">Start:</label>
        <input type="date" class="input_field input_field_smaller" id="start_date">

        <label for="end_date">End:</label>
        <input type="date" class="input_field input_field_smaller" id="end_date">
    </div>

    <div class="filter_row">
        <label for="machine_status_selector">Status:</label>
        <select class="input_field input_class_select_field input_field_smaller" id="machine_status_selector">
            <option value="no_filter" selected>No filter</option>
            <option value="active">Active</option>
            <option value="idle">Idle</option>
            <option value="maintenance">Maintenance</option>
        </select>

        <label for="maintenance_log_selector">Log:</label>
        <select class="input_field input_class_select_field input_field_smaller" id="maintenance_log_selector">
            <option value="no_filter" selected>No filter</option>
            <option value="part_replacement">Part Replacement</option>
            <option value="software_update">Software Update</option>
            <option value="catastrophic_failure">Catastrophic failure</option>
            <option value="routine_check">Routine Check</option>
        </select>
    </div>
</div>

        <script>
  function filterMachineFinerDetailsTable() {
                const machineStatusID = document.getElementById('msid').value;
                const machineID = document.getElementById('mid').value;
                const startDateInput = document.getElementById('start_date').value.split('/');
                const startDate = new Date(`${startDateInput[2]}-${startDateInput[1]}-${startDateInput[0]}`);


const endDateInput = document.getElementById('end_date').value.split('/');

const endDate = new Date(`${endDateInput[2]}-${endDateInput[1]}-${endDateInput[0]}`);
endDate.setHours(23, 59, 59, 999);


let tableRowsCount = 0;

                
                const status = document.getElementById('machine_status_selector').value;
                const maintenanceLog = document.getElementById('maintenance_log_selector').value;
                var noStartDateProvided = isNaN(startDate.getTime());

                if(noStartDateProvided == true){

                    if(document.getElementById('start_date').value.length !== 0){
                        if (!isValidDateFormat(startDate)) {

                        alert("Please enter valid date for the start date");
                        return; 
                        }
                   }
                }




                var noEndDateProvided = isNaN(endDate.getTime());

                if(noEndDateProvided == true){
                    if(document.getElementById('end_date').value.length !== 0){
                        if (!isValidDateFormat(endDate)) {

                            alert("Please enter valid date for the end date");
                            return; 
                            }
                    }
                    

                }

                /* Select elements */
                var tbody = document.querySelector('#machine_finer_details_table tbody');
                var rows = tbody.getElementsByTagName('tr');
                // Hide all rows
                for (var i = 0; i < rows.length; i++) {
                    rows[i].style.display = 'none';
                
                }
                /* Show rows so long as conditions are met */
                for (var i = 0; i < rows.length; i++) {
                    /* Get machine status ID of each column */
                    var machineStatusIDColumn = rows[i].getElementsByTagName('td')[0].textContent.trim();

/* Get machine ID of each column */
var machineIDColumn = rows[i].getElementsByTagName('td')[1].textContent.trim();


                    /* Get timestamp of each column */
                    var timestampColumn = rows[i].getElementsByTagName('td')[6].textContent.trim();
                    var datePartOfTimestamp = timestampColumn.split(' ')[0];
                    /* Get maintenance log value of each column */
                    var maintenanceLogColumn = rows[i].getElementsByTagName('td')[5].textContent.trim();
                    /* Replace spaces with underscores */
                    maintenanceLogColumn = maintenanceLogColumn.split(' ').join('_').toLowerCase();
                    /* Make it lowercase */
                    maintenanceLogColumn = maintenanceLogColumn.toLowerCase();
                    /* Get status value of each column from the div class (circle)
                    There's no text value in the table for me to be able to extract */
                    var statusColumn = rows[i].getElementsByTagName('td')[3];
                    var statusDiv = statusColumn.querySelector('div');
                    var statusClass = statusDiv ?
                        (statusDiv.classList.contains('active') ? 'active' :
                            (statusDiv.classList.contains('idle') ? 'idle' :
                                (statusDiv.classList.contains('maintenance') ? 'maintenance' : ''))) :
                        '';
                    // Seperate date
                    var date = new Date(timestampColumn);
                    // Check for correct machine status ID
                    if (
                        (machineStatusIDColumn == machineStatusID) ||
                        (machineStatusID == "")
                    ) {
                        // Check for correct machine ID
                        if (
                        (machineIDColumn == machineID) ||
                        (machineID == "")
                    ) {
                        // Check for correct timestamp
                        if (
    (noStartDateProvided || date >= startDate) &&
    (noEndDateProvided || date <= endDate)
) {
                            // Check for if machine has the status selected or the "no_filter" value
                            if (
                                (statusClass === status) ||
                                (status === "no_filter")
                            ) {
                                // Check for if machine has the maintenance log value selected or the "no_filter" value
                                if ((maintenanceLogColumn === maintenanceLog) ||
                                    (maintenanceLog === "no_filter")
                                ) {
                                    rows[i].style.display = 'table-row';
                                    tableRowsCount++;
                                }
                            }
                        }
                    }
                }
                }


                document.getElementById('total_rows_shown').textContent = "Total rows (after filtering): " + tableRowsCount; 

                saveFilters();
            }


function saveFilters(){
    sessionStorage.setItem('msid', document.getElementById('msid').value);
    sessionStorage.setItem('mid', document.getElementById('mid').value);
    sessionStorage.setItem('start_date', document.getElementById('start_date').value);
    sessionStorage.setItem('end_date', document.getElementById('end_date').value);
    sessionStorage.setItem('status', document.getElementById('machine_status_selector').value);
    sessionStorage.setItem('maintenance_log', document.getElementById('maintenance_log_selector').value);
    
}



function redirectToIndividualMachinePage(machineStatusId) {
    window.location.href = 'individual_machine.php?id=' + encodeURIComponent(machineStatusId);
}



function showMostRecentLogs() {
    var tbody = document.querySelector('#machine_finer_details_table tbody');
    var rows = tbody.getElementsByTagName('tr');
    var machineLogs = {};


    let tableRowsCount = 0;


    // Loop through rows and group by machine_id
    for (var i = 0; i < rows.length; i++) {
        var machineIDColumn = rows[i].getElementsByTagName('td')[1].textContent.trim(); // Machine ID
        var timestampColumn = rows[i].getElementsByTagName('td')[6].textContent.trim(); // Timestamp

        // Parse the timestamp correctly
        var timestamp = new Date(timestampColumn);
        
        // Check if the machineID is already logged or if the current timestamp is more recent
        if (!machineLogs[machineIDColumn] || new Date(machineLogs[machineIDColumn].timestamp) < timestamp) {
            machineLogs[machineIDColumn] = {
                row: rows[i],
                timestamp: timestampColumn
            };
        }
    }

    // Hide all rows first
    for (var i = 0; i < rows.length; i++) {
        rows[i].style.display = 'none';
    }
    
    // Display only the most recent log for each machine
    for (var machineID in machineLogs) {
        machineLogs[machineID].row.style.display = 'table-row';
        tableRowsCount++;

    }

    document.getElementById('total_rows_shown').textContent = "Total rows (after filtering): " + tableRowsCount; 
}


</script>
        <button class='button' id="filter_logs_button" onclick="filterMachineFinerDetailsTable()" style="display: none;">Filter</button>
        <button class='button' id="show_most_recent_logs_button" onclick="showMostRecentLogs()" style="display: none;">Most recent logs</button>
        <h2>Machine status logs</h2>
        <div class="full_width_table_container">
      
        <table class="full_width_table" id="machine_finer_details_table">
        <thead>
            <tr>
                <th><a class="order_by_link" href="?order_by=machine_status_id&sort=<?= getCurrentOrderingOfColumn('machine_status_id') ?>">Machine status ID</a></th>
                <th><a class="order_by_link" href="?order_by=machine_id&sort=<?= getCurrentOrderingOfColumn('machine_id') ?>">Machine ID</a></th>
                <th><a class="order_by_link" href="?order_by=machine_name&sort=<?= getCurrentOrderingOfColumn('machine_name') ?>">Name</a></th>
                <th>Status</th>
                <th><a class="order_by_link" href="?order_by=error_code&sort=<?= getCurrentOrderingOfColumn('error_code') ?>">Error code</a></th>
                <th><a class="order_by_link" href="?order_by=maintenance_log&sort=<?= getCurrentOrderingOfColumn('maintenance_log') ?>">Maintenance log</a></th>
                <th><a class="order_by_link" href="?order_by=timestamp&sort=<?= getCurrentOrderingOfColumn('timestamp') ?>">Timestamp</a></th>
                <th>See more info</th>
            </tr>
        </thead>

            <tbody>
                <?php
  $order_by = 'machine_status_id'; // default column to sort
  $sort = 'ASC'; 
  
  if (isset($_GET['order_by']) && in_array($_GET['order_by'], ['machine_status_id', 'machine_id', 'machine_name', 'error_code', 'maintenance_log', 'timestamp'])) {
      $order_by = $_GET['order_by'];
  }
  
  if (isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC'])) {
      $sort = $_GET['sort'];
  }
  
  $sql = "SELECT 
              fmams.machine_status_id,
              fm.machine_id,
              fmt.machine_name,
              fmams.operational_status,
              fmams.error_code,
              fmams.maintenance_log,
              fmams.timestamp
          FROM factory_machine_statuses fmams
          JOIN factory_machines fm ON fm.machine_id = fmams.machine_id
          JOIN factory_machine_types fmt ON fmt.machine_type_id = fm.machine_type_id
          WHERE fm.branch_id = ?
          ORDER BY $order_by $sort"; 
  
  $stmtfmt = $mysqli->prepare($sql);
  $stmtfmt->bind_param('i', $_SESSION['branch_id']);
  $stmtfmt->execute();
  $result = $stmtfmt->get_result();  
                if (!$result) {
                    throwAnError(__LINE__, __FILE__, NULL);
                }


                $total_rows_initial_value = $result->num_rows;


                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
        <td>" . '<span class="numbers_font">' . htmlspecialchars($row['machine_status_id']) . '</span>' . "</td>
        <td>" . '<span class="numbers_font">' . htmlspecialchars($row['machine_id']) . '</span>' . "</td>
        <td>" . htmlspecialchars($row['machine_name']) . "</td>
        <td><div class=\"circle " . " " . htmlspecialchars($row['operational_status']) . "\">" . "</div></td>
        <td>" . htmlspecialchars($row['error_code']) . "</td>
        <td>" . htmlspecialchars($row['maintenance_log']) . "</td>
        <td>" . '<span class="numbers_font">' . htmlspecialchars($row['timestamp']) . '</span>' . "</td>
        <td>";
                echo "<button class='button' onclick=\"redirectToIndividualMachinePage(" 
                . $row['machine_status_id'] 
                . ")\">View</button>";




                    echo "</td>
        </tr>";
                }
                $result->free();
                $stmtfmt->close();
                ?>
            </tbody>
        </table>

        <br>

        <p id="total_rows_shown">Total rows (after filtering): <?php echo $total_rows_initial_value?> </p>


        </div>





       
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>