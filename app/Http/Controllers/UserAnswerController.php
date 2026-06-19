<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserAnswerRequest;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class UserAnswerController extends Controller
{
    function store(StoreUserAnswerRequest $request)
    {
        try {
            $userId = auth()->id();
            $questionId = $request->question_id;
            $level = $request->level;

            $existingAnswer = UserAnswer::where('user_id', $userId)
                ->where('question_id', $questionId)
                ->where('level', $level)
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
                    'data' => $existingAnswer->load(['user', 'question']),
                ], 200);
            }

            // Save new
            $userAnswer = UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'level' => $level,
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
                'data' => $userAnswer->load(['user', 'question']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($questionId)
    {
        try {
            $userId = auth()->id();
            
            $userAnswer = UserAnswer::where('user_id', $userId)
                ->where('question_id', $questionId)
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
                'data' => $userAnswer->load(['user', 'question']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
