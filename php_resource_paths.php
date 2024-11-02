<?php
// Project folder
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/www/";
$projectDirectoryName = "solo_project/";
$projectPath = $rootPath . $projectDirectoryName;
// Import folder 
$importFolderName = "import/";
// Icon imports
$iconFolderName = "ico/";
$companyLogoIconFileName = "logo.ico";
$ghostIconFileName = "ghost.ico";
$fastTravelToIconsFolderPath = $projectPath . $importFolderName . $iconFolderName;
$companyLogoIconFilePath = $fastTravelToIconsFolderPath . $companyLogoIconFileName;
$ghostIconFilePath = $fastTravelToIconsFolderPath . $ghostIconFileName;
// PHP imports
$managingRecordsFilename = 'managing_records.php';
$PHPFolderName = "php/";
$DBconfigurationFilename = "db_config.php";
$DBconnectionFilename = "db_connect.php";
$dashboardLoginPHPFilename = "dashboard_login.php";
$currentTimePHPFilename = "current_time.php";
$checkInputPHPFilename = "check_input.php";
$insertRecordPHPFilename = "insert_record.php";
$crudOperationsPHPFileName = "db_crud.php";
$setIconFileName = "set_icon.php";
$sessionStorageValuesFilename = "session_storage_values.php";
$redirectUsersFilename = "redirect.php";
$lotsOfTimesPHPFilename = "lots_of_times.php";
$resetMessageFilename = "reset_message.php";
$errorThrowerFilename = 'error_thrower.php';
$obtainShowingAlertsValueFilename = 'obtain_showing_alerts_value.php';
$passwordHasherFilename = "password_hasher.php";
$sessionHandlingFilename = "session_handling.php";
$validateInputFilename = "validate_input.php";
$updateRecordFilename = "update_record.php";
$deleteRecordFilename = "delete_record.php";
$employeeNamesFilename = "employee_names_array.php";
$fdpfFilename = "fpdf.php";
$fdpfClassFilename = "fpdf_class.php";
$sortColumnFilename = "sort_column.php";

