<?php

namespace Database\Seeders;

use App\Models\CategoryMdl;
use App\Models\CategoryUpdatesMdl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = CategoryMdl::get();
        foreach ($categories as $category) {
            CategoryUpdatesMdl::create([
                'category_id' => $category->id,
                'action_en'    => 'Submitted To System',
                'action_ar'    => 'تسجيل بالنظام',
            ]);
        }
    }
}