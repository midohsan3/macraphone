<?php

namespace Database\Seeders;

use App\Models\CountryActivitiesMdl;
use App\Models\CountryMdl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryActivity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = CountryMdl::get();
        foreach ($countries as $country) {
            CountryActivitiesMdl::create([
                'country_id' => $country->id,
                'activity_en' => 'Submitted To System',
                'activity_ar' => 'تسجيل بالنظام',
            ]);
        }
    }
}