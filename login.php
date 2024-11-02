<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}
require_once("php_resource_paths.php");
require_once($DBconnectionFilePath);
require_once($setIconFilePath);
require_once($passwordHasherFilePath);
require_once($sessionHandlingFilePath);
require_once($dashboardLoginPHPFilePath);
require_once($errorThrowerFilePath);
require_once($resetMessageFilePath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require_once($headFilePath); ?>
   <title>Login Page</title>
   <script type="text/javascript" src="import/js/passwordToggle.js"></script>
   <script src="<?php echo $bannerMessagePath; ?>"> </script>
</head>
<body>
   <script>
      document.addEventListener("DOMContentLoaded", function() {
         <?php
         if (isset($message) && ($message != '')) {
            echo 'updateMessage("' . addslashes($message) . '", "login_outcome_banner", "message_text");';
         }
         ?>
      });
   </script>
   <div class="outcome_banner" id="login_outcome_banner">
      <p id="message_text"><?php echo html_entity_decode(htmlspecialchars($message, ENT_QUOTES)); ?></p>
   </div>
   <div id="login_container">
      <!-- Company logo -->
      <img class="company_logo" src="import/img/company_logo.png">
      <h1 id="login_header_text_color">Login</h1>
      <form method="post" class="form">
         <input type="text" name="username" class="input_field" autocomplete="off" placeholder="Username" required />
         <div id="password_field_container">
            <input id="password_field" type="password" class="input_field" id="passkey" name="password" placeholder="Password" required autocomplete="off" />
            <img id="show_hide_password_image" src="import/img/password_hidden.png" alt="Show Password" onclick="togglePasswordVisibility()">
         </div>
         <!-- Made user select a branch on log-in and updated branch user is at in DB
            So some poor soul doesn't have to update the DB every single time that an employee jumps between two branches
            * Not as problematic if trips are irregular (e.g. every few years an interstate trip is made)
            * Much more problematic if trips are frequent (e.g. every day a within city trip is made)
            -->
         <div id="branch_container">
            <select class="input_field input_class_select_field" name="branch_address" required>
               <?php
               // Dynamically fill list of branches
               $sql = $mysqli->query("SELECT branch_street_address FROM factory_branches");
               if (!$sql) {
                  throwAnError(__LINE__, __FILE__, "Query failed");
               }
               if ($sql->num_rows > 0) {
                  while ($row = $sql->fetch_array(MYSQLI_ASSOC)) {
               ?>
                     <option
                        value="<?php echo $row['branch_street_address']; ?>">
                        <?php echo $row['branch_street_address']; ?>
                     </option>
               <?php
                  }
                  $sql->free();
               } else {
                  throwAnError(__LINE__, __FILE__, "No branches found");
               }
               ?>
            </select>
         </div>
         <input type="submit" name="submit" id="login_page_button" class="button" value="Enter">
      </form>
   </div>
</body>
</html>