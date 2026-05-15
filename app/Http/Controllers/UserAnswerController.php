<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserAnswerRequest;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                    'pass' => $request->pass ?? false,
                    'answer' => $request->answer,
                    'score' => $request->score,
                    'attempt_count' => ($existingAnswer->attemp_count ?? 0) + 1,
                    'time_spent' => $request->time_spent,
                    'metadata' => $request->metadata,
                    'is_latest' => true,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Jawaban berhasil diperbarui',
                    'data' => $existingAnswer->load(['user', 'question']),
                ], 200);
            }

            // Save new
            $userAnswer = UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'level' => $level,
                'pass' => $request->pass ?? false,
                'answer' => $request->answer,
                'score' => $request->score,
                'attempt_count' => $request->attemp_count ??  1,
                'time_spent' => $request->time_spent,
                'metadata' => $request->metadata,
                'is_latest' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan',
                'data' => $userAnswer->load(['user', 'question']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
