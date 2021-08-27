<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rover extends Model
{
    const Ready = 'READY';
    const Interrupted = 'INTERRUPTED';

    protected $fillable = [
        'x', 'y', 'direction', 'plateau_id', 'state', 'queue'
    ];

    protected $attributes = [
        'state' => self::Ready
    ];

    protected $casts = [
        'x' => 'int',
        'y' => 'int',
        'plateau_id' => 'int',
    ];

    public function plateau()
    {
        return $this->belongsTo(Plateau::class);
    }
}
