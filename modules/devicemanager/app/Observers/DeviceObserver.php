<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Observers;

use App\Concerns\HasActionLogTrait;
use App\Enums\ActionType;
use Modules\DeviceManager\Models\Device;

class DeviceObserver
{
    use HasActionLogTrait;

    /**
     * Handle the Device "created" event.
     */
    public function created(Device $device): void
    {
        $this->storeActionLog(ActionType::CREATED, ['device' => $device]);
    }

    /**
     * Handle the Device "updated" event.
     */
    public function updated(Device $device): void
    {
        $this->storeActionLog(ActionType::UPDATED, ['device' => $device]);
    }

    /**
     * Handle the Device "deleted" event.
     */
    public function deleted(Device $device): void
    {
        $this->storeActionLog(ActionType::DELETED, ['device' => $device]);
    }
}
