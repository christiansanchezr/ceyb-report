<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Color extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'colorName',
        'color'
    ];

    protected $table = 'color';
    protected $primaryKey = 'id_color';

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_color');
    }
}
