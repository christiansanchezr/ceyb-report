<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'colorGroupName'
    ];

    protected $table = 'colorgroup';
    protected $primaryKey = 'id_colorGroup';

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_colorGroup');
    }
}
