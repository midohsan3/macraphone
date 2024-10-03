<?php

namespace Database\Seeders;

use App\Models\CategoryMdl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryMdl::create([
            'name_en' => 'Real-State',
            'name_ar' => 'عقارات',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Vehicles',
            'name_ar' => 'مركبات',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Mobiles & Accessories',
            'name_ar' => ' موبايل و اكسسوارات',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Jobs',
            'name_ar' => 'وظائف',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Electronics & Home Appliances',
            'name_ar' => 'إلكترونيات و أجهزة منزلية',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Home & Garden',
            'name_ar' => 'المنزل و الحديقة',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Fashion & Beauty',
            'name_ar' => 'الموضة والجمال',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Pets',
            'name_ar' => 'حيوانات أليفة',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Kids & Babies',
            'name_ar' => 'مستلزمات الأطفال',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Business & Industrial',
            'name_ar' => 'صناعة و تجارة',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Services',
            'name_ar' => 'خدمات',
            'status' => 1,
        ]);
        //======================
        CategoryMdl::create([
            'name_en' => 'Sports Goods',
            'name_ar' => 'معدات  رياضية',
            'status' => 1,
        ]);
        //======================
    }
}