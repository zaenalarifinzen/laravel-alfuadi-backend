<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserAnswerRequest;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class UserAnswerController extends Controller
{
    /**
     * Simpan jawaban user
     */
    public function store(StoreUserAnswerRequest $request)
    {       
        try {
            $userId = auth()->id();
            $exerciseId = $request->exercise_id;
            $level = $request->level;

            // Cek apakah sudah ada jawaban sebelumnya
            $existingAnswer = UserAnswer::where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->where('level', $level)
                ->first();

            if ($existingAnswer) {
                // Jika sudah ada, update status penyelesaian saja
                $existingAnswer->update([
                    'pass' => $request->pass ?? false,
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

            // Jika belum ada, buat catatan penyelesaian baru
            $userAnswer = UserAnswer::create([
                'user_id' => $userId,
                'exercise_id' => $exerciseId,
                'level' => $level,
                'pass' => $request->pass ?? false,
                'score' => $request->score,
                'attempt_count' => $request->attempt_count ?? 1,
                'time_spent' => $request->time_spent,
                'is_latest' => true,
                'metadata' => $request->metadata,
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

    /**
     * Ambil jawaban user untuk soal tertentu
     */
    public function show($exerciseId)
    {
        try {
            $userId = auth()->id();

            $userAnswer = UserAnswer::where('user_id', $userId)
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

    /**
     * Ambil semua jawaban user dengan filter opsional
     */
    public function index(Request $request)
    {
        try {
            $userId = auth()->id();
            $level = $request->query('level'); // filter by level
            $pass = $request->query('pass'); // filter by pass status

            $query = UserAnswer::where('user_id', $userId)
                ->where('is_latest', true)
                ->with(['exercise']);

            // Filter berdasarkan level
            if ($level) {
                $query->where('level', $level);
            }

            // Filter berdasarkan status pass
            if ($pass !== null) {
                $query->where('pass', $pass == 'true' ? 1 : 0);
            }

            $userAnswers = $query->orderBy('created_at', 'desc')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Daftar jawaban user',
                'data' => $userAnswers,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil statistik jawaban user
     */
    public function stats(Request $request)
    {
        try {
            $userId = auth()->id();
            $level = $request->query('level');

            $query = UserAnswer::where('user_id', $userId)
                ->where('is_latest', true);

            if ($level) {
                $query->where('level', $level);
            }

            $totalAnswered = $query->count();
            $totalPassed = (clone $query)->where('pass', true)->count();
            $totalFailed = (clone $query)->where('pass', false)->count();
            $avgScore = (clone $query)->avg('score');
            $avgTime = (clone $query)->avg('time_spent');

            return response()->json([
                'success' => true,
                'message' => 'Statistik jawaban user',
                'data' => [
                    'total_answered' => $totalAnswered,
                    'total_passed' => $totalPassed,
                    'total_failed' => $totalFailed,
                    'pass_rate' => $totalAnswered > 0 ? round(($totalPassed / $totalAnswered) * 100, 2) . '%' : '0%',
                    'avg_score' => $avgScore ? round($avgScore, 2) : null,
                    'avg_time_spent' => $avgTime ? round($avgTime, 0) : null,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
