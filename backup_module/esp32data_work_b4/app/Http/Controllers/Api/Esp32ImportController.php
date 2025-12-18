<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Esp32data\Models\Esp32;
use Modules\Esp32data\Services\Esp32Service;

class Esp32ImportController extends Controller
{
    public function __construct(
        private readonly Esp32Service $esp32Service,
    ) {
    }

    /**
     * Import data from external URL
     */
    public function importFromUrl(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'url' => 'required|url',
                'format' => 'sometimes|in:json,csv',
                'mapping' => 'sometimes|array',
                'mapping.sensor' => 'sometimes|string',
                'mapping.location' => 'sometimes|string',
                'mapping.value1' => 'sometimes|string',
                'mapping.value2' => 'sometimes|string',
                'mapping.value3' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $url = $request->input('url');
            $format = $request->input('format', 'json');
            $mapping = $request->input('mapping', []);

            // Fetch data from external URL
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch data from URL',
                    'error' => 'HTTP ' . $response->status(),
                ], 400);
            }

            $externalData = $response->body();

            // Process data based on format
            $processedData = $this->processExternalData($externalData, $format, $mapping);

            if (empty($processedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid data found in the response',
                ], 400);
            }

            // Import data to database
            $importedCount = $this->importDataToEsp32($processedData);

            Log::info('ESP32 data imported from external URL', [
                'url' => $url,
                'format' => $format,
                'imported_count' => $importedCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} records",
                'imported_count' => $importedCount,
            ]);

        } catch (\Exception $e) {
            Log::error('ESP32 data import failed', [
                'url' => $request->input('url'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process external data based on format
     */
    private function processExternalData(string $data, string $format, array $mapping): array
    {
        switch ($format) {
            case 'json':
                return $this->processJsonData($data, $mapping);
            case 'csv':
                return $this->processCsvData($data, $mapping);
            default:
                throw new \InvalidArgumentException("Unsupported format: {$format}");
        }
    }

    /**
     * Process JSON data
     */
    private function processJsonData(string $jsonData, array $mapping): array
    {
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        // If data is not an array, wrap it
        if (!is_array($data)) {
            $data = [$data];
        }

        // If it's an associative array with numeric keys, extract the values
        if (isset($data[0]) && is_array($data[0])) {
            $processedData = [];
            foreach ($data as $item) {
                $processedData[] = $this->mapDataFields($item, $mapping);
            }
            return $processedData;
        }

        // Single object case
        return [$this->mapDataFields($data, $mapping)];
    }

    /**
     * Process CSV data
     */
    private function processCsvData(string $csvData, array $mapping): array
    {
        $lines = explode("\n", trim($csvData));
        $processedData = [];

        // Get headers from first line
        $headers = str_getcsv($lines[0]);

        for ($i = 1; $i < count($lines); $i++) {
            $values = str_getcsv($lines[$i]);
            
            if (count($values) !== count($headers)) {
                continue; // Skip malformed rows
            }

            $row = array_combine($headers, $values);
            $processedData[] = $this->mapDataFields($row, $mapping);
        }

        return $processedData;
    }

    /**
     * Map external data fields to ESP32 fields
     */
    private function mapDataFields(array $data, array $mapping): array
    {
        $defaultMapping = [
            'sensor' => 'sensor',
            'location' => 'location',
            'value1' => 'value1',
            'value2' => 'value2',
            'value3' => 'value3',
        ];

        $mapping = array_merge($defaultMapping, $mapping);

        $mappedData = [];
        foreach ($mapping as $esp32Field => $externalField) {
            if (isset($data[$externalField])) {
                $mappedData[$esp32Field] = (string) $data[$externalField];
            }
        }

        return $mappedData;
    }

    /**
     * Import processed data to ESP32 table
     */
    private function importDataToEsp32(array $data): int
    {
        $importedCount = 0;

        foreach ($data as $item) {
            try {
                // Validate required fields
                if (empty($item['sensor']) && empty($item['location'])) {
                    continue; // Skip records without essential data
                }

                // Create new ESP32 record
                $esp32 = new Esp32();
                $esp32->fill([
                    'sensor' => $item['sensor'] ?? null,
                    'location' => $item['location'] ?? null,
                    'value1' => $item['value1'] ?? null,
                    'value2' => $item['value2'] ?? null,
                    'value3' => $item['value3'] ?? null,
                ]);
                $esp32->save();

                $importedCount++;

            } catch (\Exception $e) {
                Log::warning('Failed to import ESP32 record', [
                    'data' => $item,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $importedCount;
    }

    /**
     * Get import statistics
     */
    public function getImportStats(): JsonResponse
    {
        $stats = [
            'total_records' => Esp32::count(),
            'recent_imports' => Esp32::where('created_at', '>=', now()->subDay())->count(),
            'by_sensor' => Esp32::selectRaw('sensor, COUNT(*) as count')
                ->groupBy('sensor')
                ->get()
                ->pluck('count', 'sensor'),
            'by_location' => Esp32::selectRaw('location, COUNT(*) as count')
                ->groupBy('location')
                ->get()
                ->pluck('count', 'location'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}