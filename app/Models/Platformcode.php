<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Platformcode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'platformName',
        'platformCode',
    ];

    protected $table = 'platformcode';
    protected $primaryKey = 'id_platformCode';

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
