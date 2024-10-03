<?php

namespace App\Models;

use App\Models\AdMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CityMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cities';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'country_id', 'name_en', 'name_ar', 'status',
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function countryCities()
    {
        return $this->belongsTo(CountryMdl::class, 'country_id');
    }
    /*
    ====================================
    ====================================
    */
    public function adCity()
    {
        return $this->hasMany(AdMdl::class, 'city_id');
    }
    /*
    ====================================
    ====================================
    */
}