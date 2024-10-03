<?php

namespace App\Models;

use App\Models\AdMdl;
use App\Models\CountryMdl;
use App\Models\CategoryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubcategoryMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'subcategories';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'category_id', 'name_en', 'name_ar', 'status'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function categorySubcategory()
    {
        return $this->belongsTo(CategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function countrySubcategory()
    {
        return $this->belongsToMany(CountryMdl::class, 'country_subcategory', 'subcategory_id', 'country_id');
    }
    /*
    ====================================
    ====================================
    */
    public function adSubcategory()
    {
        return $this->hasMany(AdMdl::class, 'subcategory_id');
    }
    /*
    ====================================
    ====================================
    */
}
