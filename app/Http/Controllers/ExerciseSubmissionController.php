<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseSubmissionRequest;
use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\ExerciseSubmission;
use App\Models\UserLevelProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExerciseSubmissionController extends Controller
{
    function store(StoreExerciseSubmissionRequest $request)
    {
        try {
            $userId = auth()->id();
            $exerciseId = $request->exercise_id;
            $level = $request->level;

            $availableLevel = ExerciseLevel::active()->orderBy('display_order', 'asc')->get();
            $currentLevel = $availableLevel->firstWhere('slug', $level);
            $nextLevel = $availableLevel->firstWhere('level_number', '>', $currentLevel->level_number);

            $exercise = Exercise::where('id', $exerciseId)
                ->where('level_id', $currentLevel->id)
                ->first();

            $isLastInLevel = !Exercise::active()
                ->where('level_id', $exercise->level_id)
                ->where('display_order', '>', $exercise->display_order)
                ->exists();

            $existingAnswer = ExerciseSubmission::where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->where('level_id', $currentLevel->id)
                ->first();

            $responseStatus = 201;

            if ($existingAnswer) {
                $existingAnswer->update([
                    'passed' => $request->pass ?? false,
                    'score' => $request->score,
                    'attempt_count' => ($existingAnswer->attempt_count ?? 0) + 1,
                    'time_spent' => $request->time_spent,
                    'metadata' => $request->metadata,
                    'is_latest' => true,
                ]);
                $responseStatus = 200;
            } else {
                ExerciseSubmission::create([
                    'user_id' => $userId,
                    'exercise_id' => $exerciseId,
                    'level_id' => $currentLevel->id,
                    'passed' => $request->pass ?? false,
                    'score' => $request->score,
                    'attempt_count' => $request->attempt_count ?? 1,
                    'time_spent' => $request->time_spent,
                    'metadata' => $request->metadata,
                    'is_latest' => true,
                ]);
            }

            $nextExercise = $isLastInLevel && $request->boolean('pass') && $nextLevel
                ? $nextLevel->activeExercises()
                    ->orderBy('display_order')
                    ->orderBy('id')
                    ->first()
                : null;

            if ($isLastInLevel && $request->boolean('pass')) {
                UserLevelProgress::updateOrCreate(
                    ['user_id' => $userId, 'exercise_level_id' => $exercise->level_id],
                    ['completed_at' => now()]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Status penyelesaian berhasil disimpan',
                'data' => [
                    'next_exercise' => $nextExercise ? [
                        'level' => $nextLevel->slug,
                        'id' => $nextExercise->id,
                    ] : null,
                ],
            ], $responseStatus);
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
