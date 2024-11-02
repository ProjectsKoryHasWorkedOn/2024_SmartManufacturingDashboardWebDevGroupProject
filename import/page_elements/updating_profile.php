<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/www/solo_project/php_resource_paths.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once($DBconnectionFilePath);
require_once($passwordHasherFilePath);
require_once($checkInputPHPFilePath);
require_once($validateInputFilePath);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_email'])) {
        $new_email = $_POST['new_email'];
        // Validate new email
        $new_email = checkEmail($new_email, $_SERVER['HTTP_REFERER']);
        // Get user associated with this username and set the new e-mail address
        $stmt = $mysqli->prepare("UPDATE factory_employees SET employee_email_address = ? WHERE employee_id = ?");
        $stmt->bind_param('ss', $new_email, $_SESSION['employee_id']);
        $stmt->execute();
        $stmt->close();
        // Redirect user back to page user was on
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    if (
        (isset($_POST['current_password'])) && (isset($_POST['new_password']))
        && (isset($_POST['new_password_again']))
    ) {
        /* What we'll be checking */
        $newPasswordRequirementsMet = false;
        /* What individual checks we'll be performing */
        $passedPasswordCharacterCheck = false;
        $passedUserRemembersPasswordCheck = false;
        $passedPasswordDoesNotAppearInDictionaryCheck = false;
        $passedPasswordDoesNotContainNameOfEmployeeCheck = false;
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $new_password_again = $_POST['new_password_again'];
        $hashed_current_password = passwordHash($mysqli, $_SESSION['username'], $current_password);
        // Validate new password
        $new_password = sanitizeString($mysqli, $new_password);
        // See if password is found in bad passwords list .txt file
        $pwd_found_in_bad_pwd_list = foundPasswordInBadPasswordListFile($worstPasswordsListFilePath, $new_password);
        if ($pwd_found_in_bad_pwd_list) {
            $passedPasswordDoesNotAppearInDictionaryCheck = false;
            $_SESSION['message'] = "New PWD easily discoverable so it can't be used. Come up with a better PWD";
            // Redirect user back to page user was on
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            $passedPasswordDoesNotAppearInDictionaryCheck = true;
        }
        // See if password contains person's name
        $pwd_contains_first_or_last_name_of_employee = passwordContainsNameOfPerson($_SESSION['employee_first_name'], $_SESSION['employee_last_name'], $new_password);
        if ($pwd_contains_first_or_last_name_of_employee) {
            $passedPasswordDoesNotContainNameOfEmployeeCheck = false;
            $_SESSION['message'] = "New PWD easily discoverable so it can't be used. Come up with a better PWD";
            // Redirect user back to page user was on
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            $passedPasswordDoesNotContainNameOfEmployeeCheck = true;
        }
        // Check if new password meets requirements for kinds of and character it can and cannot have as well as the length of characters
        /* START of use of AI logic **/
        /* Prompt was: How do I write regular expressions based on these specifications? */
        $hasLetter = preg_match('/[A-Za-z]/', $new_password);
        $hasLowercase = preg_match('/[a-z]/', $new_password);
        $hasUppercase = preg_match('/[A-Z]/', $new_password);
        $hasNumber = preg_match('/\d/', $new_password);
        $hasSpecialChar = preg_match('/[!()*+,\-.\:;?[\]_]/', $new_password);
        $hasNoProhibitedChars = !preg_match('/[<>^`{}~=\'"\\;$@]/', $new_password);
        $hasValidLength = strlen($new_password) >= 20 && strlen($new_password) <= 256;
        $hasNoLeadingTrailingSpaces = !preg_match('/^\s|\s$/', $new_password);
        /* END of use of AI logic **/
        if (!$hasLetter || !$hasLowercase || !$hasUppercase || !$hasNumber || !$hasSpecialChar || !$hasValidLength || !$hasNoProhibitedChars || !$hasNoLeadingTrailingSpaces) {
            $passedPasswordCharacterCheck = false;
            $_SESSION['message'] = "New password does not meet the requirements";
            // Redirect user back to the page user was on
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            $passedPasswordCharacterCheck = true;
        }
        // Check if user is aware of the new password (can enter it twice)
        if ($new_password != $new_password_again) {
            $passedUserRemembersPasswordCheck = false;
            $_SESSION['message'] = "New password does not match";
            // Redirect user back to page user was on
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            $passedUserRemembersPasswordCheck = true;
        }
        if ($passedPasswordCharacterCheck && $passedUserRemembersPasswordCheck && $passedPasswordDoesNotAppearInDictionaryCheck && $passedPasswordDoesNotContainNameOfEmployeeCheck) {
            $newPasswordRequirementsMet = true;
        }
        if ($newPasswordRequirementsMet == true) {
            // Get user associated with this username and password
            $sql_query_for_employee_user_account_information = $mysqli->prepare("SELECT * FROM factory_user_accounts WHERE user_username = ?");
            $sql_query_for_employee_user_account_information->bind_param('s', $_SESSION['username']);
            $sql_query_for_employee_user_account_information->execute();
            $result = $sql_query_for_employee_user_account_information->get_result();
            $sql_query_for_employee_user_account_information->close();
            if ($result->num_rows > 0) {
                $row_of_factory_employee_user_accounts_table = $result->fetch_object();
                // Check if hashed version of unhashed current user PWD matches the one in the DB
                if (password_verify($current_password, $row_of_factory_employee_user_accounts_table->user_password)) {
                    // Set the new password
                    $hashed_password = bcryptHash($new_password);
                    $stmt = $mysqli->prepare("UPDATE factory_user_accounts SET user_password = ? WHERE user_username = ?");
                    $stmt->bind_param('ss', $hashed_password, $_SESSION['username']);
                    $stmt->execute();
                    $stmt->close();
                    $_SESSION['message'] = "Updated password to the new password";
                    // Redirect user back to page user was on
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    $_SESSION['message'] = "Password entered for existing PWD is incorrect";
                    // Redirect user back to page user was on
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            } else {
                throwAnError(__LINE__, __FILE__, "Couldn't find user with this username");
            }
        }
    }
    /* Replace picture in import/img/employee_profile_pictures. It's in format last name_first name.extension. All lowercase */
    /*  Update extension of file indicated in DB path string. File name should be predictable */
    if (isset($_FILES['user_profile_picture'])) {
        $file = $_FILES['user_profile_picture'];
        // Validate uploaded file
        $file = checkImageFileType($file, $_SERVER['HTTP_REFERER']);
        $array = explode('.', $file['name']);
        $file_extension_for_uploaded_image_file = end($array);
        $full_file_name = strtolower($_SESSION['username'] . "_" . $_SESSION['employee_last_name'] . "_" . $_SESSION['employee_first_name'] . '.' . $file_extension_for_uploaded_image_file);
        
        
        $employeeProfilePicturesPath = "/www/solo_project/import/img/employee_profile_pictures/";
        $destinationPath = $_SERVER['DOCUMENT_ROOT'] . $employeeProfilePicturesPath;

        // Check if destination found
        if (is_dir($destinationPath)) {
            $fullPath = $destinationPath . $full_file_name;
            // Delete all other profile pictures with different names 
            $filesToDeleteHaveThisPath = $destinationPath . strtolower($_SESSION['username'] . "_" . $_SESSION['employee_last_name'] . "_" . $_SESSION['employee_first_name']) . '.*';
            $filesToDeletePattern = glob($filesToDeleteHaveThisPath);
            foreach ($filesToDeletePattern as $fileToDelete) {
                if (is_file($fileToDelete)) {
                    // Remove the file
                    unlink($fileToDelete);
                }
            }
            // Put new profile picture in folder
            move_uploaded_file($_FILES['user_profile_picture']['tmp_name'], $fullPath);
            // Update the DB with the new file extension
            $sql_query_for_users_table = "SELECT *
        FROM 
        factory_user_accounts
        WHERE
        user_username = ?;";
            $stmtut = $mysqli->prepare($sql_query_for_users_table);
            $stmtut->bind_param('s', $_SESSION['username']);
            $stmtut->execute();
            $result = $stmtut->get_result();
            $stmtut->close();
            $row_of_factory_users_table = $result->fetch_assoc();
            if ($row_of_factory_users_table) {
                $currentPathToImageFile = $row_of_factory_users_table['user_profile_picture'];

                $userProfilePictureProjectRootPath = 'C:/xampp/htdocs/www/solo_project/';

                $relativePath = str_replace($userProfilePictureProjectRootPath, '', $fullPath);
                
                // Update file path if it's different
                if (($relativePath != $currentPathToImageFile)) {
                    $new_path = $relativePath;
                    $sql_query_for_users_table = "UPDATE factory_user_accounts
                SET 
                user_profile_picture = ?
                WHERE
                user_username = ?;";
                    $stmt = $mysqli->prepare($sql_query_for_users_table);
                    $stmt->bind_param('ss', $new_path, $_SESSION['username']);
                    $stmt->execute();
                    $stmt->close();
                    // Update session variable with the new path
                    $_SESSION['user_profile_picture'] = $new_path;
                }


                // Redirect user back to page user was on
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                throwAnError(__LINE__, __FILE__, "User account not found");
            }
        } else {
            throwAnError(__LINE__, __FILE__, "Folder to move file to can't be found");
        }
    }
}