<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalysisSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_store_and_retrieve_analysis_settings(): void
    {
        Setting::setValue('analysis_allowed_surah_ids', '1,2,3');
        Setting::setValue('analysis_allowed_verses_by_surah', ['1' => [1, 2], '2' => [1]]);

        $this->assertSame([1, 2, 3], Setting::getIntArray('analysis_allowed_surah_ids'));
        $this->assertSame(['1' => [1, 2], '2' => [1]], Setting::getJson('analysis_allowed_verses_by_surah'));
    }
}
