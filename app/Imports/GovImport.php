<?php
namespace App\Imports;

use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\Isolation;
use App\Models\Province;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\Village;
use Maatwebsite\Excel\Concerns\ToModel;

class GovImport implements ToModel
{
    public function model(array $row)
    {
        $province = Governorate::firstOrCreate(['name' => $row[0]]); // governorate

        $district = Directorate::firstOrCreate([
            'name' => $row[1], 
            'governorate_id' => $province->id,
        ]);

        $subDistrict = Isolation::firstOrCreate([
            'name' => $row[2], 
            'directorate_id' => $district->id,
        ]);

        Village::create([
            'name' => $row[3], 
            'isolation_id' => $subDistrict->id,
        ]);
    }
}