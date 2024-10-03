<?php

namespace App\Models;

use App\Models\User;
use App\Models\CategoryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryUpdatesMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'category_updates';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'category_id', 'user_id', 'action_en', 'action_ar'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function categoryWUpdate()
    {
        return $this->belongsTo(CategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
    public function updaterCategory()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
    ====================================
    ====================================
    */
}