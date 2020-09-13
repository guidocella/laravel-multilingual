<?php

namespace GuidoCella\Multilingual;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;

class MultilingualTestCase extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'multilingual.locales' => ['en', 'es'],
            'multilingual.fallback_locale' => 'en',
        ]);
    }
}
