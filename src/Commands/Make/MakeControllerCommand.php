<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands\Make;

use FischerEnterprise\LaravelPackageCommands\Commands\BaseCommand;
use FischerEnterprise\LaravelPackageCommands\Presets\PresetManager;
use FischerEnterprise\LaravelPackageCommands\Extension\StringExtension;
use FischerEnterprise\LaravelPackageCommands\Extension\ComposerInfoExtension;

/**
 * Create a new controller file
 * @author Ben Fischer
 */
class MakeControllerCommand extends BaseCommand
{
    protected $signature = 'make:controller {name} {--model|-m=} {--resource|-r} {--api} {--invokable|-i}';
    protected $description = 'Create a new controller file';

    protected function executeCommand(): int
    {
        // Get root
        $packageRoot = getcwd();
        $controllerRoot = "$packageRoot/src/Http/Controllers";

        // Create model root
        if (!is_dir($controllerRoot)) {
            mkdir($controllerRoot, 0777, true);
        }

        // Generate path and file name
        $path = preg_split('/(\/|\\\\)/', $this->getArgument('name'));
        $name = array_pop($path);
        $path = implode('/', $path);

        // Remove tailing and leading `/` from path
        $path = StringExtension::SanitizePath($path, '/');

        // Check for existing controller
        if (file_exists("$controllerRoot/$path/$name.php")) {
            $this->error("Controller file at 'src/Http/Controllers/$path/$name.php' already exists");
            return 1;
        }

        // Create folders
        if (!is_dir("$controllerRoot/$path")) {
            mkdir("$controllerRoot/$path", 0777, true);
        }

        // Create base controller if required
        if (!file_exists("$controllerRoot/$path/Controller.php")) {
            $fileContent = PresetManager::GetPresetContent('Controller/BaseController', [
                'defaultNamespace' => ComposerInfoExtension::GetDefaultNamespace(),
            ]);
            file_put_contents("$controllerRoot/$path/Controller.php", $fileContent);
            $this->info("Created base controller in src/Http/Controllers/$path/Controller.php");
        }

        // Create namespace
        $namespace = ComposerInfoExtension::GetDefaultNamespace() . 'Http\\Controllers\\';

        // Append path to namespace
        $namespace = StringExtension::ToNamespace("$namespace\\$path");

        // Split model into class and namespace
        $modelClassName = null;
        $modelNameSpace = null;

        if ($this->getOption('model')) {
            $modelPath = preg_split('/(\/|\\\\)/', $this->getOption('model'));
            $modelClassName = array_pop($modelPath);
            $modelPath = implode('\\', $modelPath);
            $modelNameSpace = ComposerInfoExtension::GetDefaultNamespace() . 'Models\\';
            $modelNameSpace .= StringExtension::SanitizePath($modelPath, '\\', false, true);
        }

        // Determine preset file
        $presetFile = 'Controller/Default';
        if ($this->getOption('api')) {
            $presetFile = 'Controller/Api';
        } else if ($this->getOption('model') || $this->getOption('resource')) {
            $presetFile = 'Controller/Resource';
        } else if ($this->getOption('invokable')) {
            $presetFile = 'Controller/Invokable';
        }

        if ($this->getOption('model') !== null) {
            $presetFile .= 'Model';
        }

        // Create file from preset
        $fileContent = PresetManager::GetPresetContent($presetFile, [
            'namespace' => $namespace,
            'className' => $name,
            'modelName' => $modelClassName,
            'modelNamespace' => $modelNameSpace,
        ]);
        file_put_contents("$controllerRoot/$path/$name.php", $fileContent);

        // Write success to console
        $this->info("New controller generated at src/Http/Controllers/$path/$name.php");

        // Return success
        return 0;
    }

}
