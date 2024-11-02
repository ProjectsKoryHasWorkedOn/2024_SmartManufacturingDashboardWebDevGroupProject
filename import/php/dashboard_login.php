<?php
require_once("php_resource_paths.php");
require_once($validateInputFilePath);
/* Change to true to see ghost icon when it's not Halloween */
$itIsHalloween = false;
if ($mysqli->connect_error) {
	$errorAdditionalInformation = "Connection failed: " . $mysqli->connect_error;
	throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
}
if (isset($_POST['submit'])) {
	// Make sure username is entered
	if (empty($_POST['username'])) {
		$_SESSION['message'] = "Need to have entered a username";
		header("Location: login.php");
		exit();
	}
	// Make sure password is entered
	if (empty($_POST['password'])) {
		$_SESSION['message'] = "Need to have entered a password";
		header("Location: login.php");
		exit();
	}
	// Make sure password is entered
	if (empty($_POST['branch_address'])) {
		$_SESSION['message'] = "Need to have selected a branch";
		header("Location: login.php");
		exit();
	}
	// Check if the right username is entered
	if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['branch_address'])) {
		$username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');
		$branch_address = filter_input(INPUT_POST, 'branch_address');
		// Get branch associated with this address
		$sql_query_for_branch_address = "SELECT * FROM factory_branches WHERE branch_street_address = '$branch_address'";
		$sql_queried_branch_address = $mysqli->query($sql_query_for_branch_address);
		if ($sql_queried_branch_address->num_rows > 0) { /* Found branch */
			$factory_branches_row = $sql_queried_branch_address->fetch_object();
			$branch_employee_is_at_now = NULL;
			if (isset($factory_branches_row)) {
				if (isset($factory_branches_row->branch_id)) {
					$branch_employee_is_at_now = $factory_branches_row->branch_id;
					$_SESSION['branch_id'] = $branch_employee_is_at_now;
				} else {
					throwError(__LINE__, __FILE__, "Couldn't find branch ID");
				}
				if (isset($factory_branches_row->branch_city)) {
					$_SESSION['branch_city'] = $factory_branches_row->branch_city;
				} else {
					throwError(__LINE__, __FILE__, "Couldn't find branch city");
				}
				if (isset($factory_branches_row->branch_timezone)) {
					$_SESSION['branch_timezone'] = $factory_branches_row->branch_timezone;
				} else {
					throwError(__LINE__, __FILE__, "Couldn't find branch timezone");
				}
			} else {
				throwError(__LINE__, __FILE__, "Branch not set");
			}
		} else {
			$_SESSION['message'] = "Couldn't find this branch";
			header("Location: login.php");
			exit();
		}
		date_default_timezone_set($_SESSION['branch_timezone']);
		/* Timezone based on the location of the branch: $_SESSION['branch_timezone'] */
		// Security measures
		$username = sanitizeString($mysqli, $username);
		$password = sanitizeString($mysqli, $password);
		$unhashedPassword = $password;
		$password = passwordHash($mysqli, $username, $unhashedPassword);
		// Get user associated with this username and password
		$sql_query_for_employee_user_account_information = "SELECT * 
			FROM factory_user_accounts
			WHERE user_username = '$username'";
		// Need to check password after fetching username
		$sql_queried_for_employee_user_account_information = $mysqli->query($sql_query_for_employee_user_account_information);
		if (!$sql_queried_for_employee_user_account_information) {
			$errorAdditionalInformation = "Query failed: " . $mysqli->error;
			throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
		} else if ($sql_queried_for_employee_user_account_information->num_rows > 0) { /* Found user */
			$row_of_factory_employee_user_accounts_table = $sql_queried_for_employee_user_account_information->fetch_object();
			// Check if hashed version of unhashed PWD matches the one in the DB. Will fail if PWD is sha256. Shouldn't be sha256 though. Should be being converted to bcrypt
			if (password_verify($unhashedPassword, $row_of_factory_employee_user_accounts_table->user_password)) {
				// Password is valid
				// Get login date and time
				$loginDate = date("d/M/Y");
				$loginTime = date("g:i:sa");
				/* Could be used to change icon for Halloween */
				if (date("m-d") === "10-31") {
					$itIsHalloween = true;
				}
				if ($itIsHalloween) {
					changeIcon($ghostIconFilePath);
				}
				// Store data about the user in the session
				$_SESSION['username'] = $row_of_factory_employee_user_accounts_table->user_username;
				$_SESSION['user_logged_in'] = true;
				$_SESSION['user_login_date'] = $loginDate;
				$_SESSION['user_login_time'] = $loginTime;
				$_SESSION['user_profile_picture'] = $row_of_factory_employee_user_accounts_table->user_profile_picture;
				// Get name and last name of this user through another sql statement
				$user_employee_id_fk = $row_of_factory_employee_user_accounts_table->user_employee_id;
				$sql_query_for_employee_information = "SELECT * 
					FROM factory_employees
					WHERE employee_id = '$user_employee_id_fk'";
				$sql_queried_for_employee_information = $mysqli->query($sql_query_for_employee_information);
				if (!$sql_queried_for_employee_information) {
					$errorAdditionalInformation = "Query failed: " . $mysqli->error;
					throwAnError(__LINE__, __FILE__, $errorAdditionalInformation);
				}
				if ($sql_queried_for_employee_information->num_rows > 0) { /* Found employee */
					$row_of_factory_employees_table = $sql_queried_for_employee_information->fetch_object();
					$_SESSION['employee_email_address'] = $row_of_factory_employees_table->employee_email_address;
					$_SESSION['employee_id'] = $row_of_factory_employees_table->employee_id;
					$_SESSION['employee_first_name'] = $row_of_factory_employees_table->employee_first_name;
					$_SESSION['employee_last_name'] = $row_of_factory_employees_table->employee_last_name;
					$_SESSION['employee_role'] = $row_of_factory_employees_table->employee_role;
					$branch_employee_was_last_at_id = $row_of_factory_employees_table->employee_branch_id;
				} else {
					// Employee not found
					$_SESSION['message'] = "Wrong login details";
					header("Location: login.php");
					exit();
				}
				// Update branch user was at if it differs
				if ($branch_employee_is_at_now != $branch_employee_was_last_at_id) {
					if ($branch_employee_is_at_now == NULL) {
						throwAnError(__LINE__, __FILE__, "Branch ID not set");
					} else {
						$stmt = $mysqli->prepare("UPDATE factory_employees SET employee_branch_id = ? WHERE employee_id = ?");
						$stmt->bind_param("ii", $branch_employee_is_at_now, $_SESSION['employee_id']);
						$stmt->execute();
						$stmt->close();
					}
				}
				// Logged in
				setSessionID();
				header("Location: logged_in_page.php");
				exit();
			} else {
				// Password not found
				$_SESSION['message'] = "Wrong login details";
				header("Location: login.php");
				exit();
			}
		} else {
			// Username not found
			$_SESSION['message'] = "Wrong login details";
			header("Location: login.php");
			exit();
		}
	}
}