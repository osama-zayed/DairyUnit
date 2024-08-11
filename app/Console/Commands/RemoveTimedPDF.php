<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class RemoveTimedPDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:remove-timed-pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the timed PDF files of reports every day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $filesystem = new Filesystem();
        // $pdfDirectory = '/pdf';
        Storage::delete('public/pdf/report_2024-08-04_00-55-05.pdf');
        // if ($filesystem->exists($pdfDirectory)) {
        //     $files = $filesystem->files($pdfDirectory);
        //     foreach ($files as $file) {
        //         $filesystem->delete($file);
        //     }
        //     $this->info('The timed PDF files have been removed successfully.');
        // } else {
        //     $this->info('No PDF files found in the directory.');
        // }
    }
}
