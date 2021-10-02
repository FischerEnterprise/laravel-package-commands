<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands;

/**
 * Copy the pack file to the package root for easier usage
 * @author Ben Fischer
 */
class InitCommand extends BaseCommand
{
    protected $signature = 'lpc:init';
    protected $description = "Initialize laravel-package-commands for better usage";

    protected function executeCommand(): int
    {
        // Symlink pack command
        $packageRoot = getcwd();

        // Copy pack file
        $copySuccess = copy(__DIR__ . '/../../bin/pack', "$packageRoot/pack");

        // Return success code
        return $copySuccess ? 0 : 1;
    }

}
