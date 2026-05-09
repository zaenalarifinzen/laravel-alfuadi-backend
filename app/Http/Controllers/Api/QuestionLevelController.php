<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionLevelRequest;
use App\Http\Requests\UpdateQuestionLevelRequest;
use App\Models\QuestionLevel;
use Illuminate\Http\Request;

class QuestionLevelController extends Controller
{
    /**
     * Display a listing of question levels
     */
    public function index(Request $request)
    {
        try {
            $query = QuestionLevel::query();

            // Filter by active status
            if ($request->query('is_active') !== null) {
                $query->where('is_active', $request->query('is_active') == 'true' ? 1 : 0);
            }

            $levels = $query->orderBy('level_number', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar level soal',
                'data' => $levels,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created level
     */
    public function store(StoreQuestionLevelRequest $request)
    {
        try {
            $level = QuestionLevel::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Level berhasil dibuat',
                'data' => $level,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified level
     */
    public function show($id)
    {
        try {
            $level = QuestionLevel::with(['questions'])->find($id);

            if (!$level) {
                return response()->json([
                    'success' => false,
                    'message' => 'Level tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail level',
                'data' => $level,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified level
     */
    public function update(UpdateQuestionLevelRequest $request, $id)
    {
        try {
            $level = QuestionLevel::find($id);

            if (!$level) {
                return response()->json([
                    'success' => false,
                    'message' => 'Level tidak ditemukan',
                ], 404);
            }

            $level->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Level berhasil diperbarui',
                'data' => $level,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified level
     */
    public function destroy($id)
    {
        try {
            $level = QuestionLevel::find($id);

            if (!$level) {
                return response()->json([
                    'success' => false,
                    'message' => 'Level tidak ditemukan',
                ], 404);
            }

            $level->delete();

            return response()->json([
                'success' => true,
                'message' => 'Level berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get level by number
     */
    public function getByNumber($levelNumber)
    {
        try {
            $level = QuestionLevel::where('level_number', $levelNumber)
                ->where('is_active', true)
                ->with(['questions' => function ($query) {
                    $query->where('is_active', true)->orderBy('display_order');
                }])
                ->first();

            if (!$level) {
                return response()->json([
                    'success' => false,
                    'message' => 'Level tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail level',
                'data' => $level,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
