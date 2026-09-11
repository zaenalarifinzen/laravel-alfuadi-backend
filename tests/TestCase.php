<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // run factories before each test
    protected function setUp(): void
    {
        parent::setUp();
        // $this->artisan('db:seed');
    }
}
