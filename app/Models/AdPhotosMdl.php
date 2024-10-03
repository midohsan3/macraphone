<?php

namespace App\Models;

use App\Models\AdMdl;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdPhotosMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'ads_photos';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'ad_id', 'photo'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function adPhoto()
    {
        return $this->belongsTo(AdMdl::class, 'ad_id');
    }
    /*
    ====================================
    ====================================
    */
}