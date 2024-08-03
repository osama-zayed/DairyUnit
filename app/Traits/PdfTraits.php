<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait PdfTraits
{
    public static function printPdf($html, $format = '-L')
    {
        // Set up mPDF with UTF-8 support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4' . $format,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');
        // Return the PDF response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }

    public static function printApiPdf($html, $format = '-L')
    {
        // Set up mPDF with UTF-8 support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4' . $format,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Generate a unique file name for the PDF
        $fileName = 'report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';

        // Save the PDF to the local file system
        $pdfContent = $mpdf->Output($fileName, 'S');

        // Store the PDF file in the storage
        Storage::disk('public')->put('pdf/' . $fileName, $pdfContent);

        // Return the URL of the generated PDF file
        return self::responseSuccess([
            'url' => asset('storage/pdf/' . $fileName)
        ]);
    }

    public static function downloadPdf($html, $format = '-L')
    {
        // Set up mPDF with UTF-8 support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4' . $format,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Generate a unique file name for the PDF
        $fileName = 'report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';

        // Save the PDF to the local file system
        $pdfContent = $mpdf->Output($fileName, 'S');

        // Store the PDF file in the storage
        Storage::disk('public')->put('pdf/' . $fileName, $pdfContent);

        // Return the URL of the generated PDF file
        return response()->json([
            'status' => 'true',
            'data' => [
                'url' => asset('storage/pdf/' . $fileName),
            ],
            'message' => '',
        ], 200,[
            'Content-Type', 'application/pdf','Content-Disposition', 'attachment; filename="report.pdf"'
        ]);
    }
}
