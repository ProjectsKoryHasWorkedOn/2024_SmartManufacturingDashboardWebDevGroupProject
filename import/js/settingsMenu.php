<?php
session_start();
header('Content-Type: application/javascript');
require_once("../../php_resource_paths.php");
?>
var pathToSessionStoragePHPFile = '<?php echo $pathToSessionStorageFile ?>';
var debugAlertsSessionVariable = false;
window.onload = function() {
    setPathToSessionStoragePHPFile(pathToSessionStoragePHPFile);
    updateSettingsMenuSelections();
    initializeAlerts(); 
};
function seeSettingsMenu(){
    var popup = document.querySelector('#settings_menu'); 
    popup.classList.add('visible'); 
}
function closeSettingsMenu() {
    var popup = document.querySelector('#settings_menu'); 
    popup.classList.remove('visible');
}
function updateBodyTextSize(){
    const bodyFontSizeSelectorValue = document.getElementById('body_font_size_selector').value;
    const bodyFontSize = bodyFontSizeSelectorValue;
    sessionStorage.setItem("bodyFontSize", bodyFontSize);
    changeBodyTextSize();
}
function changeBodyTextSize(){
    let savedBodyFontSizeValue = sessionStorage.getItem("bodyFontSize");
    
    if(!savedBodyFontSizeValue){
        savedBodyFontSizeValue = 18;
    }
    
    let bodyFontSizeSelectorValue = parseInt(savedBodyFontSizeValue, 10);
    document.documentElement.style.setProperty('--default-font-size', bodyFontSizeSelectorValue + 'px');
}


function keepSidebarOpen(){
    let keepSidebarOpenEnabled = JSON.parse(sessionStorage.getItem("keepSidebarOpenEnabled"));
    if(keepSidebarOpenEnabled !== null){
        keepSidebarOpenEnabled = !keepSidebarOpenEnabled;
    }
    else{
        keepSidebarOpenEnabled = true;
    }
    sessionStorage.setItem("keepSidebarOpenEnabled", keepSidebarOpenEnabled);

    decideIfSidebarWillBeKeptOpened();
}

function decideIfSidebarWillBeKeptOpened(){
    let keepSidebarOpenEnabled = JSON.parse(sessionStorage.getItem("keepSidebarOpenEnabled"));
    if(keepSidebarOpenEnabled) {
        if (!document.body.classList.contains('open')) {
            document.body.classList.add('open'); 
        }
    } 
}

