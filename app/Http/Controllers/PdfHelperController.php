<?php

namespace App\Http\Controllers;

use App\Models\ReturnTheQuantity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfHelperController extends Controller
{
    public static function data()
    {
        $data = ReturnTheQuantity::all();
        $today = Carbon::now()->format('Y / m / d');

        $html = view('report.index', [
            'data' => $data,
            'today' => $today,
        ])->render();
        return  self::downloadPdf($html);
    }

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

    public static function printApiPdf($html , $format = '-L')
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

    public static function downloadPdf($html , $format = '-L')
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

        // Create a streamed response for the PDF file
        $response = new StreamedResponse(function () use ($pdfContent) {
            echo $pdfContent;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);

        return $response;
    }
}
