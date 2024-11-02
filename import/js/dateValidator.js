function isValidDateFormat(dateString, format) {
    let regex;

    // Define regex patterns for different date formats
    switch (format) {
        case 'YYYY-MM-DD':
            regex = /^\d{4}-\d{2}-\d{2}$/;
            break;
        case 'DD/MM/YYYY':
            regex = /^\d{2}\/\d{2}\/\d{4}$/;
            break;
        case 'MM-DD-YYYY':
            regex = /^\d{2}-\d{2}-\d{4}$/;
            break;
        default:
            return false; // Unsupported format
    }

    // Check if dateString matches the regex
    if (!regex.test(dateString)) return false;

    // Parse date parts based on the format
    let parts, day, month, year;
    switch (format) {
        case 'YYYY-MM-DD':
            [year, month, day] = dateString.split('-').map(Number);
            month--; // JS months are 0-based
            break;
        case 'DD/MM/YYYY':
            [day, month, year] = dateString.split('/').map(Number);
            month--;
            break;
        case 'MM-DD-YYYY':
            [month, day, year] = dateString.split('-').map(Number);
            month--;
            break;
    }

    // Create a Date object and validate
    const date = new Date(year, month, day);
    return date.getFullYear() === year && date.getMonth() === month && date.getDate() === day;
}
