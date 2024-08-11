<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveTimedPDFJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
