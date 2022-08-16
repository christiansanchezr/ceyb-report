<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_color',
        'id_colorGroup',
        'id_category',
        'id_condition',
        'productNo',
        'productNameENG',
        'productNameJP',
        'price',
        'cost',
        'codeBLF',
        'id_status',
    ];
    protected $table = 'product';
    protected $primaryKey = 'id_product';


    public function platformcodes() {
        return $this->belongsTo(Platformcode::class, 'id_platformCode', 'id_product');
    }

    public function color() {
        return $this->hasOne(Color::class);
    }

    public function colorGroup() {
        return $this->hasOne(ColorGroup::class);
    }

    public function category() {
        return $this->hasOne(Category::class);
    }

    public function condition() {
        return $this->hasOne(Condition::class);
    }
}
