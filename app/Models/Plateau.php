<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plateau extends Model
{
    protected $fillable = [
        'width', 'height',
    ];

    protected $casts = [
        'width' => 'int',
        'height' => 'int',
    ];

    public function rovers()
    {
        return $this->hasMany(Rover::class);
    }
}
