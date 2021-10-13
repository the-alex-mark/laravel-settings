<?php

namespace ProgLib\Settings\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ProgLib\Settings\Console\CacheCommand;
use ProgLib\Settings\Console\ClearCommand;
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

        return (isset($settings_path))
            ? $this->app->basePath()    . DIRECTORY_SEPARATOR . $settings_path
            : $this->app->storagePath() . DIRECTORY_SEPARATOR . 'settings';
    }

    #endregion

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            'command.settings.clear',
            'command.settings.cache'
        ];
    }

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

        // Команда очистки кеша настроек
        $this->app->singleton('command.settings.clear', function ($app) {
            return new ClearCommand();
        });

        // Команда кеширования настроек
        $this->app->singleton('command.settings.cache', function ($app) {
            return new CacheCommand();
        });

        $this->commands(
            'command.settings.clear',
            'command.settings.cache'
        );

        // Создание директории файлов при её отсутствии
        if (!File::exists($this->app->make('path.settings')))
            File::makeDirectory($this->app->make('path.settings'), 0777, true, true);
    }
}