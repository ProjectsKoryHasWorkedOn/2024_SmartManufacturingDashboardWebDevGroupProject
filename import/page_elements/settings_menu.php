<script type="text/javascript" src="import/js/settingsMenu.php" defer></script>
<div class="popup" id="settings_menu">
    <div class="right_topmost_button_container">
        <button id="close_button" class="button tiny_button" onClick="closeSettingsMenu();">&#10006;</button>
    </div>
    <div class="options_container">
        <p class="option_header">Body text font size</p>
        <select name="body_text_size" id="body_font_size_selector" onchange="updateBodyTextSize();">
            <option value="16">16</option>
            <option value="18" default>18</option>
            <option value="20">20</option>
            <option value="22">22</option>
        </select>
        <p class="option_header">Button background color</p>
        <div class="option_container">
            <input type="color" id="button_background_color_picker" value="#1F4690"
                onchange="setColorOfButtonBackground();" />
        </div>
        <p class="option_header">Hover color</p>
        <div class="option_container">
            <input type="color" id="hover_color_picker" value="#8a2be2" onchange="setColorOfHover();" />
        </div>
        <p class="option_header">Dark mode</p>
        <div class="option_container">
            <label class="switch">
                <input type="checkbox" onchange="darkMode();">
                <span class="slider"></span>
            </label>
        </div>
        <p class="option_header">Red &amp; Green color blind mode</p>
        <div class="option_container">
            <label class="switch">
                <input type="checkbox" onchange="redGreenColorBlindMode();">
                <span class="slider"></span>
            </label>
        </div>
        <p class="option_header">Show alerts</p>
        <div class="option_container">
            <label class="switch">
                <input type="checkbox" onchange="showAlerts();" checked>
                <span class="slider"></span>
            </label>
        </div>

        <p class="option_header">Keep sidebar open</p>
        <div class="option_container">
            <label class="switch">
                <input type="checkbox" onchange="keepSidebarOpen();" checked>
                <span class="slider"></span>
            </label>
        </div>

    </div>
</div>