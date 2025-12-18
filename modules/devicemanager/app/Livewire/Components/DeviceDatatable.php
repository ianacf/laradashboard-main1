<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Livewire\Components;

use App\Livewire\Datatable\Datatable;
use Modules\DeviceManager\Models\Device;

class DeviceDatatable extends Datatable
{
    public string $status = '';
    public string $assigned_to = '';

    public string $model = Device::class;

    public array $queryString = [
        ...parent::QUERY_STRING_DEFAULTS,
        'status' => [],
        'assigned_to' => [],
    ];

    protected function getSearchbarPlaceholder(): string
    {
        return __('Search by title or description...');
    }

    protected function getHeaders(): array
    {
        return [
            [
                'id' => 'device_name',
                'title' => __('Device Name'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'device_name',
            ],
            [
                'id' => 'status',
                'title' => __('Status'),
                'width' => '150px',
                'sortable' => true,
                'sortBy' => 'status',
            ],
            [
                'id' => 'description',
                'title' => __('Description'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'description',
            ],
            [
                'id' => 'api_key',
                'title' => __('API Key'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'api_key',
            ],      
            [
                'id' => 'assigned_to',
                'title' => __('Assigned To'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'assigned_to',
            ],                 
            [
                'id' => 'created_at',
                'title' => __('Created At'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'created_at',
            ],
            [
                'id' => 'actions',
                'title' => __('Actions'),
                'sortable' => false,
                'is_action' => true,
            ],
        ];
    }

    public function getFilters(): array
    {
        return [
            [
                'id' => 'status',
                'label' => __('Status'),
                'filterLabel' => __('Status'),
                'icon' => 'lucide:filter',
                'allLabel' => __('All Statuses'),
                'options' => Device::statuses(),
                'selected' => $this->status,
            ],
            [
                'id' => 'assigned_to',
                'label' => __('Assigned To'),
                'filterLabel' => __('Assigned To'),
                'icon' => 'lucide:user',
                'allLabel' => __('All Users'),
                'options' => $this->getUsersOptions(),
                'selected' => $this->assigned_to,
            ],
        ];
    }

    protected function buildQuery(): \Spatie\QueryBuilder\QueryBuilder
    {
        $query = parent::buildQuery()
            ->with('assigned')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('device_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->assigned_to, function ($query) {
                $query->where('assigned_to', $this->assigned_to);
            });

        return $this->sortQuery($query);
    }

    public function getUsersOptions(): array
    {
        return \App\Models\User::pluck('first_name', 'id')->toArray();
    }

    public function renderAssignedToColumn(Device $device): string
    {
        return $device->assigned ? $device->assigned->full_name : __('Unassigned');
    }

    public function renderTitleColumn(Device $device): string
    {
        return "<a href='" . route('admin.devices.edit', $device->id) . "' class='flex items-center hover:text-primary'>
                <div class='flex flex-col'>
                    <span>" . $device->title . "</span>
                    <span class='text-xs text-gray-500 dark:text-gray-400'>" . $device->username . "</span>
                </div>
            </a>";
    }

    public function renderStatusColumn(Device $device): string
    {
        return view('devicemanager::partials.status-changer', [
            'device' => $device,
            'status' => $device->status,
            'statuses' => Device::statuses(),
        ])->render();
    }

	public function renderCreatedAtColumn($item): string
	{
		if (!$item->created_at) {
			return '';
		}

		// Display full date format: Day, Month DD, YYYY at HH:MM AM/PM
		$fullDate = $item->created_at->format('l, F j, Y \a\t g:i A');
		
		return '<span class="text-sm">' . e($fullDate) . '</span>';
	}
}
