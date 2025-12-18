<?php

declare(strict_types=1);

namespace Modules\Esp32data\Services;

use Modules\Esp32data\Models\Esp32;

class Esp32Service
{
    /**
     * Get Esp32s with filters
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getEsp32s(array $filters = [])
    {
        $query = Esp32::applyFilters($filters);

        if (isset($filters['location']) && $filters['location']) {
            $query->where('location', $filters['location']);
        }

        return $query->paginateData();
    }

    /**
     * Create a new esp32 data.
     *
     * @param array $data
     * @return Esp32
     */
    public function createEsp32(array $data): Esp32
    {
        $esp32 = new Esp32();
        $esp32->fill($data);
        $esp32->save();

        return $esp32;
    }

    /**
     * Get esp32 date by ID.
     *
     * @param int $id
     * @return Esp32|null
     */
    public function getEsp32ById(int $id): ?Esp32
    {
        return Esp32::find($id);
    }

    /**
     * Get Esp32 data by esp32 ids.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEsp32sByIds(array $esp32Ids)
    {
        return Esp32::whereIn('id', $esp32Ids)->get();
    }

    /**
     * Create multiple ESP32 records
     *
     * @param array $records
     * @return int
     */
    public function createMultipleEsp32(array $records): int
    {
        $created = 0;
        
        foreach ($records as $data) {
            try {
                $this->createEsp32($data);
                $created++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to create ESP32 record', [
                    'data' => $data,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $created;
    }
}
