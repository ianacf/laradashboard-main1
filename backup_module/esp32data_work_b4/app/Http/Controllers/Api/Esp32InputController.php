<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Esp32data\Models\Esp32;
use Modules\Esp32data\Services\Esp32Service;

class Esp32InputController extends Controller
{
    public function __construct(
        private readonly Esp32Service $esp32Service,
    ) {
    }

    /**
     * Accept ESP32 data via URL parameters (GET request)
     * Example: /api/v1/esp32datas/input?sensor=temp&location=room1&value1=25.5&api_key=IanRan1qaz@WSX
     */
    public function inputData(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sensor' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'value1' => 'sometimes|string|max:255',
                'value2' => 'sometimes|string|max:255',
                'value3' => 'sometimes|string|max:255',
                'api_key' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Validate API key
            if ($request->input('api_key') !== 'IanRan1qaz@WSX') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API key',
                ], 401);
            }

            $data = $request->only(['sensor', 'location', 'value1', 'value2', 'value3']);
            $esp32 = $this->esp32Service->createEsp32($data);

            Log::info('ESP32 data received', [
                'sensor' => $data['sensor'],
                'location' => $data['location'],
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data received successfully',
                'id' => $esp32->id,
                'timestamp' => $esp32->created_at->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('ESP32 input failed', [
                'data' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Accept ESP32 data via POST request
     */
    public function postData(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sensor' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'value1' => 'sometimes|string|max:255',
                'value2' => 'sometimes|string|max:255',
                'value3' => 'sometimes|string|max:255',
                'api_key' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Validate API key
            if ($request->input('api_key') !== 'IanRan1qaz@WSX') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API key',
                ], 401);
            }

            $data = $request->only(['sensor', 'location', 'value1', 'value2', 'value3']);
            $esp32 = $this->esp32Service->createEsp32($data);

            Log::info('ESP32 data received via POST', [
                'sensor' => $data['sensor'],
                'location' => $data['location'],
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data received successfully',
                'id' => $esp32->id,
                'timestamp' => $esp32->created_at->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('ESP32 POST input failed', [
                'data' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Accept multiple ESP32 data records via POST
     */
    public function inputBulk(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array|min:1|max:100', // Limit to 100 records per request
                'data.*.sensor' => 'required|string|max:255',
                'data.*.location' => 'required|string|max:255',
                'data.*.value1' => 'sometimes|string|max:255',
                'data.*.value2' => 'sometimes|string|max:255',
                'data.*.value3' => 'sometimes|string|max:255',
                'api_key' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Validate API key
            if ($request->input('api_key') !== 'IanRan1qaz@WSX') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API key',
                ], 401);
            }

            $records = $request->input('data');
            $importedCount = $this->esp32Service->createMultipleEsp32($records);

            Log::info('ESP32 bulk data received', [
                'count' => $importedCount,
                'total_sent' => count($records),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$importedCount} records",
                'imported_count' => $importedCount,
                'total_sent' => count($records),
            ]);

        } catch (\Exception $e) {
            Log::error('ESP32 bulk input failed', [
                'data_count' => count($request->input('data', [])),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process bulk data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Health check endpoint for ESP32 devices
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'service' => 'ESP32 Data Input',
        ]);
    }
}
