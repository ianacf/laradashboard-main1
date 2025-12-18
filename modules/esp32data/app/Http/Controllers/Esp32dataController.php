<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Esp32data\Models\Esp32;
use Modules\Esp32data\Services\Esp32Service;
use Modules\Esp32data\Http\Requests\Esp32Request;
use App\Enums\ActionType;

class Esp32dataController extends Controller
{
    public function __construct(
        private readonly Esp32Service $esp32Service,
    ) {
    }

    public function index()
    {
        $this->authorize('viewAny', Esp32::class);

        $this->setBreadcrumbTitle(__('Esp32s'));

        $records = \Modules\Esp32data\Models\Esp32::query()
            ->select(['created_at', 'value1', 'value2', 'value3'])
            ->orderByDesc('created_at')
            ->limit(60)
            ->get()
            ->reverse()
            ->values();

        $esp32_stats = [
            'value1' => $records->map(fn ($r) => ['x' => $r->created_at?->toISOString(), 'y' => $r->value1 !== null ? (float) $r->value1 : null])->all(),
            'value2' => $records->map(fn ($r) => ['x' => $r->created_at?->toISOString(), 'y' => $r->value2 !== null ? (float) $r->value2 : null])->all(),
            'value3' => $records->map(fn ($r) => ['x' => $r->created_at?->toISOString(), 'y' => $r->value3 !== null ? (float) $r->value3 : null])->all(),
        ];

        //return $this->renderViewWithBreadcrumbs('esp32data::index');

        return $this->renderViewWithBreadcrumbs('esp32data::index', compact('esp32_stats'));
    }

    public function create()
    {
        $this->authorize('create', Esp32::class);

        $this->setBreadcrumbTitle(__('Create Esp32'))
            ->addBreadcrumbItem(__('Esp32s'), route('admin.esp32s.index'));

        return $this->renderViewWithBreadcrumbs('esp32data::create', [

        ]);
    }

    public function store(Esp32Request $request)
    {
        $this->authorize('create', Esp32::class);

        try {
            $this->esp32Service->createEsp32($request->validated());
            $this->storeActionLog(ActionType::CREATED, ['esp32' => $request->validated()]);
            return redirect()->route('admin.esp32s.index')->with('success', __('Esp32 created successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Failed to create esp32.'));
        }
    }

    public function show(int $id)
    {
        $esp32 = $this->esp32Service->getEsp32ById((int) $id);
        $this->authorize('view', $eso32);

        $this->setBreadcrumbTitle(__('View Esp32'))
            ->addBreadcrumbItem(__('Esp32s'), route('admin.esp32s.index'));

        return $this->renderViewWithBreadcrumbs('esp32data::show', [
            'esp32' => $esp32,
        ]);
    }
}
