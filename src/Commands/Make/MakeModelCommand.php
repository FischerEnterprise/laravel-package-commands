<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands\Make;

use FischerEnterprise\LaravelPackageCommands\Commands\BaseCommand;
use FischerEnterprise\LaravelPackageCommands\Presets\PresetManager;
use FischerEnterprise\LaravelPackageCommands\Extension\StringExtension;
use FischerEnterprise\LaravelPackageCommands\Extension\ComposerInfoExtension;

/**
 * Create a new model file
 * @author Ben Fischer
 */
class MakeModelCommand extends BaseCommand
{
    protected $signature = 'make:model {name}';
    protected $description = 'Create a new model file';

    protected function executeCommand(): int
    {
        // Get root
        $packageRoot = getcwd();
        $modelRoot = "$packageRoot/src/Models";

        // Create model root
        if (!is_dir($modelRoot)) {
            mkdir($modelRoot, 0777, true);
        }

        // Generate path and file name
        $path = preg_split('/(\/|\\\\)/', $this->getArgument('name'));
        $name = array_pop($path);
        $path = implode('/', $path);

        // Remove tailing and leading `/` from path
        $path = StringExtension::SanitizePath($path, '/');

        // Check for existing model
        if (file_exists("$modelRoot/$path/$name.php")) {
            $this->error("Model file at 'src/Models/$path/$name.php' already exists");
            return 1;
        }

        // Create folders
        if (!is_dir("$modelRoot/$path")) {
            mkdir("$modelRoot/$path", 0777, true);
        }

        // Create namespace
        $namespace = ComposerInfoExtension::GetDefaultNamespace();

        // Append path to namespace
        $namespace .= StringExtension::ToNamespace($path);

        // Remove trailing `\` from namespace
        while (str_ends_with($namespace, '\\')) {
            $namespace = substr($namespace, 0, -1);
        }

        // Create file from preset
        $fileContent = PresetManager::GetPresetContent('ModelPreset', [
            'namespace' => $namespace,
            'modelName' => $name,
        ]);
        file_put_contents("$modelRoot/$path/$name.php", $fileContent);

        // Write success to console
        $this->info("New model generated at src/Models/$path/$name.php");

        // Return success
        return 0;
    }

}
