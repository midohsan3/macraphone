<?php

namespace App\Models;

use App\Models\CategoryMdl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategorySEOMdl extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'category_seo';

    protected $primaryKey = 'id';

    protected $fillable   = [
        'category_id', 'meta_name', 'meta_en', 'meta_ar', 'status'
    ];

    protected $date = ['deleted_at'];

    /*
    =========================
    RELATIONS
    =========================
    */
    public function categoryWSEO()
    {
        return $this->hasMany(CategoryMdl::class, 'category_id');
    }
    /*
    ====================================
    ====================================
    */
}