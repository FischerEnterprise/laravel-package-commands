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

        // Generate path and file name
        $path = explode('.', $this->getArgument('path'));
        $name = array_pop($path);
        $path = implode('/', $path);

        // Check for existing view
        if (file_exists("$viewRoot/$path/$name.blade.php")) {
            $this->error("View file at 'resources/views/$path/$name.blade.php' already exists");
            return 1;
        }

        // Create folders and file
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
