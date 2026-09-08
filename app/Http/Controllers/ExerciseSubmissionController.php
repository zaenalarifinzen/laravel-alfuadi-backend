<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseSubmissionRequest;
use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\ExerciseSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExerciseSubmissionController extends Controller
{
    function store(StoreExerciseSubmissionRequest $request)
    {
        try {
            $userId = auth()->id();
            $exerciseNumber = $request->exercise_number;
            $level = $request->level;

            $exerciseLevel = ExerciseLevel::where('slug', $level)->first();
            $exerciseLevelNumber = $exerciseLevel->level_number;

            $exercise = Exercise::where('display_order', $exerciseNumber)
                ->where('level_id', $exerciseLevelNumber)
                ->first();
            $exerciseId = $exercise->id;

            $existingAnswer = ExerciseSubmission::where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->where('level_id', $exerciseLevelNumber)
                ->first();

            // Update
            if ($existingAnswer) {
                $existingAnswer->update([
                    'passed' => $request->pass ?? false,
                    'score' => $request->score,
                    'attempt_count' => ($existingAnswer->attempt_count ?? 0) + 1,
                    'time_spent' => $request->time_spent,
                    'metadata' => $request->metadata,
                    'is_latest' => true,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status penyelesaian berhasil diperbarui',
                    'data' => $existingAnswer->load(['user', 'exercise']),
                ], 200);
            }

            // Save new
            $userAnswer = ExerciseSubmission::create([
                'user_id' => $userId,
                'exercise_id' => $exerciseId,
                'level_id' => $exerciseLevelNumber,
                'passed' => $request->pass ?? false,
                'score' => $request->score,
                'attempt_count' => $request->attempt_count ??  1,
                'time_spent' => $request->time_spent,
                'metadata' => $request->metadata,
                'is_latest' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status penyelesaian berhasil disimpan',
                'data' => $userAnswer->load(['user', 'exercise']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($exerciseId)
    {
        try {
            $userId = auth()->id();
            
            $userAnswer = ExerciseSubmission::where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->where('is_latest', true)
                ->first();

            if (!$userAnswer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail status penyelesaian',
                'data' => $userAnswer->load(['user', 'exercise']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
