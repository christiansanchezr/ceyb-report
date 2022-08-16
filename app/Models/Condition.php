<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conditionName'
    ];
    protected $table = 'condition';
    protected $primaryKey = 'id_condition';

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_condition');
    }
}
