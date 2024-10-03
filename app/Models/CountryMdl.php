<?php

namespace App\Models;

use App\Models\AdMdl;
use App\Models\CityMdl;
use App\Models\CategoryMdl;
use App\Models\SubcategoryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CountryMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'countries';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'name_en', 'name_ar', 'country_code', 'phone_code', 'currency_code', 'flag', 'status'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function citiesCountry()
    {
        return $this->hasMany(CityMdl::class, 'country_id');
    }
    /*
    ====================================
    ====================================
    */
    public function categoryCountry()
    {
        return $this->belongsToMany(CategoryMdl::class, 'country_category', 'country_id', 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function subcategoryCountry()
    {
        return $this->belongsToMany(SubcategoryMdl::class, 'country_subcategory', 'country_id', 'subcategory_id');
    }
    /*
    ====================================
    ====================================
    */
    public function adCountry()
    {
        return $this->hasMany(AdMdl::class, 'country_id');
    }
    /*
    ====================================
    ====================================
    */
}