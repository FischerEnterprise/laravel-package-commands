<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands\Make;

use RuntimeException;
use FischerEnterprise\LaravelPackageCommands\Commands\BaseCommand;
use FischerEnterprise\LaravelPackageCommands\Presets\PresetManager;
use FischerEnterprise\LaravelPackageCommands\Extension\StringExtension;

/**
 * Create a new migration file
 * @author Ben Fischer
 */
class MakeMigrationCommand extends BaseCommand
{
    protected $signature = 'make:migration {name}';
    protected $description = 'Create a new migration file';

    protected function executeCommand(): int
    {
        // Get root
        $packageRoot = getcwd();
        $migrationRoot = "$packageRoot/database/migrations";

        // Create migration root
        if (!is_dir($migrationRoot)) {
            mkdir($migrationRoot, 0777, true);
        }

        // Check for valid migration name
        if (
            str_contains($this->getArgument('name'), '/') ||
            str_contains($this->getArgument('name'), '\\')
        ) {
            throw new RuntimeException('Migrations should not be split into multiple folders');
        }

        // Generate class and file name
        $baseName = $this->getArgument('name');
        $fileName = date('Y_m_d_His_') . $baseName;
        $className = StringExtension::SnakeCaseToPascalCase($baseName);

        // Check for existing migration
        if (file_exists("$migrationRoot/$fileName.php")) {
            $this->error("Migration file at 'database/migrations/$fileName.php' already exists");
            return 1;
        }

        // Try to interpret desired migration action
        $withContent = false;
        $mode = null;
        $table = null;

        if (str_starts_with($baseName, 'create_')) {
            $mode = 'create';
            $withContent = true;
            $table = substr($baseName, strlen('create_'));
            if (str_ends_with($table, '_table')) {
                $table = substr($table, 0, -strlen('_table'));
            }
        } else if (
            str_contains($baseName, '_on_') ||
            str_contains($baseName, '_in_') ||
            str_contains($baseName, '_to_')
        ) {
            $withContent = true;
            $mode = 'update';

            $nameParts = preg_split('/(_on_|_in_|_to_)/', $baseName);
            $table = array_pop($nameParts);
            if (str_ends_with($table, '_table')) {
                $table = substr($table, 0, -strlen('_table'));
            }
        }

        // Create file from preset
        $fileContent = PresetManager::GetPresetContent('MigrationPreset', [
            'className' => $className,
            'withContent' => $withContent,
            'mode' => $mode,
            'table' => $table,
        ]);
        file_put_contents("$migrationRoot/$fileName.php", $fileContent);

        // Write success to console
        $this->info("New migration generated at database/migrations/$fileName.php");

        // Return success
        return 0;
    }

}
