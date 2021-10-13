<?php

namespace ProgLib\Settings\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use ProgLib\Settings\Foundation\Repository;

/**
 *
 */
class SettingsServiceProvider extends ServiceProvider {

    #region Helpers

    /**
     * Возвращает расположение файлов настроек.
     *
     * @return string
     * @throws BindingResolutionException
     */
    public function settingsPath() {

        // Получение расположения из конфигурации
        $settings_path = $this->app->make('config')->get('app.settings_path');

        return ($settings_path)
            ? $this->app->basePath()    . DIRECTORY_SEPARATOR . $settings_path
            : $this->app->storagePath() . DIRECTORY_SEPARATOR . 'settings';
    }

    #endregion

    /**
     * Register the service provider.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register() {

        // Сохранение расположения файлов в экземпляр приложения
        $this->app->instance('path.settings', $this->settingsPath());

        // Регистрация фасада
        $this->app->singleton('settings', function ($app) {
            return new Repository();
        });
    }
}