<?php

namespace App\Console\Commands;

use App\Jobs\RemoveTimedPDFJob;
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
        dispatch(new RemoveTimedPDFJob());
    }
}
