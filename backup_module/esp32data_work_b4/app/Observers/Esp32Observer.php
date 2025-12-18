<?php

declare(strict_types=1);

namespace Modules\Esp32data\Observers;

use App\Concerns\HasActionLogTrait;
use App\Enums\ActionType;
use Modules\Esp32data\Models\Esp32;

class Esp32Observer
{
    use HasActionLogTrait;

    /**
     * Handle the Esp32 "created" event.
     */
    public function created(Esp32 $esp32): void
    {
        $this->storeActionLog(ActionType::CREATED, ['esp32' => $esp32]);
    }

    /**
     * Handle the Esp32 "updated" event.
     */
    public function updated(Esp32 $esp32): void
    {
        $this->storeActionLog(ActionType::UPDATED, ['esp32' => $esp32]);
    }

    /**
     * Handle the Esp32 "deleted" event.
     */
    public function deleted(Esp32 $esp32): void
    {
        $this->storeActionLog(ActionType::DELETED, ['esp32' => $esp32]);
    }
}
