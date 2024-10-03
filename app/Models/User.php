<?php

namespace App\Models;

use App\Models\AdMdl;
use App\Models\UserActivityMdl;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CategoryUpdatesMdl;
use App\Models\CountryActivitiesMdl;
use Database\Seeders\CountryActivity;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'phone', 'country', 'status', 'role_name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    /*
    ====================================
    ====================================
    */
    public function countryUpdater()
    {
        return $this->hasMany(CountryActivitiesMdl::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
    public function actionsUser()
    {
        return $this->hasMany(UserActivityMdl::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
    public function categoryUpdater()
    {
        return $this->hasMany(CategoryUpdatesMdl::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
    public function ad_User()
    {
        return $this->hasMany(AdMdl::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
}