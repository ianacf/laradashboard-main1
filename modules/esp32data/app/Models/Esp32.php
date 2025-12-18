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
	
    protected $casts = [
        'value1' => 'decimal:2',
        'value2' => 'decimal:2', 
        'value3' => 'decimal:2',
        'created_at' => 'datetime',
    ];
 
    public static function sensorTypes(): array
    {
        return [
            'temperature' => __('Temperature'),
            'humidity' => __('Humidity'),
            'pressure' => __('Pressure'),
            'light' => __('Light'),
            'motion' => __('Motion'),
        ];
    }	

    protected static function newFactory(): Esp32Factory
    {
        return Esp32Factory::new();
    }
}
