<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Esp32data\Models\Esp32;

class Esp32ChartController extends Controller
{
    /**
     * Return last 50 points for charting value1-3 against created_at.
     */
    public function latest(): JsonResponse
    {
        $records = Esp32::query()
            ->select(['id', 'created_at', 'value1', 'value2', 'value3'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        $data = [
            'labels' => $records->map(fn ($r) => $r->created_at?->toDateTimeString())->all(),
            'series' => [
                'value1' => $records->map(fn ($r) => $r->value1 !== null ? (float) $r->value1 : null)->all(),
                //'value2' => $records->map(fn ($r) => $r->value2 !== null ? (float) $r->value2 : null)->all(),
                //'value3' => $records->map(fn ($r) => $r->value3 !== null ? (float) $r->value3 : null)->all(),
            ],
        ];

        return response()->json($data);
    }
}



