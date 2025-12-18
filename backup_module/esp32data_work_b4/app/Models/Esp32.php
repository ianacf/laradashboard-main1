<?php

namespace Modules\Esp32data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Esp32data\Database\Factories\Esp32Factory;

class Esp32 extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sensor',
        'location',
        'value1',
        'value2',
        'value3',
    ];

    protected static function newFactory(): Esp32Factory
    {
        return Esp32Factory::new();
    }
}
