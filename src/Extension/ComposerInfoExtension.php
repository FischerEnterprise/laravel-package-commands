<?php

namespace FischerEnterprise\LaravelPackageCommands\Extension;

use RuntimeException;

/**
 * Extension class to get information from the packs composer.json
 * @author Ben Fischer
 */
class ComposerInfoExtension
{
    /**
     * Get the packages composer info
     *
     * @return object
     */
    public static function GetComposerInfo()
    {
        return json_decode(static::GetComposerContent());
    }

    /**
     * Try to get the default namespace
     *
     * @return string
     */
    public static function GetDefaultNamespace(): string
    {
        $namespace = null;
        foreach (static::GetComposerInfo()->autoload->{'psr-4'} as $key => $value) {
            if ($value === 'src' || $value === 'src/') {
                $namespace = $key;
                break;
            }
        }
        if ($namespace === null) {
            throw new RuntimeException('Could not find default namespace. Maybe you modified your composer.json autoload?');
        }

        return $namespace;
    }

    /**
     * Get the raw composer.json content
     *
     * @return string
     */
    private static function GetComposerContent(): string
    {
        $path = getcwd() . '/composer.json';

        if (!file_exists($path)) {
            throw new RuntimeException("Could not find composer.json at '$path'", 1);

        }

        return file_get_contents($path);
    }
}
