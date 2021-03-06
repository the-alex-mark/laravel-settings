<?php

if (!function_exists('settings_path')) {

    /**
     * Возвращает расположение файлов настроек.
     *
     * @param  string  $path
     * @return string
     */
    function settings_path($path = '') {
        return app('path.settings') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('settings')) {

    /**
     * Возвращает или задаёт значение параметров настроек.
     *
     * @param  array|string|null $key     Ключ.
     * @param  mixed             $default Значение по умолчанию.
     * @return mixed|ProgLib\Settings\Foundation\Repository
     */
    function settings($key = null, $default = null) {

        // Возврат экземпляра «Settings»
        if (is_null($key))
            return app('settings');

        // Установка списка значений
        if (is_array($key))
            return app('settings')->set($key);

        // Возврат значения указанных настроек
        return app('settings')->get($key, $default);
    }
}
