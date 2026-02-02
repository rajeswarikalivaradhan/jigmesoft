<?php
require('fpdf.php');

class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->Image('logo.png',10,0,30);
        // Arial bold 15
        $this->SetFont('Arial','',15);
        // Move to the right
        $this->Cell(160,0,'',0,0);
        // Title
        $this->Cell(30,10,'INVOICE',0,1,'R');
// Set font
        $this->SetFont('Arial','B',12);
        $this->Cell(30,10,'BUSINESS SUPPORT GRPUP',0,1,'L');
        $this->SetFont('Arial','',10);
        $this->Cell(30,5,'Old No 15/1, New No 30/1, 1st Floor,',0,1,'L');
        $this->Cell(30,5,'SRP Nagar, 2nd Cross Road, Saibaba Colony,',0,1,'L');
        $this->Cell(30,5,'Coimbatore - 641011',0,1,'L');
        // Line break
        //$this->Ln(20);
    }

    function SetCol($col)
    {
        // Set position at a given column
        $this->col = $col;
        $x = 10+$col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
    }
/*
    protected $col = 0; // Current column
    protected $y0;      // Ordinate of column start

    function Header()
    {
        // Page header
        global $title;

        $this->SetFont('Arial','B',15);
        $w = $this->GetStringWidth($title)+6;
        $this->SetX((210-$w)/2);
        $this->SetDrawColor(0,80,180);
        $this->SetFillColor(230,230,0);
        $this->SetTextColor(220,50,50);
        $this->SetLineWidth(1);
        $this->Cell($w,9,$title,1,1,'C',true);
        $this->Ln(10);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    function Footer()
    {
        // Page footer
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    function SetCol($col)
    {
        // Set position at a given column
        $this->col = $col;
        $x = 10+$col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak()
    {
        // Method accepting or not automatic page break
        if($this->col<2)
        {
            // Go to next column
            $this->SetCol($this->col+1);
            // Set ordinate to top
            $this->SetY($this->y0);
            // Keep on page
            return false;
        }
        else
        {
            // Go back to first column
            $this->SetCol(0);
            // Page break
            return true;
        }
    }

    function ChapterTitle($num, $label)
    {
        // Title
        $this->SetFont('Arial','',12);
        $this->SetFillColor(200,220,255);
        $this->Cell(0,6,"Chapter $num : $label",0,1,'L',true);
        $this->Ln(4);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    function ChapterBody($file)
    {
        // Read text file
        $txt = file_get_contents($file);
        // Font
        $this->SetFont('Times','',12);
        // Output text in a 6 cm width column
        $this->MultiCell(60,5,$txt);
        $this->Ln();
        // Mention
        $this->SetFont('','I');
        $this->Cell(0,5,'(end of excerpt)');
        // Go back to first column
        $this->SetCol(0);
    }

    function PrintChapter($num, $title, $file)
    {
        // Add chapter
        $this->AddPage();
        $this->ChapterTitle($num,$title);
        $this->ChapterBody($file);
    }
    */
// Better table
    function ImprovedTable($header, $data)
    {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],'',0,'C');
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'');
            $this->Cell($w[1],6,$row[1],'');
            $this->Cell($w[2],6,number_format($row[2]),'',0,'R');
            $this->Cell($w[3],6,number_format($row[3]),'',0,'R');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','');
    }
}