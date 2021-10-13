# Laravel Settings

Реализует систему хранения перезаписываемых настроек в формате «YAML».

<br>

## Установка

```bash
composer require the_alex_mark/laravel-settings
```

<br>

## Использование

<br>

Установка параметров конфигурации (необязательно).  
Расположение по умолчанию: `storage_path('settings/')`
```ini
SETTINGS_PATH=settings/
```

<br>

Пример использования:
```php
// Получение и установка данных через фасад
Settings::get('example.enabled');
Settings::set('example.enabled', true);

// Получение данных через вспомогательную функцию
settings('example.enabled');

// Получение расположения файлов настроек
$path = settings_path();
```

<br>

Дополнительные команды `Artisan`:
```bash
# Очистка
php artisan settings:clear

# Кеширование актуальных данных
php artisan settings:cache
```
