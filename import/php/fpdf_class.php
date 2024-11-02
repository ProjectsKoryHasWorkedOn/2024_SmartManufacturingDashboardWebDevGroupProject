<?php
require_once("php_resource_paths.php");
include_once($fdpfFilePath);
class PDF extends FPDF
{
    public $employee_name;
    public $companyLogoPictureFilePath;
    function __construct($pictureFilePath, $name)
    {
        parent::__construct();
        $this->companyLogoPictureFilePath = $pictureFilePath;
        $this->employee_name = $name;
    }
    function Header()
    {
        $headerWidth = 100;
        $pageWidth = $this->GetPageWidth();
        $centre = ($pageWidth - $headerWidth) / 2;
        $this->SetDrawColor(50, 145, 175);
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        // Add the logo image to the header
        if (!empty($this->companyLogoPictureFilePath)) {
            $this->Image($this->companyLogoPictureFilePath, 10, 6, 20);
        }
        $this->SetX($centre);
        $this->SetFont('Helvetica', 'B', 18);
        $this->Cell($headerWidth, 20, $this->employee_name, 1, 0, 'C');
        $this->Ln(30);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    function sectionTitle($title)
    {
        $this->SetFont('Helvetica', '', 14);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $title, 0, 1, 'L', true);
        $this->Ln(4);
    }
}
