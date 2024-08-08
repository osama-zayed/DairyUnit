<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Factory;
use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FackeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'وحدة الالبان',
            'phone' => '777888999',
            'user_type' => 'institution',
            'password' => bcrypt('123123123'),
        ]);
        //collector representative association institution
        $association1 = User::create([
            'name' => 'الجمعية 1',
            'phone' => '120778899',
            'user_type' => 'association',
            'password' => bcrypt('123123123'),
        ]);
        $association2 = User::create([
            'name' => 'الجمعية 2',
            'phone' => '220778899',
            'user_type' => 'association',
            'password' => bcrypt('123123123'),
        ]);
        $collector1 = User::create([
            'name' => 'المجمع 1',
            'phone' => '130778899',
            'user_type' => 'collector',
            'password' => bcrypt('123123123'),
            'association_id' =>  $association1->id
        ]);
        $collector2 = User::create([
            'name' => 'المجمع 2',
            'phone' => '230778899',
            'user_type' => 'collector',
            'password' => bcrypt('123123123'),
            'association_id' =>  $association1->id
        ]);
        $collector3 = User::create([
            'name' => 'المجمع 3',
            'phone' => '330778899',
            'user_type' => 'collector',
            'password' => bcrypt('123123123'),
            'association_id' =>  $association2->id
        ]);

        $family = Family::create([
            'name' => "الاسره 1",
            'phone' => "777888990",
            'number_of_cows_produced' => 5,
            'number_of_cows_unproductive' => 6,
            'association_id' => $association1->id,
            'associations_branche_id' =>  $collector1->id,
        ]);
        $family = Family::create([
            'name' => "الاسره 2",
            'phone' => "777888999",
            'number_of_cows_produced' => 5,
            'number_of_cows_unproductive' => 6,
            'association_id' => $association1->id,
            'associations_branche_id' =>  $collector2->id,
        ]);
        $family = Family::create([
            'name' => "الاسره 3",
            'phone' => "777888995",
            'number_of_cows_produced' => 5,
            'number_of_cows_unproductive' => 6,
            'association_id' => $association2->id,
            'associations_branche_id' =>  $collector3->id,
        ]);


        $Factory1 = Factory::create([
            'name'=>"المصنع 1",
        ]);
        $Factory2 = Factory::create([
            'name'=>"المصنع 2",
        ]);
        $representative1 = User::create([
            'name' => 'المندوب 1',
            'phone' => '140778899',
            'user_type' => 'representative',
            'password' => bcrypt('123123123'),
            'factory_id' =>  $Factory1->id
        ]);
        $representative2 = User::create([
            'name' => 'المندوب 2',
            'phone' => '240778899',
            'user_type' => 'representative',
            'password' => bcrypt('123123123'),
            'factory_id' =>  $Factory1->id
        ]);
        $representative3 = User::create([
            'name' => 'المندوب 3',
            'phone' => '340778899',
            'user_type' => 'representative',
            'password' => bcrypt('123123123'),
            'factory_id' =>  $Factory2->id
        ]);
        $Driver = Driver::create([
            'name' => "السائق 1",
            'phone' => "777888990",
            'association_id' => $association1->id,
        ]);
        $Driver = Driver::create([
            'name' => "السائق 2",
            'phone' => "777888950",
            'association_id' => $association2->id,
        ]);
      
    }
}
