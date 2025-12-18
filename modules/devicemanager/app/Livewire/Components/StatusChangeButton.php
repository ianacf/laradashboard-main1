<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Livewire\Components;

use Livewire\Component;
use Modules\DeviceManager\Models\Device;

class StatusChangeButton extends Component
{
    public Device $device;
    public $status;
    public $statuses;

    public function mount(Device $device)
    {
        $this->device = $device;
        $this->status = $device->status;
        $this->statuses = Device::statuses();
    }

    public function changeStatusTo($newStatus)
    {
        $this->status = $newStatus;
        $this->device->update(['status' => $newStatus]);
        $this->device->refresh();
        $this->dispatch('device-status-updated', $this->device->id);
    }

    public function render()
    {
        return view('devicemanager::livewire.components.status-change-button');
    }
}
