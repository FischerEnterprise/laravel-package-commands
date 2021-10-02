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
        if (!is_dir($viewRoot)) {
            mkdir($viewRoot, 0777, true);
        }

        // Create view file
        $path = explode('.', $this->getArgument('path'));
        $name = array_pop($path);
        $path = implode('/', $path);
        if (!is_dir("$viewRoot/$path")) {
            mkdir("$viewRoot/$path", 0777, true);
        }
        file_put_contents("$viewRoot/$path/$name.blade.php", '');

        // Write success to console
        $this->info("New view generated at resources/views/$path/$name.blade.php");

        // Return success
        return 0;
    }

}
