<?php

namespace Core\Printers;

use \mikehaertl\wkhtmlto\Pdf;

class PDFBuilder
{
    private $print_orientation;
    private $sheet_type;
    private $margin_top;
    private $margin_right;
    private $margin_bottom;
    private $margin_left;
    private $footer_font_size;
    private $footer_font_name;
    private $footer_spacing;

    public function __construct($print_orientation = 'portrait', $sheet_type = 'A4', $margin_top = 8, $margin_right = 6, $margin_bottom = 10, $margin_left = 6, $footer_font_size = 8, $footer_font_name = 'times', $footer_spacing = 2)
    {
        $this->print_orientation = $print_orientation;
        $this->sheet_type = $sheet_type;
        $this->margin_top = $margin_top;
        $this->margin_right = $margin_right;
        $this->margin_bottom = $margin_bottom;
        $this->margin_left = $margin_left;
        $this->footer_font_size = $footer_font_size;
        $this->footer_font_name = $footer_font_name;
        $this->footer_spacing = $footer_spacing;
    }

    public function print($title, $content, $footer)
    {
        $print_date = date('d/m/Y H:i:s');

        $pdf = new Pdf([
            'no-outline',
            'margin-top' => $this->margin_top,
            'margin-right' => $this->margin_right,
            'margin-bottom' => $this->margin_bottom,
            'margin-left' => $this->margin_left,
            'page-size' => $this->sheet_type,
            'orientation' => $this->print_orientation,
            'footer-right' => "Pagina: [page] de [toPage]",
            'footer-left' => "Impresso em: {$print_date} {$footer}",
            'footer-font-size' => $this->footer_font_size,
            'footer-font-name' => $this->footer_font_name,
            'footer-spacing' => $this->footer_spacing,
            'title' => $title,
            'encoding' => 'utf-8',
            'custom-header' => ['meta' => 'charset=utf-8']
        ]);
        $pdf->addPage($content);

        if (!$pdf->send("$title.pdf")) {
            $error = $pdf->getError();
            return $error;
        }
    }

    public function printWithoutFooter($title, $content)
    {
        $pdf = new Pdf(
            [
                'no-outline',
                'margin-top' => $this->margin_top,
                'margin-right' => $this->margin_right,
                'margin-bottom' => $this->margin_bottom,
                'margin-left' => $this->margin_left,
                'page-size' => $this->sheet_type,
                'orientation' => $this->print_orientation,
                'title' => $title
            ]
        );
        $pdf->addPage(utf8_encode($content));

        if (!$pdf->send("$title.pdf")) {
            $error = $pdf->getError();
            return $error;
        }
    }
}
