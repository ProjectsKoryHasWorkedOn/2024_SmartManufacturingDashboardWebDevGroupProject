<?php
require_once("php_resource_paths.php");
require_once($errorThrowerFilePath);
require_once($updatingProfileFilePath);
?>
<div class="popup" id="update_profile_screen">
    <div class="right_topmost_button_container">
        <button id="close_button" class="button tiny_button" onClick="closeUpdateProfilePopUp();">&#10006;</button>
    </div>
    <div class="options_container">
        <h1>Update user details</h1>
        <h2 class="option_header">Update password</h2>
        <ul class="popup_list">
            <li>Must have a letter</li>
            <li>Must have a number</li>
            <li>Must have a space somewhere after the first and before the last character</li>
            <li>Must have at least one uppercase letter</li>
            <li>Must have at least one lowercase letter</li>
            <li>Must have at least one special character from this set <br> <b>{</b><span class="numbers_font">! ( ) * +
                    , - . / : ; ? [ ] _</span><b>}</b></li>
            <li>Must be at least 20 characters</li>
            <li>Must be less than 255 characters</li>
            <li>Must not contain characters from this set <br> <b>{</b><span class="numbers_font">
                    <> ^ ` { } ~ = ' " \ ; $ @
                </span><b>}</b></li>
            <li>Must not contain your name</li>
            <li>Must not contain leading or trailing spaces</li>
            <li>Must not appear in a list of ill-advised passwords</li>
            <li>Users are advised to use a password manager or sentence passwords</li>
        </ul>
        <img src="import/img/xkcd_comic.png">
        <form method="POST" action="import/page_elements/updating_profile.php">
            <label for="current_password">Current password:</label><br>
            <input type="text" class="input_field" name="current_password" required><br>
            <label for="new_password">New password:</label><br>
            <input type="text" class="input_field" name="new_password" required><br>
            <label for="new_password_again">New password (again):</label><br>
            <input type="text" class="input_field" name="new_password_again" required><br>
            <input class="button" type="submit" value="Update">
        </form>
        <h2 class="option_header">Update e-mail address</h2>
        <form method="POST" action="import/page_elements/updating_profile.php">
            <label for="new_email">New e-mail address:</label><br>
            <input type="text" class="input_field" name="new_email" required><br>
            <input class="button" type="submit" value="Update">
        </form>
        <h2 class="option_header">Update profile picture</h2>
        <p>Accepts all image types</p>
        <form method="POST" action="import/page_elements/updating_profile.php" enctype="multipart/form-data">
            <label class="button upload_button">
                <input type="file" class="input_field" id="user_profile_picture_upload_button"
                    name="user_profile_picture" accept="image/*" required />
                Upload
            </label><br>
            <input class="button" type="submit" value="Update">
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const userProfilePictureUploadButton = document.getElementById('user_profile_picture_upload_button');
                userProfilePictureUploadButton.addEventListener('change', function () {
                    if (userProfilePictureUploadButton.files.length > 0) {
                        alert("Selected" + " " + userProfilePictureUploadButton.files[0].name);
                    }
                });
            });
        </script>
    </div>
</div>
</div>