<?php

namespace App\Models;

use App\Models\User;
use App\Models\CountryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CountryActivitiesMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'country_activity';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'country_id', 'user_id', 'activity_en', 'activity_ar'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function Country_has_activity()
    {
        return $this->belongsTo(CountryMdl::class, 'country_id');
    }
    /*
    ====================================
    ====================================
    */
    public function user_country_action()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
}