<?php

namespace App\Models;

use App\Models\User;
use App\Models\CityMdl;
use App\Models\CountryMdl;
use App\Models\AdPhotosMdl;
use App\Models\CategoryMdl;
use App\Models\SubcategoryMdl;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'ads';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'user_id', 'affliet', 'afflet_link', 'category_id', 'subcategory_id', 'country_id', 'city_id', 'ad_type', 'featured', 'status', 'phone', 'whatsApp', 'mail', 'start_date', 'end_date',  'title', 'description', 'price', 'currency', 'photo', 'views'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function userAd()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
    public function categoryAd()
    {
        return $this->belongsTo(CategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function subcategoryAd()
    {
        return $this->belongsTo(SubcategoryMdl::class, 'subcategory_id');
    }
    /*
    ====================================
    ====================================
    */
    public function countryAd()
    {
        return $this->belongsTo(CountryMdl::class, 'country_id');
    }
    /*
    ====================================
    ====================================
    */
    public function cityAd()
    {
        return $this->belongsTo(CityMdl::class, 'city_id');
    }
    /*
    ====================================
    ====================================
    */
    public function photoAd()
    {
        return $this->hasMany(AdPhotosMdl::class, 'ad_id');
    }
    /*
    ====================================
    ====================================
    */
}