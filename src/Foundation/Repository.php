<?php

namespace ProgLib\Settings\Foundation;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Yaml\Yaml;

/**
 *
 */
class Repository extends ConfigRepository {

    /**
     * Инициализирует новый экземпляр для работы с настройками.
     *
     * @return void
     */
    public function __construct() {

        // Выполнение исходного метода
        parent::__construct($this->load());
    }

    #region Properties

    /**
     * Ключ кеша.
     *
     * @var string
     */
    private $cache_key = 'the_alex_mark::settings';

    #endregion

    /**
     * Загружает настройки из соответствующей директории.
     *
     * @return array
     */
    private function load() {
        return Cache::remember($this->cache_key, Cache::get('cache.ttl', 600), function () {
            $item = [];
            foreach (glob(settings_path('*.yml')) as $file) {
                if (is_readable($file)) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $item[$filename] = Yaml::parseFile($file, Yaml::PARSE_OBJECT);
                }
            }

            return $item;
        });
    }

    /**
     * Загружает настройки с пересохранением в кеш.
     *
     * @return void
     */
    public function reload() {
        Cache::forget($this->cache_key);

        // Выполнение исходного метода
        parent::__construct($this->load());
    }

    public function set($key, $value = null) {

        // Выполнение исходного метода
        parent::set($key, $value);

        // Сохранение файла настроек
        $arr = explode('.', $key);
        $filename = reset($arr);
        if (!empty($filename)) {
            $yaml = Yaml::dump($this->get($filename), 10, 4, Yaml::DUMP_OBJECT);
            $filename = $filename . '.yml';
            file_put_contents(settings_path($filename), $yaml,  LOCK_EX);

            // Кеширование актуальных данных
            Cache::put($this->cache_key, $this->items, config('cache.ttl', 600));
        }
    }
}