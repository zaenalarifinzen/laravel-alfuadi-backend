<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use App\Models\Verse;
use App\Models\Word;
use App\Models\WordGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $randomVerse = Cache::remember('daily_verse', now()->endOfDay(), function () {
            return Verse::query()
                ->with('surah')
                ->inRandomOrder()
                ->first();
        });

        $latestTask = null;
        $updatedAt = null;
        $latestExercise = null;

        if ($user) {
            if (in_array($user->roles, ['administrator', 'operator'], true)) {
                $latestProgres = Word::query()
                    ->where('editor', $user->id)
                    ->latest('updated_at')
                    ->first();

                if ($latestProgres) {
                    $wordgroup = WordGroup::query()
                        ->where('id', $latestProgres->word_group_id)
                        ->latest('updated_at')
                        ->first();

                    if ($wordgroup) {
                        $surah = Surah::query()
                            ->where('id', $wordgroup->surah_id)
                            ->first();

                        if ($surah) {
                            $latestTask = 'Surah ' . $surah->name . ' ayat ' . $wordgroup->verse_number;
                        }

                        $updatedAt = $latestProgres->updated_at;
                    }
                }
            }
        }

        return view('pages.dashboard.dashboard', [
            'type_menu' => 'dashboard',
            'randomVerse' => $randomVerse,
            'latestTask' => $latestTask,
            'updated_at' => $updatedAt,
            'latestExercise' => $latestExercise,
        ]);
    }
}
