<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categoryName'
    ];

    protected $table = 'category';
    protected $primaryKey = 'id_category';

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_category');
    }
}
