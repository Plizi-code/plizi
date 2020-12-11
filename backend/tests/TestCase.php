<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    protected static $setUpHasRunOnce = true;

    protected function setUp(): void
    {
        parent::setUp();
        if (static::$setUpHasRunOnce) {
            static::$setUpHasRunOnce = false;
            Artisan::call('migrate');
            Artisan::call('db:seed');
        }
    }
}
