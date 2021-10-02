<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands;

/**
 * Greetings :)
 * @author Ben Fischer
 */
class HelloCommand extends BaseCommand
{
    protected $signature = 'lpc:hello';
    protected $description = 'Greetings :)';

    protected function executeCommand(): int
    {
        $this->info('Greetings my friend :)');
        return 0;
    }

}
