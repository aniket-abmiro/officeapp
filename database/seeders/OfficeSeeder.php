<?php

namespace Database\Seeders;

use App\Models\Office;
use Database\Factories\OfficeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Office::factory(10)->create();
    }
}
