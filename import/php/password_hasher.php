<?php
$currentHashAlgorithm = PASSWORD_DEFAULT; // bcrypt by default 
$currentHashOptions = array('cost' => 16);
// Hash the password (no thrills)
function bcryptHash($pwd)
{
    global $currentHashAlgorithm, $currentHashOptions;
    $bcryptPassword = password_hash($pwd, $currentHashAlgorithm, $currentHashOptions);
    return $bcryptPassword;
}
function passwordHash($mysqli, $username, $pwd)
{
    $hashedPassword = $pwd;
    $stmt = $mysqli->prepare("SELECT * FROM factory_user_accounts WHERE user_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $sha256password = hash("sha256", $pwd);
        global $currentHashAlgorithm, $currentHashOptions;
        /* Hashing time: ~ 3.4401268959045 seconds */
        $startTime = microtime(true);
        $bcryptPassword = password_hash($pwd, $currentHashAlgorithm, $currentHashOptions);
        $endTime = microtime(true);
        error_log("Hashing time: " . ($endTime - $startTime) . " seconds");
        $hashedPassword = $bcryptPassword;
        if ($user['user_password'] === $sha256password) {
            $stmtShaToBcrypt = $mysqli->prepare("UPDATE factory_user_accounts SET user_password = ? WHERE user_username = ?");
            $stmtShaToBcrypt->bind_param("ss", $bcryptPassword, $username);
            $stmtShaToBcrypt->execute();
            $stmtShaToBcrypt->close();
            $user['user_password'] = $bcryptPassword;
        }
        if (password_verify($pwd, $user['user_password'])) {
            if (password_needs_rehash($user['user_password'], $currentHashAlgorithm, $currentHashOptions)) {
                $bcryptPasswordRehashed = password_hash($pwd, $currentHashAlgorithm, $currentHashOptions);
                $stmtRehash = $mysqli->prepare("UPDATE factory_user_accounts SET user_password = ? WHERE user_username = ?");
                $stmtRehash->bind_param("ss", $bcryptPasswordRehashed, $username);
                $stmtRehash->execute();
                $stmtRehash->close();
                $hashedPassword = $bcryptPasswordRehashed;
            }
        } else {
            error_log("Hashed version of unhashed PWD does not match hashed PWD in DB. Could be the wrong PWD");
        }
    }
    return $hashedPassword;
}
