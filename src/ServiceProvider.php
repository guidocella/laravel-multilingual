<?php

namespace GuidoCella\Multilingual;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([__DIR__.'/config/multilingual.php' => config_path('multilingual.php')]);

        $this->app['validator']->extendImplicit('translatable_required', function ($attribute, $value, $parameters) {
            if (!is_array($value)) {
                return false;
            }

            return collect(config('multilingual.locales'))->every(fn ($locale) => !empty($value[$locale]));
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/multilingual.php', 'multilingual');
    }
}
