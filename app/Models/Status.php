<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nameStatus'
    ];

    protected $table = 'status';
    protected $primaryKey = 'id_status';

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_status');
    }
}
