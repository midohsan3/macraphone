<?php

namespace App\Models;

use App\Models\CategorySEOMdl;
use App\Models\SubcategoryMdl;
use App\Models\CategoryUpdatesMdl;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'name_en', 'name_ar', 'status', 'logo'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function updatedCategory()
    {
        return $this->hasMany(CategoryUpdatesMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function seoCategory()
    {
        return $this->hasMany(CategorySEOMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function subcategoryCategory()
    {
        return $this->hasMany(SubcategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function countryCategory()
    {
        return $this->hasMany(CountryMdl::class, 'country_category', 'country_id', 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function adCategory()
    {
        return $this->hasMany(AdMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
}