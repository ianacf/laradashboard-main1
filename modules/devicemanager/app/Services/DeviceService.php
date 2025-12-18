<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Services;

use Modules\DeviceManager\Models\Device;

class DeviceService
{
    /**
     * Get devices with filters
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDevices(array $filters = [])
    {
        $query = Device::applyFilters($filters);

        if (isset($filters['priority']) && $filters['priority']) {
            $query->where('priority', $filters['priority']);
        }

        return $query->paginateData();
    }

    /**
     * Create a new device.
     *
     * @param array $data
     * @return Device
     */
    public function createDevice(array $data): Device
    {
        $device = new Device();
        $device->fill($data);
        $device->created_by = auth()->id();
        $device->save();

        return $device;
    }

    /**
     * Update an existing device.
     *
     * @param Device $device
     * @param array $data
     * @return Device
     */
    public function updateDevice(Device $device, array $data): Device
    {
        $device->fill($data);
        $device->save();

        return $device;
    }

    /**
     * Delete a device.
     *
     * @param Device $device
     * @return void
     */
    public function deleteDevice(Device $device): void
    {
        $device->delete();
    }

    /**
     * Get device by ID.
     *
     * @param int $id
     * @return Device|null
     */
    public function getDeviceById(int $id): ?Device
    {
        return Device::find($id);
    }

    /**
     * Get devices by device ids.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDevicesByIds(array $deviceIds)
    {
        return Device::whereIn('id', $deviceIds)->get();
    }
}

