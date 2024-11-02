function toggleCheckboxes() {
    const checkboxes = document.querySelectorAll('#table_selection_container input[type="checkbox"]');
    const checkAllCheckboxes = Array.from(checkboxes).every(checkbox => checkbox.checked);
    checkboxes.forEach(checkbox => {
        checkbox.checked = !checkAllCheckboxes;
        chooseWhatTableToShow();
    });
}
function chooseWhatTableToShow() {
    // Select the checkboxes based on if they're checked or not
    const checkedCheckboxes = document.querySelectorAll('input[name="insertion_form_selection"]:checked');
    const uncheckedCheckboxes = document.querySelectorAll('input[name="insertion_form_selection"]:not(:checked)');
    // Get an array of values from the checked checkboxes
    const selectedForms = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
    // Get an array of values from the unchecked checkboxes
    const unselectedForms = Array.from(uncheckedCheckboxes).map(checkbox => checkbox.value);
    // Local storage can only store strings. This means if you need to store values like objects or arrays, you first need to get a string representation of the value. You do this using the JSON. stringify() method
    localStorage.setItem('selectedForms', JSON.stringify(selectedForms));
    // Show forms with a value in selectedForms array
    selectedForms.forEach(table => {
        var formElement = document.getElementById(table + "_form");
        formElement.style.display = 'block';
        formElement.style.marginBottom = '30px';
    });
    // Hide forms with a value in unselectedForms array
    unselectedForms.forEach(table => {
        var formElement = document.getElementById(table + "_form");
        formElement.style.display = 'none';
    });
}
function loadCheckboxState() {
    // Get stored values from local storage
    const savedFormSelections = JSON.parse(localStorage.getItem('selectedForms') || '[]'); // Default value: '[]'
    if (savedFormSelections.length > 0) {
        // Select all checkboxes if there are saved selections
        document.querySelectorAll('input[name="insertion_form_selection"]').forEach(checkbox => {
            // Set checkbox to checked if it was checked before
            if (savedFormSelections.includes(checkbox.value)) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
        chooseWhatTableToShow();
    }
    else {
        console.log("No checkbox states saved in storage");
    }
}
document.addEventListener('DOMContentLoaded', loadCheckboxState);