function redGreenColorBlindMode(){
    let redGreenColorBlindModeEnabled = JSON.parse(sessionStorage.getItem("redGreenColorBlindModeEnabled"));
    if(redGreenColorBlindModeEnabled !== null){
        redGreenColorBlindModeEnabled = !redGreenColorBlindModeEnabled;
    }
    else{
        redGreenColorBlindModeEnabled = true;
    }
    sessionStorage.setItem("redGreenColorBlindModeEnabled", redGreenColorBlindModeEnabled);
    changeBetweenRedGreenColorBlindMode();
}
function changeBetweenRedGreenColorBlindMode(){
    const root = document.documentElement;
    let colorMode = JSON.parse(sessionStorage.getItem("redGreenColorBlindModeEnabled"));
    if(colorMode) {
        root.style.setProperty('--bad-message-color', 'black');
        root.style.setProperty('--good-message-color', 'blue');
        root.style.setProperty('--bad-message-background-color', '#f1f1f1');
        root.style.setProperty('--good-message-background-color', 'lightblue');
    } else {
        root.style.setProperty('--bad-message-color', 'red');
        root.style.setProperty('--good-message-color', 'green');
        root.style.setProperty('--bad-message-background-color', '#fff1f1');
        root.style.setProperty('--good-message-background-color', '#fafffa');
    }
}
function darkMode(){
    let darkModeEnabled = JSON.parse(sessionStorage.getItem("darkModeEnabled"));
    if(darkModeEnabled !== null){
        darkModeEnabled = !darkModeEnabled;
    }
    else{
        darkModeEnabled = true;
    }
    sessionStorage.setItem("darkModeEnabled", darkModeEnabled);
    changeBetweenDarkMode();
}
function changeBetweenDarkMode(){
    const root = document.documentElement;
    let darkMode = JSON.parse(sessionStorage.getItem("darkModeEnabled"));
    const style = getComputedStyle(root);   
    const breakImageSource = style.getPropertyValue('--break-image-src').trim();
    if(darkMode) {
        document.body.style.backgroundColor = 'black';
        document.body.style.color = 'white';
        root.style.setProperty('--light-to-dark-color', 'white');
        root.style.setProperty('--contrast-with-light-to-dark-color', 'black');
        root.style.setProperty('--sidebar-open-image-src', 'url(../img/sidebar_open_invert.png)');
        root.style.setProperty('--sidebar-close-image-src', 'url(../img/sidebar_close.png)');
        if(breakImageSource == 'url(\'../img/15_minute_break_invert.png\')'){
            root.style.setProperty('--break-image-src', 'url(\'../img/15_minute_break_invert.png\')');
        }
        else{
            root.style.setProperty('--break-image-src', 'url(\'../img/30_minute_break_invert.png\')');
        }
        root.style.setProperty('--light-mode-gradient-color-a', '#222');
        root.style.setProperty('--light-mode-gradient-color-b', '#000');
    } else {
        document.body.style.backgroundColor = 'white';
        document.body.style.color = 'black';
        root.style.setProperty('--light-to-dark-color', 'black');
        root.style.setProperty('--contrast-with-light-to-dark-color', 'white');
        root.style.setProperty('--sidebar-open-image-src', 'url(../img/sidebar_open.png)');
        root.style.setProperty('--sidebar-close-image-src', 'url(../img/sidebar_close_invert.png)');
        if(breakImageSource == 'url(\'../img/15_minute_break.png\')'){
            root.style.setProperty('--break-image-src', 'url(\'../img/15_minute_break.png\')');
        }
        else{
            root.style.setProperty('--break-image-src', 'url(\'../img/30_minute_break.png\')');
        }
        root.style.setProperty('--light-mode-gradient-color-a', '#fff');
        root.style.setProperty('--light-mode-gradient-color-b', '#f8f8f8');
    }
    const settingsMenu = document.getElementById('settings_menu');
    settingsMenu.style.backgroundColor = 'inherit';
}
function setPathToSessionStoragePHPFile(path){
    pathToSessionStoragePHPFile = path;
}
function outputSessionVariableToConsoleForDebuggingPurposes(){
    console.log('<?php 
    if(isset($_SESSION["alertsWillBeShown"])){
        echo $_SESSION["alertsWillBeShown"];
    }
    else {
        echo "Session variable alertsWillBeShown is not set or is false";
    }
    ?>');
}
function initializeAlerts() {
    const alertsCheckbox = document.querySelector('input[type="checkbox"][onchange="showAlerts();"]');
    if (alertsCheckbox) {
        const alertsWillBeShown = alertsCheckbox.checked;
        sessionStorage.setItem("alertsWillBeShown", alertsWillBeShown);
        fetch(pathToSessionStoragePHPFile, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ alertsWillBeShown: alertsWillBeShown })
        });
    }
}
function showAlerts() {
    let alertsWillBeShown = JSON.parse(sessionStorage.getItem("alertsWillBeShown"));
    if (alertsWillBeShown !== null) {
        alertsWillBeShown = !alertsWillBeShown;
    } else {
        alertsWillBeShown = true;
    }
    sessionStorage.setItem("alertsWillBeShown", alertsWillBeShown);
    // Send the value to PHP since this'll allow me to show/hide alerts for when user
        // * Clocks in
        // * Clocks off
        // * Starts break
        // * Ends break
    if(debugAlertsSessionVariable){
        console.log("showAlerts function called"); 
        console.log("Check this path: " + pathToSessionStoragePHPFile);
        console.log("Sending alertsWillBeShown to PHP:", alertsWillBeShown);
    }
    fetch(pathToSessionStoragePHPFile, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ alertsWillBeShown: alertsWillBeShown })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
    })
    .catch(error => {
        console.error('Fetch error:', error);
    }); 
    if(debugAlertsSessionVariable){
        outputSessionVariableToConsoleForDebuggingPurposes();
    }
}
function setColorOfButtonBackground(){
    const currentColorPicked = document.getElementById('button_background_color_picker').value;
    var buttonColorPicked = currentColorPicked;
    sessionStorage.setItem("buttonColorPicked", buttonColorPicked);
    updateColorOfButtonBackground();
}
function updateColorOfButtonBackground(){
    let buttonColor = sessionStorage.getItem("buttonColorPicked");
    document.documentElement.style.setProperty('--button-color', buttonColor);
}
function setColorOfHover(){
    const currentColorPicked = document.getElementById('hover_color_picker').value;
    var hoverColorPicked = currentColorPicked;
    sessionStorage.setItem("hoverColorPicked", hoverColorPicked);
    updateColorOfHover();
}
function updateColorOfHover(){
    let hoverColor = sessionStorage.getItem("hoverColorPicked");
    document.documentElement.style.setProperty('--hover-color', hoverColor);
}
function updateSettingsMenuSelections(){
    const fontSizeSelector = document.getElementById('body_font_size_selector');
    const buttonColorPicker = document.getElementById('button_background_color_picker');
    const hoverColorPicker = document.getElementById('hover_color_picker');
    const darkModeCheckbox = document.querySelector('input[type="checkbox"][onchange="darkMode();"]');
    const colorBlindCheckbox = document.querySelector('input[type="checkbox"][onchange="redGreenColorBlindMode();"]');
    const alertsCheckbox = document.querySelector('input[type="checkbox"][onchange="showAlerts();"]');
    const keepSidebarOpenCheckbox = document.querySelector('input[type="checkbox"][onchange="keepSidebarOpen();"]');

    let savedDarkModeValue = sessionStorage.getItem("darkModeEnabled");
    let savedButtonColorValue = sessionStorage.getItem("buttonColorPicked");
    let savedHoverColorValue = sessionStorage.getItem("hoverColorPicked");
    let savedRedGreenColorModeValue = sessionStorage.getItem("redGreenColorBlindModeEnabled");
    let savedBodyFontSizeValue = sessionStorage.getItem("bodyFontSize");
    let savedAlertsShownValue = sessionStorage.getItem("alertsWillBeShown");
    let savedKeepSidebarOpenValue = sessionStorage.getItem("keepSidebarOpenEnabled");

    
    if (savedBodyFontSizeValue !== null) {
        fontSizeSelector.value = savedBodyFontSizeValue;
        changeBodyTextSize();
    } 
    if (savedButtonColorValue !== null) {
        buttonColorPicker.value = savedButtonColorValue;
        updateColorOfButtonBackground();
    } 
    if (savedHoverColorValue !== null) {
        hoverColorPicker.value = savedHoverColorValue;
        updateColorOfHover();
    } 
    if (savedDarkModeValue !== null) {
        darkModeCheckbox.checked = JSON.parse(savedDarkModeValue);
        changeBetweenDarkMode();
    } 
    if (savedRedGreenColorModeValue !== null) {
        colorBlindCheckbox.checked = JSON.parse(savedRedGreenColorModeValue);
        changeBetweenRedGreenColorBlindMode();
    } 
    if (savedAlertsShownValue !== null) {
        alertsCheckbox.checked = JSON.parse(savedAlertsShownValue);
        showAlerts();
    } 
    if (savedKeepSidebarOpenValue !== null){
        keepSidebarOpenCheckbox.checked = JSON.parse(savedKeepSidebarOpenValue);
        decideIfSidebarWillBeKeptOpened();
    }
}
