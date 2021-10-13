<?php

namespace ProgLib\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use ProgLib\Settings\Foundation\Repository;

/**
 *
 */
class SettingsServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        // Регистрация фасада
        $this->app->singleton('settings', function ($app) {
            return new Repository();
        });
    }
}