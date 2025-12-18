<livewire:devicemanager::components.status-change-button
    :device="$device"
    :status="$device->status"
    :statuses="$statuses"
    :key="'status-change-' . $device->id"
/>