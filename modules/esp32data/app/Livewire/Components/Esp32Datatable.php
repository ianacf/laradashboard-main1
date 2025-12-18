<?php

declare(strict_types=1);

namespace Modules\Esp32data\Livewire\Components;

use App\Livewire\Datatable\Datatable;
use Modules\Esp32data\Models\Esp32;

class Esp32Datatable extends Datatable
{
    public string $sensor = '';
    public string $location = '';

    public string $model = Esp32::class;

    public array $queryString = [
        ...parent::QUERY_STRING_DEFAULTS,
        'sensor' => [],
        'location' => [],
    ];

    protected function getSearchbarPlaceholder(): string
    {
        return __('Search by title or description...');
    }

    protected function getHeaders(): array
    {
        return [
            [
                'id' => 'sensor',
                'title' => __('Sensor'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'sensor',
            ],
            [
                'id' => 'location',
                'title' => __('Location'),
                'width' => '150px',
                'sortable' => true,
                'sortBy' => 'location',
            ],
            [
                'id' => 'value1',
                'title' => __('Value1'),
                'width' => '150px',
                'sortable' => true,
                'sortBy' => 'value1',
            ],
            [
                'id' => 'value2',
                'title' => __('Value2'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'value2',
            ],
            [
                'id' => 'value3',
                'title' => __('Value3'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'value3',
            ],			
            [
                'id' => 'created_at',
                'title' => __('Created At'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'created_at',
            ],
        ];
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
