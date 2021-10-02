<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands\Make;

use FischerEnterprise\LaravelPackageCommands\Commands\BaseCommand;

/**
 * Create a new view file
 * @author Ben Fischer
 */
class MakeViewCommand extends BaseCommand
{
    protected $signature = 'make:view {path}';
    protected $description = 'Create a new view file';

    protected function executeCommand(): int
    {
        // Get root
        $packageRoot = getcwd();
        $viewRoot = "$packageRoot/resources/views";

        // Create view root
        mkdir($viewRoot, 0777, true);

        // Create view file
        $path = str_replace('.', '/', $this->getArgument('path'));
        file_put_contents("$viewRoot/$path", '');

        // Return success
        return 0;
    }

}
