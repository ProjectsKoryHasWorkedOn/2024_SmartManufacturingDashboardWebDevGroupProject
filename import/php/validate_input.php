<?php
// Protection against SQL injection and XSS attacks
function sanitizeString($mysqli, $string)
{
    return strip_tags(trim(htmlentities($mysqli->real_escape_string($string), ENT_QUOTES, 'UTF-8')));
}
function passwordContainsNameOfPerson($firstName, $lastName, $password)
{
    $firstNameLowerCase = strtolower($firstName);
    $lastNameLowerCase = strtolower($lastName);
    $passwordLowerCase = strtolower($password);
    $firstNameChars = strlen($firstNameLowerCase);
    $lastNameChars = strlen($lastNameLowerCase);
    // See number of chars in whole first name and last name string appear in PWD
    $numberOfFirstNameCharsAppearingInPWD = similar_text($firstNameLowerCase, $passwordLowerCase);
    $numberOfLastNameCharsAppearingInPWD = similar_text($lastNameLowerCase, $passwordLowerCase);
    if ($numberOfFirstNameCharsAppearingInPWD == $firstNameChars || $numberOfLastNameCharsAppearingInPWD == $lastNameChars) {
        return true;
    } else {
        return false;
    }
}
// Protection against dictionary attacks. Stop user from setting a easily discoverable PWD
function foundPasswordInBadPasswordListFile($file, $password)
{
    $fileHandle = fopen($file, 'r');
    // Check if file could be opened
    if (!$fileHandle) {
        throwAnError(__LINE__, __FILE__, "Could not open the file");
    }
    while (!feof($fileHandle)) {
        // Get PWD from file
        $badPassword = trim(fgets($fileHandle));
        // See if it matches PWD user wants
        if ($badPassword === $password) {
            return true;
        }
    }
    return false;
}
