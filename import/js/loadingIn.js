
    console.log('Loading values from session storage...');
    console.log('Machine Status ID:', sessionStorage.getItem('msid'));
    console.log('Machine ID:', sessionStorage.getItem('mid'));
    console.log('Start Date:', sessionStorage.getItem('start_date'));
    console.log('End Date:', sessionStorage.getItem('end_date'));
    console.log('Status:', sessionStorage.getItem('status'));
    console.log('Maintenance Log:', sessionStorage.getItem('maintenance_log'));


    // Check if session storage has any filter values and apply them
    if (sessionStorage.getItem('msid')) {
        document.getElementById('msid').value = sessionStorage.getItem('msid');
    }
    if (sessionStorage.getItem('mid')) {
        document.getElementById('mid').value = sessionStorage.getItem('mid');
    }
    if (sessionStorage.getItem('start_date')) {
        document.getElementById('start_date').value = sessionStorage.getItem('start_date');
    }
    if (sessionStorage.getItem('end_date')) {
        document.getElementById('end_date').value = sessionStorage.getItem('end_date');
    }
    if (sessionStorage.getItem('status')) {
        document.getElementById('machine_status_selector').value = sessionStorage.getItem('status');
    }
    if (sessionStorage.getItem('maintenance_log')) {
        document.getElementById('maintenance_log_selector').value = sessionStorage.getItem('maintenance_log');
    }


    showMostRecentLogs();


    function toggleVisibilityOfFiltersContainer(){
        var filtersContainer = document.getElementById('machine_logs_filters_container');
        var filtersButton = document.getElementById('show_hide_filters_button');

        var showMostRecentLogsButton = document.getElementById('show_most_recent_logs_button');
        var filterLogsButton = document.getElementById('filter_logs_button');


            if (filtersContainer.style.display === 'none') {
                filtersContainer.style.display = 'block';
                showMostRecentLogsButton.style.display = 'inline-block';
                filterLogsButton.style.display = 'inline-block';
                filtersButton.textContent = 'Hide filters';
            } else {
                filtersContainer.style.display = 'none';
                showMostRecentLogsButton.style.display = 'none';
                filterLogsButton.style.display = 'none';
                filtersButton.textContent = 'Show filters';
            }
        }


    function toggleVisibilityOfOverviewForLogsContainer(){
        var overviewContainer = document.getElementById('overviews_for_logs_container');
        var overviewButton = document.getElementById('show_hide_overview_button');

        if (overviewContainer.style.display === 'none') {
            overviewContainer.style.display = 'block';
            overviewButton.textContent = 'Hide overview';
        } else {
            overviewContainer.style.display = 'none';
            overviewButton.textContent = 'Show overview';
        }

    }