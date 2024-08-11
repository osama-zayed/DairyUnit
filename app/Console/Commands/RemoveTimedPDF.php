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
    protected $signature = 'app:remove-timed-pdf';

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

        $filesystem = new Filesystem();
        $pdfDirectory = public_path('storage/pdf');
        if ($filesystem->exists($pdfDirectory)) {
            $files = $filesystem->files($pdfDirectory);
            foreach ($files as $file) {
                $filesystem->delete($file);
            }
            dd('The timed PDF files have been removed successfully.');
        } else {
            dd('No PDF files found in the directory.');
        }
    }
}
