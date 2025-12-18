<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DeviceManager\Models\Device;
use Modules\DeviceManager\Services\DeviceService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Modules\DeviceManager\Http\Requests\DeviceRequest;
use App\Enums\ActionType;

class DeviceManagerController extends Controller
{
    public function __construct(
        private readonly DeviceService $deviceService,
    ) {
    }

    public function index()
    {
        $this->authorize('viewAny', Device::class);

        $this->setBreadcrumbTitle(__('Devices'));

        return $this->renderViewWithBreadcrumbs('devicemanager::index');
    }

    public function create()
    {
        $this->authorize('create', Device::class);

        $this->setBreadcrumbTitle(__('Create Device'))
            ->addBreadcrumbItem(__('Devices'), route('admin.devices.index'));

        return $this->renderViewWithBreadcrumbs('devicemanager::create', [
            'statuses' => Device::statuses(),
            'users' => User::pluck('first_name', 'id')->toArray(),
        ]);
    }

    public function store(DeviceRequest $request)
    {
        $this->authorize('create', Device::class);

        try {
            $this->deviceService->createDevice($request->validated());
            $this->storeActionLog(ActionType::CREATED, ['device' => $request->validated()]);
            return redirect()->route('admin.devices.index')->with('success', __('Device created successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Failed to create device.'));
        }
    }

    public function edit(int $id)
    {
        $device = $this->deviceService->getDeviceById((int) $id);
        $this->authorize('update', $device);

        $this->setBreadcrumbTitle(__('Edit Device'))
            ->addBreadcrumbItem(__('Devices'), route('admin.devices.index'));

        return $this->renderViewWithBreadcrumbs('devicemanager::edit', [
            'device' => $device,
            'statuses' => Device::statuses(),
            'users' => User::pluck('first_name', 'id')->toArray(),
        ]);
    }

    public function show(int $id)
    {
        $device = $this->deviceService->getDeviceById((int) $id);
        $this->authorize('view', $device);

        $this->setBreadcrumbTitle(__('View Device'))
            ->addBreadcrumbItem(__('Devices'), route('admin.devices.index'));

        return $this->renderViewWithBreadcrumbs('devicemanager::show', [
            'device' => $device,
            'statuses' => Device::statuses(),
            'users' => User::pluck('first_name', 'id')->toArray(),
        ]);
    }

    public function update(DeviceRequest $request, int $id): RedirectResponse
    {
        $device = $this->deviceService->getDeviceById((int) $id);
        $this->authorize('update', $device);

        try {
            $this->deviceService->updateDevice($device, $request->validated());

            return redirect()->route('admin.devices.index')->with('success', __('Device updated successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Failed to update device.'));
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $device = $this->deviceService->getDeviceById((int) $id);
        $this->authorize('delete', $device);

        try {
            $this->deviceService->deleteDevice($device);
            $this->storeActionLog(ActionType::DELETED, ['device' => $device->toArray()]);
            return redirect()->route('admin.devices.index')->with('success', __('Device deleted successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Failed to delete device.'));
        }
    }
}

