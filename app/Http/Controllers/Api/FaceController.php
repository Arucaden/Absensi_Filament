<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FaceController extends Controller
{
    public function checkFaceMatch(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id_karyawan',
            'face_vector' => 'required|json',
        ]);

        // Retrieve employee data
        $karyawan = Karyawan::findOrFail($validated['id_karyawan']);
        $storedVectorsJson = $karyawan->face_vector;

        if (!$storedVectorsJson) {
            return response()->json(['message' => 'No face vector stored for this user.'], 404);
        }

        // Decode the JSON embeddings to arrays
        $inputVector = json_decode($validated['face_vector'], true);
        $storedVectors = json_decode($storedVectorsJson, true);

        // Flatten the embeddings
        $inputVector = $this->flattenArray($inputVector);
        $storedVectors = $this->flattenArray($storedVectors);

        // Log the embeddings for comparison
        Log::debug('Flattened Input Face Vector:', $inputVector);
        Log::debug('Flattened Stored Face Vector:', $storedVectors);

        // Compare embeddings in PHP
        if ($inputVector === $storedVectors) {
            Log::info('The input face vector and stored face vector are identical.');
        } else {
            Log::warning('The input face vector and stored face vector differ.');
        }

        // Re-encode embeddings to JSON strings for Python script
        $inputVectorJson = json_encode($inputVector);
        $storedVectorsJson = json_encode([$storedVectors]); // Wrap stored vector in an array

        // Run the Python script for face matching
        $process = new Process([
            'python3',
            base_path('scripts/face_matching.py'),
            $inputVectorJson,
            $storedVectorsJson
        ]);

        try {
            $process->mustRun();

            // Log any output or errors from the Python script
            Log::debug('Python Script Output:', [$process->getOutput()]);
            Log::debug('Python Script Error Output:', [$process->getErrorOutput()]);

            $output = json_decode($process->getOutput(), true);

            // If the face matches, record attendance
            if ($output['match'] ?? false) {
                Absensi::create([
                    'karyawan_id' => $karyawan->karyawan_id,
                    'tanggal' => now()->toDateString(),
                    'jam_masuk' => now(),
                    'status' => 'hadir',
                ]);

                return response()->json(['message' => 'Attendance recorded successfully!']);
            } else {
                return response()->json(['message' => 'Face does not match.'], 401);
            }
        } catch (ProcessFailedException $exception) {
            // Log the exception details
            Log::error('Error running face match process:', [
                'message' => $exception->getMessage(),
                'stderr' => $exception->getProcess()->getErrorOutput(),
                'stdout' => $exception->getProcess()->getOutput(),
            ]);

            return response()->json(['message' => 'Error running face match process.'], 500);
        }
    }

    private function flattenArray($array)
    {
        $result = [];
        array_walk_recursive($array, function ($a) use (&$result) {
            $result[] = $a;
        });
        return $result;
    }

}
