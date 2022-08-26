<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dproduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_market',
        'id_color',
        'group_code',
        'product_no',
        'product_name',
        'blf_code',
        'blf_code_old',
        'combined_code',
        'item_number',
        'for_sorting',
        'product_code',
        'serepos_product_code',
        'smaregi_product_code',
        'product_name_jp',
        'selling_price',
        'cost_price',
        'product_code_special',
        'product_name_special',
        'years',
        'values'
    ];

    public function market() {
        return $this->belongsTo(Market::class, 'id', 'id_market');
    }
}
