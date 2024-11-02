DROP DATABASE IF EXISTS factory_db;
CREATE DATABASE factory_db;
USE factory_db;
/* Tables with no FKs */
CREATE TABLE `factory_branches` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_country` varchar(20) NOT NULL,
  `branch_city` varchar(20) NOT NULL,
  `branch_timezone` varchar(20) NOT NULL,
  `branch_street_address` varchar(40) NOT NULL,
  PRIMARY KEY(`branch_id`)
);
CREATE TABLE `factory_machine_types` (
  `machine_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_name` varchar(30) NOT NULL,
  PRIMARY KEY (`machine_type_id`)
);
CREATE TABLE `factory_shifts` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_period` varchar(30)  NOT NULL CHECK (`shift_period` in ('day','afternoon','night')),
  PRIMARY KEY (`shift_id`)
);
/* Tables with one FK */
CREATE TABLE `factory_employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_branch_id` int(11) NOT NULL,
  `employee_first_name` varchar(20) NOT NULL,
  `employee_last_name` varchar(20) NOT NULL,
  `employee_email_address` varchar(255) NOT NULL UNIQUE,
  `employee_role` varchar(30) NOT NULL CHECK (`employee_role` in ('production operator','admin staff','maintenance worker', 'factory manager', 'internal auditor')),
  `employee_salary` DECIMAL(9,2) DEFAULT NULL,
  `employee_hourly_rate` DECIMAL(5,2) DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  FOREIGN KEY (`employee_branch_id`) REFERENCES `factory_branches`(`branch_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_inventory` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_branch_id` int(11) NOT NULL,
  `stock_name` VARCHAR(50) NOT NULL,
  `stock_quantity` DECIMAL(12,2) NOT NULL,
  PRIMARY KEY(`stock_id`),
  FOREIGN KEY (`stock_branch_id`) REFERENCES `factory_branches`(`branch_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_employee_id` int(11) NOT NULL,
  `recipient_employee_id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `message_title` varchar(255) NOT NULL,
  `message_body` TEXT NOT NULL,
  `message_date` DATE NOT NULL,
  `message_attachment` longblob DEFAULT NULL,
  PRIMARY KEY(`message_id`),
  FOREIGN KEY (`sender_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE,
  FOREIGN KEY (`recipient_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_user_accounts` (
  `user_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_employee_id` int(11) NOT NULL,
  `user_username` varchar(25) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `user_profile_picture` varchar(256) NOT NULL,
  PRIMARY KEY (`user_account_id`),
  FOREIGN KEY (`user_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_grievances` (
  `grievance_id` int(11) NOT NULL AUTO_INCREMENT,
  `grievance_employee_id` int(11) NOT NULL,
  `grievance_date` DATE NOT NULL,
  `grievance_description` TEXT NOT NULL,
  `grievance_action_taken` TEXT NOT NULL,
  PRIMARY KEY(`grievance_id`),
  FOREIGN KEY (`grievance_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_employee_id` int(11) NOT NULL,
  `payment_date_period_start` DATE NOT NULL,
  `payment_date_period_end` DATE NOT NULL,
  `payment_amount` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`payment_id`),
  FOREIGN KEY (`payment_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_time_period_worked` (
  `working_time_period_id` int(11) NOT NULL AUTO_INCREMENT,
  `working_time_period_employee_id` int(11) NOT NULL,
  `employee_shift_id` int(11) NOT NULL,
  `employee_clock_in_date` DATE NOT NULL,
  `employee_clock_in_time` TIME NOT NULL,
  `employee_clock_off_date` DATE NULL,
  `employee_clock_off_time` TIME NULL,
  PRIMARY KEY (`working_time_period_id`),
  FOREIGN KEY (`working_time_period_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE,
  FOREIGN KEY (`employee_shift_id`) REFERENCES `factory_shifts`(`shift_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_breaks_taken` (
  `break_period_id` int(11) NOT NULL AUTO_INCREMENT,
  `break_taker_employee_id` int(11) NOT NULL,
  `break_start_date` DATE NOT NULL,
  `break_start_time` TIME NOT NULL,
  `break_finish_date` DATE NOT NULL,
  `break_finish_time` TIME NOT NULL,
  `break_type` varchar(30) NOT NULL CHECK (`break_type` in ('rest break','meal break')),
  PRIMARY KEY (`break_period_id`),
  FOREIGN KEY (`break_taker_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_employee_overtime_worked` (
  `overtime_period_id` int(11) NOT NULL AUTO_INCREMENT,
  `overtime_employee_id` int(11) NOT NULL,
  `overtime_start_date` DATE NOT NULL,
  `overtime_start_time` TIME NOT NULL,
  `overtime_finish_date` DATE NOT NULL,
  `overtime_finish_time` TIME NOT NULL,
  PRIMARY KEY (`overtime_period_id`),
  FOREIGN KEY (`overtime_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_safety_incidents` (
  `incident_id` int(11) NOT NULL AUTO_INCREMENT,
  `incident_employee_id` int(11) NOT NULL,
  `incident_date` DATE NOT NULL,
  `incident_description` TEXT NOT NULL,
  `incident_outcome` TEXT NOT NULL,
  PRIMARY KEY (`incident_id`),
  FOREIGN KEY (`incident_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
CREATE TABLE `factory_jobs` (
  /* Matches DB schema as of 28/08/2024 */
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_employee_id` int(11) NOT NULL,
  `job_assigned_date` DATE NOT NULL,
  `job_completed_date` DATE DEFAULT NULL,
  `job_description` text NOT NULL,
  `job_status` varchar(20) NOT NULL,
  `job_task_notes` text NOT NULL,
  `job_priority` TINYINT(1) NOT NULL CHECK (`job_priority` in ('1','2','3', '4', '5')),
  PRIMARY KEY(`job_id`),
  FOREIGN KEY(`job_employee_id`) REFERENCES `factory_employees`(`employee_id`) ON DELETE CASCADE
);
/* Tables with two FKs */
CREATE TABLE `factory_machines` (
  `machine_id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_type_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`machine_id`),
  FOREIGN KEY(`machine_type_id`) REFERENCES `factory_machine_types`(`machine_type_id`) ON DELETE CASCADE,
  FOREIGN KEY(`branch_id`) REFERENCES `factory_branches`(`branch_id`) ON DELETE CASCADE
);

CREATE TABLE `factory_machine_statuses` (
  `machine_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL,
  `temperature` float(8,2) NOT NULL,
  `pressure` float(8,2) NOT NULL,
  `vibration` float(8,2) NOT NULL,
  `humidity` float(8,2) NOT NULL,
  `power_consumption` float(8,2) NOT NULL,
  `operational_status` varchar(18) NOT NULL,
  `error_code` varchar(10) DEFAULT NULL,
  `production_count` float(8,2) NOT NULL,
  `maintenance_log` varchar(20) DEFAULT NULL,
  `speed` float(8,2) DEFAULT NULL,
  PRIMARY KEY(`machine_status_id`), 
  FOREIGN KEY(`machine_id`) REFERENCES `factory_machines`(`machine_id`) ON DELETE CASCADE
);