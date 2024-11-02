<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GovImport;

class GovSeeder extends Seeder
{
    public function run()
    {
        Excel::import(new GovImport, 'gov.xlsx');
    }
}