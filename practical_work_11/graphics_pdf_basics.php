<?php

declare(strict_types=1);
define('FPDF_FONTPATH', 'font/');
require('fpdf.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

function renderBlackSquare(): void {
    $image = imagecreatetruecolor(200, 200);

    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $white);

    imagefilledrectangle($image, 50, 50, 150, 150, $black);

    header('Content-Type: image/png');
    
    imagepng($image);
    imagedestroy($image);
}

function renderTextImage(string $text): void {
    $image = imagecreatetruecolor(200, 200);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $white);

    if (mb_strlen($text) < 50) {
        imagestring($image, 5, 10, 10, $text, $black);
    }
    else {
        imagestring($image, 5, 10, 10, "Many text!", $black);
    }
    
    header('Content-Type: image/png');

    imagepng($image);
    imagedestroy($image);
}

function renderTtfText(string $text, string $fontPath): void {
    $image = imagecreatetruecolor(200, 200);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $white);

    if (is_readable($fontPath)) {
        imagettftext($image, 20, 0, 50, 100, $black, $fontPath, $text);
    }

    else {
        imagestring($image, 5, 10, 10, "File is not exist!", $black);
    }

    header('Content-Type: image/png');

    imagepng($image);
    imagedestroy($image);
}

function renderButton(string $text, string $bgImagePath): void
{
    if (!file_exists($bgImagePath)) {
        die("Файл не найден: " . $bgImagePath);
    }
    
    $image = imagecreatefrompng($bgImagePath);
    
    
    $white = imagecolorallocate($image, 255, 255, 255);
    
    $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
    
    $width = imagesx($image);
    $height = imagesy($image);
    
    imagettftext($image, 20, 0, 50, $height / 2, $white, $fontPath, $text);
    
    header('Content-Type: image/png');
    
    imagepng($image);
    
    imagedestroy($image);
}

function getCachedImageOrGenerate(string $cacheDir, string $key, callable $generator): void {
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . md5($key) . '.png';

    if (file_exists($cacheFile)) {
        header('Content-Type: image/png');
        readfile($cacheFile);
        exit;
    }
    $image = $generator();
    
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    imagepng($image, $cacheFile);
    
    header('Content-Type: image/png');
    
    imagepng($image);
    
    imagedestroy($image);
}

function renderSimplePdf(string $message): void {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(40, 10, $message);
    $pdf->Output();
}

class InvoicePdf extends FPDF {
    public function Header() {
        $this->Image('image/user_ivan.png', 10, 8, 15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Score', 0, 1, 'C');
        $this->Ln(10);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' from {nb}', 0, 0, 'C');

        $this->Ln(8);
        $this->SetTextColor(0, 0, 255);
        $this->SetFont('Arial', 'U', 10);
        $this->Cell(0, 5, 'Look', 0, 1, 'C', false, 'https://example.com');
        $this->SetTextColor(0, 0, 0);
    }

    public function buildTable(array $header, array $data): void {
        $this->AliasNbPages();

        $this->AddPage();
        $this->SetFont('Arial', 'B', 12);

        
        $this->Cell(40, 10, $header[0], 1);
        $this->Cell(40, 10, $header[1], 1);
        $this->Cell(40, 10, $header[2], 1);
        $this->Ln();

        $this->SetFont('Arial', '', 12);

        foreach ($data as $row) {
            $this->Cell(40, 10, $row[0], 1);
            $this->Cell(40, 10, $row[1], 1);
            $this->Cell(40, 10, $row[2], 1);
            $this->Ln();
        }
        $this->Output();
    }
}

// renderBlackSquare();

// renderTextImage("Hello everybody!");

// renderTtfText("Привет!", "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf");

// renderButton("Привет", "/var/www/html/sustavov/practical_work_11/image/user_ivan.png");

// getCachedImageOrGenerate(
//     '/var/www/html/sustavov/practical_work_11/cache',
//     'user_ivan',
//     function() {
//         $image = imagecreatetruecolor(200, 200);
//         $white = imagecolorallocate($image, 255, 255, 255);
//         imagefill($image, 0, 0, $white);
        
//         $black = imagecolorallocate($image, 0, 0, 0);
//         imagefilledrectangle($image, 50, 50, 150, 150, $black);
        
//         return $image;
//     }
// );

// renderSimplePdf("hi");

// $mypdf = new InvoicePdf();
// $header = ["Name", "Count", "Price"];
// $product = [
//     ['Banana', '20', '2000'],
//     ['Apple', '150', '100'],
//     ['Milk', '200', '50']
// ];

// $mypdf -> buildTable($header, $product);