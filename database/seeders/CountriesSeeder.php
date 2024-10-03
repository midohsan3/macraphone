<?php

namespace Database\Seeders;

use App\Models\CountryMdl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CountryMdl::create([
            'name_en' => 'Algeria',
            'name_ar' => 'الجزائر',
            'country_code' => 'DZ',
            'phone_code' => '+213',
            'currency_code' => 'DZD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Bahrain',
            'name_ar' => 'البحرين',
            'country_code' => 'BH',
            'phone_code' => '+973',
            'currency_code' => 'BHD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Egypt',
            'name_ar' => 'مصر',
            'country_code' => 'EG',
            'phone_code' => '+20',
            'currency_code' => 'EGP',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'France',
            'name_ar' => 'فرنسا',
            'country_code' => 'FR',
            'phone_code' => '+33',
            'currency_code' => 'EUR',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Germany',
            'name_ar' => 'المانيا',
            'country_code' => 'DE',
            'phone_code' => '+49',
            'currency_code' => 'EUR',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Iraq',
            'name_ar' => 'العراق',
            'country_code' => 'IQ',
            'phone_code' => '+964',
            'currency_code' => 'IQD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Jordan',
            'name_ar' => 'الأردن',
            'country_code' => 'JO',
            'phone_code' => '+962',
            'currency_code' => 'JOD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Kuwait',
            'name_ar' => 'الكويت',
            'country_code' => 'KW',
            'phone_code' => '+965',
            'currency_code' => 'KWD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Lebanon',
            'name_ar' => 'لبنان',
            'country_code' => 'LB',
            'phone_code' => '+961',
            'currency_code' => 'LBP',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Libya',
            'name_ar' => 'لبيا',
            'country_code' => 'LY',
            'phone_code' => '+218',
            'currency_code' => 'LYD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Morocco',
            'name_ar' => 'المغرب',
            'country_code' => 'MA',
            'phone_code' => '+212',
            'currency_code' => 'MAD',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Oman',
            'name_ar' => 'عمان',
            'country_code' => 'OM',
            'phone_code' => '+968',
            'currency_code' => 'OMR',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Philippines',
            'name_ar' => 'الفلبين',
            'country_code' => 'PH',
            'phone_code' => '+63',
            'currency_code' => 'PHP',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Qatar',
            'name_ar' => 'قطر',
            'country_code' => 'QA',
            'phone_code' => '+974',
            'currency_code' => 'QAR',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Sudan',
            'name_ar' => 'السودان',
            'country_code' => 'SD',
            'phone_code' => '+249',
            'currency_code' => 'SDG',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Syria',
            'name_ar' => 'سوريا',
            'country_code' => 'SY',
            'phone_code' => '+963',
            'currency_code' => 'SYP',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Tunisia',
            'name_ar' => 'تونس',
            'country_code' => 'TN',
            'phone_code' => '+216',
            'currency_code' => 'TND',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Turkey',
            'name_ar' => 'تركيا',
            'country_code' => 'TR',
            'phone_code' => '+90',
            'currency_code' => 'TRY',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Ukraine',
            'name_ar' => 'أوكرانيا',
            'country_code' => 'UA',
            'phone_code' => '+380',
            'currency_code' => 'UAH',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'United Arab Emirates',
            'name_ar' => 'الإمارات العربية المتحدة',
            'country_code' => 'AE',
            'phone_code' => '+971',
            'currency_code' => 'AED',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Yemen',
            'name_ar' => 'اليمن',
            'country_code' => 'YE',
            'phone_code' => '+967',
            'currency_code' => 'YER',
        ]);
        //======================
        CountryMdl::create([
            'name_en' => 'Saudi Arabia',
            'name_ar' => 'المملكة العربية السعودية',
            'country_code' => 'SA',
            'phone_code' => '+966',
            'currency_code' => 'SAR',
        ]);
        //======================
    }
}