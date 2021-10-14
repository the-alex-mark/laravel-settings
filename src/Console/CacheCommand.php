<?php

namespace ProgLib\Settings\Console;

use Illuminate\Console\Command;
use ProgLib\Settings\Foundation\Repository;

/**
 *
 */
class CacheCommand extends Command {

    #region Properties

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'settings:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Кеширование данных настроек';

    #endregion

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        // Получение данных
        Repository::clear();
        Repository::load();

        // Уведомление для разработчика
        $this->info('Settings cache cleared!');
        $this->info('Settings cached successfully!');
    }
}
