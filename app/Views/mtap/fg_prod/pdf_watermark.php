<?php
namespace App\Libraries\Fpdf;
class pdf_watermark extends Fpdf
{
    protected $watermarkText;
    
    public function SetWatermarkText($text)
    {
        $this->watermarkText = $text;
    }
    
    public function Watermark()
    {
        $this->SetFont('Arial', 'B', 50);
        $this->SetTextColor(255, 192, 203); // Set the color of the watermark text
        
        // Rotate the page to place the watermark diagonally
        $this->Rotate(45, 100, 100);
        
        $textWidth = $this->GetStringWidth($this->watermarkText);
        $textHeight = 6; // Adjust the height of the watermark text
        
        // Calculate the center position to place the watermark
        $x = ($this->GetPageWidth() - $textWidth) / 2;
        $y = ($this->GetPageHeight() - $textHeight) / 2;
        
        // Output the watermark text
        $this->Text($x, $y, $this->watermarkText);
        
        // Reset rotation
        $this->Rotate(0);
    }
}
?>