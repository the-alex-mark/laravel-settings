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
        parent::__construct($this->load());
    }

    #region Properties

    /**
     * Ключ кеша.
     *
     * @var string
     */
    private static $cache_key = 'the_alex_mark::settings';

    #endregion

    #region Customs

    /**
     * Загружает настройки из соответствующей директории.
     *
     * @return array
     */
    public static function load() {
        return Cache::remember(self::$cache_key, Cache::get('cache.ttl', 600), function () {
            $settings = [];
            foreach (glob(settings_path('*.yml')) as $file) {
                if (is_readable($file)) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $settings[$filename] = Yaml::parseFile($file, Yaml::PARSE_OBJECT);
                }
            }

            return $settings;
        });
    }

    /**
     * Загружает настройки с пересохранением в кеш.
     *
     * @return void
     */
    public function reload() {
        $this->clear();
        $this->__construct();
    }

    /**
     * Выполняет очистку кешированных данных.
     *
     * @return bool
     */
    public static function clear() {
        if (Cache::has(self::$cache_key))
            return Cache::forget(self::$cache_key);

        return true;
    }

    #endregion

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
            Cache::put(self::$cache_key, $this->items, config('cache.ttl', 600));
        }
    }
}