$fastTravelToPHPFolderPath = $projectPath . $importFolderName . $PHPFolderName;
$DBconfigurationFilePath = $fastTravelToPHPFolderPath . $DBconfigurationFilename;
$DBconnectionFilePath = $fastTravelToPHPFolderPath . $DBconnectionFilename;
$dashboardLoginPHPFilePath = $fastTravelToPHPFolderPath . $dashboardLoginPHPFilename;
$checkInputPHPFilePath = $fastTravelToPHPFolderPath . $checkInputPHPFilename;
$insertRecordPHPFilePath = $fastTravelToPHPFolderPath . $insertRecordPHPFilename;
$crudOperationsPHPFilePath = $fastTravelToPHPFolderPath . $crudOperationsPHPFileName;
$setIconFilePath = $fastTravelToPHPFolderPath . $setIconFileName;
$sessionStorageValuesFilePath = $fastTravelToPHPFolderPath . $sessionStorageValuesFilename;
$redirectUsersFilePath = $fastTravelToPHPFolderPath . $redirectUsersFilename;
$resetMessageFilePath = $fastTravelToPHPFolderPath . $resetMessageFilename;
$errorThrowerFilePath = $fastTravelToPHPFolderPath . $errorThrowerFilename;
$obtainShowingAlertsValueFilePath = $fastTravelToPHPFolderPath . $obtainShowingAlertsValueFilename;
$passwordHasherFilePath = $fastTravelToPHPFolderPath . $passwordHasherFilename;
$sessionHandlingFilePath = $fastTravelToPHPFolderPath . $sessionHandlingFilename;
$validateInputFilePath = $fastTravelToPHPFolderPath . $validateInputFilename;
$updateRecordFilePath = $fastTravelToPHPFolderPath . $updateRecordFilename;
$deleteRecordFilePath = $fastTravelToPHPFolderPath . $deleteRecordFilename;
$sortColumnFilePath = $fastTravelToPHPFolderPath . $sortColumnFilename;
$employeeNamesFilePath = $fastTravelToPHPFolderPath . $employeeNamesFilename;
$fdpfFilePath = $fastTravelToPHPFolderPath . $fdpfFilename;
$fdpfClassFilePath = $fastTravelToPHPFolderPath . $fdpfClassFilename;
$currentTimePHPFilePath = $importFolderName . $PHPFolderName . $currentTimePHPFilename;
// eventSource does not want projectPath in it
$lotsOfTimesPHPFilePath = $importFolderName . $PHPFolderName . $lotsOfTimesPHPFilename;
// eventSource does not want projectPath in it
// Page element imports
$importPageElementsFolderName = "page_elements/";
$fastTravelToPageElementsFolderPath = $projectPath . $importFolderName . $importPageElementsFolderName;
$sidebarFilename = "sidemenu.php";
$headFilename = "head.php";
$hotkeyFilename = 'hotkeys.php';
$headerFilename = "header.php";
$footerFilename = "footer.php";
$timeLoggedInPageFilename = "time_logged_in_page.php";
$timeInHeaderFilename = "time_in_header.php";
$timeInHeaderJobsToDoFilename = "time_in_header_jobs_to_do.php";
$headerJobsToDoFilename = 'header_jobs_to_do.php';
$updateProfileFilename = "update_profile.php";
$settingsMenuFilename = "settings_menu.php";
$updatingProfileFilename = "updating_profile.php";
$sidebarFilePath = $fastTravelToPageElementsFolderPath . $sidebarFilename;
$headerFilePath = $fastTravelToPageElementsFolderPath . $headerFilename;
$footerFilePath = $fastTravelToPageElementsFolderPath . $footerFilename;
$timeLoggedInPageFilePath = $fastTravelToPageElementsFolderPath . $timeLoggedInPageFilename;
$timeInHeaderFilePath = $fastTravelToPageElementsFolderPath . $timeInHeaderFilename;
$hotkeysFilePath = $fastTravelToPageElementsFolderPath . $hotkeyFilename;
$headFilePath = $fastTravelToPageElementsFolderPath . $headFilename;
$updateProfileFilePath = $fastTravelToPageElementsFolderPath . $updateProfileFilename;
$updatingProfileFilePath = $fastTravelToPageElementsFolderPath . $updatingProfileFilename;
$settingsMenuFilePath = $fastTravelToPageElementsFolderPath . $settingsMenuFilename;
$headerJobsToDoFilePath = $fastTravelToPageElementsFolderPath . $headerJobsToDoFilename;
$timeInHeaderJobsToDoFilePath = $fastTravelToPageElementsFolderPath . $timeInHeaderJobsToDoFilename;
// JS imports
$jsFolderName = "js/";
$bannerMessageFilename = 'bannerMessage.js';
$moveCurrentTimeElementFilename = "moveTimeFromHeaderToFooter.js";
$bannerMessagePath = $importFolderName . $jsFolderName . $bannerMessageFilename;
$moveCurrentTimeElementPath = $importFolderName . $jsFolderName . $moveCurrentTimeElementFilename;
$pathToSessionStorageFile = 'https://localhost/www/solo_project/import/php/session_storage_values.php';
// Txt file imports
$txtFolderName = "txt/";
$worstPasswordsListFilename = 'bad_passwords.txt';
$worstPasswordsListFilePath = $importFolderName . $txtFolderName . $worstPasswordsListFilename;
// Video file imports
$videoFolderName = "video/";
$productionOperatorInductionVideoFilename = "induction_video.mp4";
$adminStaffInductionVideoFilename = "induction_video.mp4";
$factoryManagerInductionVideoFilename = "induction_video.mp4";
$maintenanceWorkerInductionVideoFilename = "induction_video.mp4";
$internalAuditorInductionVideoFilename = "induction_video.mp4";
$productionOperatorInductionVideoFilePath = $importFolderName . $videoFolderName . $productionOperatorInductionVideoFilename;
$adminStaffInductionVideoFilePath = $importFolderName . $videoFolderName . $adminStaffInductionVideoFilename;
$factoryManagerInductionVideoFilePath = $importFolderName . $videoFolderName . $factoryManagerInductionVideoFilename;
$maintenanceWorkerInductionVideoFilePath = $importFolderName . $videoFolderName . $maintenanceWorkerInductionVideoFilename;
$internalAuditorInductionVideoFilePath = $importFolderName . $videoFolderName . $internalAuditorInductionVideoFilename;