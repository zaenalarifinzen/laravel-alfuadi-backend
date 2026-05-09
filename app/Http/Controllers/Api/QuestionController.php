<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions
     */
    public function index(Request $request)
    {
        try {
            $query = Question::with(['creator']);

            // Filter by level
            if ($request->query('level')) {
                $query->where('level', $request->query('level'));
            }

            // Filter by type
            if ($request->query('type')) {
                $query->where('type', $request->query('type'));
            }

            // Filter by active status
            if ($request->query('is_active') !== null) {
                $query->where('is_active', $request->query('is_active') == 'true' ? 1 : 0);
            }

            // Filter by creator
            if ($request->query('created_by')) {
                $query->where('created_by', $request->query('created_by'));
            }

            $questions = $query->orderBy('display_order')
                ->orderBy('created_at', 'desc')
                ->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Daftar soal',
                'data' => $questions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created question
     */
    public function store(StoreQuestionRequest $request)
    {
        try {
            $question = Question::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dibuat',
                'data' => $question->load(['creator']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified question
     */
    public function show($id)
    {
        try {
            $question = Question::with(['creator', 'userAnswers'])->find($id);

            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Soal tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail soal',
                'data' => $question,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified question
     */
    public function update(UpdateQuestionRequest $request, $id)
    {
        try {
            $question = Question::find($id);

            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Soal tidak ditemukan',
                ], 404);
            }

            $question->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil diperbarui',
                'data' => $question->load(['creator']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified question
     */
    public function destroy($id)
    {
        try {
            $question = Question::find($id);

            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Soal tidak ditemukan',
                ], 404);
            }

            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get questions by level for user (active only)
     */
    public function getByLevel($level)
    {
        try {
            $questions = Question::where('level', $level)
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar soal level ' . $level,
                'data' => $questions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get statistics for questions
     */
    public function stats(Request $request)
    {
        try {
            $query = Question::query();

            if ($request->query('level')) {
                $query->where('level', $request->query('level'));
            }

            $totalQuestions = (clone $query)->count();
            $totalAttempts = (clone $query)->sum('attempts');
            $totalPassed = (clone $query)->sum('passed');
            $averagePassRate = $totalAttempts > 0 ? round(($totalPassed / $totalAttempts) * 100, 2) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Statistik soal',
                'data' => [
                    'total_questions' => $totalQuestions,
                    'total_attempts' => $totalAttempts,
                    'total_passed' => $totalPassed,
                    'average_pass_rate' => $averagePassRate . '%',
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
