<?php

namespace App\Models;

use App\Models\User;
use App\Models\CategoryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserActivityMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'user_activity';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'user_id', 'action_en', 'action_ar'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function userAction()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
    ====================================
    ********************************************
    ====================================
    */
    public function categoryWithActivity()
    {
        return $this->belongsTo(CategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ********************************************
    ====================================
    */
}