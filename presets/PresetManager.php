<?php

namespace FischerEnterprise\LaravelPackageCommands\Presets;

class PresetManager
{

    /**
     * Run a preset and get its content
     *
     * @param string $presetFile
     * @param array $attributes
     * @return string
     */
    public static function GetPresetContent(string $presetFile, array $attributes = []): string
    {
        // Set attribute variables
        foreach ($attributes as $key => $value) {
            $$key = $value;
        }

        // Include file and catch output
        ob_start();
        include __DIR__ . '/res/' . $presetFile . '.php';
        $content = ob_get_clean();

        // Unset attribute variables
        foreach ($attributes as $key => $value) {
            unset($$key);
        }

        // Return content
        return $content;
    }

}
