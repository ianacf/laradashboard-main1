<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DeviceManager\Database\Factories\DeviceFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Modules\DeviceManager\Observers\DeviceObserver;

#[ObservedBy([DeviceObserver::class])]
class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'Name',
        'device_id',
        "api_key",
        'description',
        'status',
        'assigned_to',
        'created_by',
    ];

    protected static function newFactory(): DeviceFactory
    {
        return DeviceFactory::new();
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function statuses(): array
    {
        return [
            'pending' => __('Pending'),
            'enable' => __('Enable'),
            'disable' => __('Disable'),
        ];
    }
}

