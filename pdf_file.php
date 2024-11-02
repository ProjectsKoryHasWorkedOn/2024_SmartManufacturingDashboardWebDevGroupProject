<?php
session_start();
require_once("php_resource_paths.php");
include_once($fdpfClassFilePath);
// Check if session variables are set
if (!isset($_SESSION['pdf_employee_name']) || !isset($_SESSION['pdf_total_amount_earned']) || !isset($_SESSION['pdf_pay_period'])) {
    die("Session variables not set.");
}
// Set PDF headers
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="payslip.pdf"');
// Create PDF instance
$pdf = new PDF('import/img/company_logo.png', $_SESSION['pdf_employee_name']);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
$pdf->sectionTitle('Employee details');
$employee_details_array = [];
if ($_SESSION['employee_role'] != '') {
    array_push($employee_details_array, 'Role: ' . $_SESSION['employee_role']);
}
if ($_SESSION['pdf_employee_salary'] != '') {
    array_push($employee_details_array, 'Salary: $' . $_SESSION['pdf_employee_salary']);
}
if ($_SESSION['pdf_employee_hourly_rate'] != '') {
    array_push($employee_details_array, 'Hourly rate: $' . $_SESSION['pdf_employee_hourly_rate']);
}
foreach ($employee_details_array as $line) {
    $pdf->Cell(0, 10, $line, 0, 1); // Display each line
}
$pdf->sectionTitle('Employee pay');
// Create content for the PDF
$payslip_array = array(
    'Payslip period: ' . $_SESSION['pdf_pay_period'],
    'Hours worked: ' . $_SESSION['pdf_total_hours_worked'],
    // '(Break hours this factors in: ' . $_SESSION['pdf_total_hours_spent_on_break'] . ')',
    // '(Overtime hours this factors in: ' . $_SESSION['pdf_total_overtime_hours'] . ')',
    'Amount earned: ' . '$ ' . $_SESSION['pdf_total_amount_earned']
);
// Output content to the PDF
foreach ($payslip_array as $line) {
    $pdf->Cell(0, 10, $line, 0, 1); // Display each line
}
// Output the PDF document
$pdf->Output('D', 'payslip.pdf'); // 'D' forces download