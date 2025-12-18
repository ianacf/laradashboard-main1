<?php

declare(strict_types=1);

namespace Modules\Esp32data\Livewire\Components;

use Livewire\Component;
use Modules\Esp32data\Models\Esp32;

class StatusChangeButton extends Component
{
    public Esp32 $esp32;
    public $status;
    public $statuses;

    public function mount(Esp32 $esp32)
    {
        $this->esp32 = $esp32;
        $this->status = $esp32->status;
        $this->statuses = Esp32::statuses();
    }

    public function changeStatusTo($newStatus)
    {
        $this->status = $newStatus;
        $this->esp32->update(['status' => $newStatus]);
        $this->esp32->refresh();
        $this->dispatch('esp32-status-updated', $this->esp32->id);
    }

    public function render()
    {
        return view('esp32data::livewire.components.status-change-button');
    }
}
