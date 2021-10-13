<?php

namespace ProgLib\Settings\Console;

use Illuminate\Console\Command;
use ProgLib\Settings\Foundation\Repository;

/**
 *
 */
class ClearCommand extends Command {

    #region Properties

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'settings:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистка кеша настроек';

    #endregion

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        // Очистка
        Repository::clear();

        // Уведомление для разработчика
        $this->info('Settings cache cleared!');
    }
}