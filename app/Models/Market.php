<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function dproducts() {
        return $this->hasMany(Dproduct::class, 'id_market', 'id');
    }
}
