<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;

class FirebaseController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    // ✅ CREATE (Insert data)
    public function store()
    {
        try {
            $newPost = $this->database
                ->getReference('users')
                ->push([
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'created_at' => now()->toDateTimeString()
                ]);

            return response()->json([
                'message' => 'Data saved successfully!',
                'id' => $newPost->getKey()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ READ (Get all users)
    public function index()
    {
        try {
            $data = $this->database
                ->getReference('users')
                ->getValue();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ UPDATE
    public function update($id)
    {
        try {
            $this->database
                ->getReference('users/' . $id)
                ->update([
                    'name' => 'Updated Name',
                    'updated_at' => now()->toDateTimeString()
                ]);

            return response()->json([
                'message' => 'Data updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ DELETE
    public function destroy($id)
    {
        try {
            $this->database
                ->getReference('users/' . $id)
                ->remove();

            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPatientLocation(string $patientId = 'default'): JsonResponse
    {
        try {
            $location = $this->database
                ->getReference('patients/' . $patientId . '/location')
                ->getValue();

            if (!$location) {
                return response()->json([
                    'message' => 'No live location available yet.',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'data' => $location,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePatientLocation(Request $request, string $patientId = 'default'): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
            'battery' => ['nullable', 'numeric', 'between:0,100'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $payload = [
                'latitude' => (float) $validated['latitude'],
                'longitude' => (float) $validated['longitude'],
                'accuracy' => isset($validated['accuracy']) ? (float) $validated['accuracy'] : null,
                'battery' => isset($validated['battery']) ? (float) $validated['battery'] : null,
                'source' => $validated['source'] ?? 'device',
                'updated_at' => now()->toIso8601String(),
            ];

            $this->database
                ->getReference('patients/' . $patientId . '/location')
                ->set($payload);

            return response()->json([
                'message' => 'Live location updated successfully.',
                'data' => $payload,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
