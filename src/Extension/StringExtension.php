<?php

namespace FischerEnterprise\LaravelPackageCommands\Extension;

class StringExtension
{

    /**
     * Sanitize a path by unifying seperators,
     * replacing multiple seperators and
     * removing tailing / leading seperators if needed
     *
     * @param string $path
     * @param string $seperator
     * @param bool $endWithSeperator
     * @param bool $startWithSeperator
     * @return string
     */
    public static function SanitizePath(string $path, string $seperator = DIRECTORY_SEPARATOR, bool $startWithSeperator = false, bool $endWithSeperator = false): string
    {
        // Replace seperators and replace multiple with single seperator
        $path = preg_replace('/(\/|\\\\)+/', $seperator, $path);

        // Handle leading seperator if required
        if (!$endWithSeperator && str_ends_with($path, $seperator)) {
            $path = substr($path, 0, -1);
        } else if ($endWithSeperator && !str_ends_with($path, $seperator)) {
            $path .= $seperator;
        }

        // Handle tailing seperator if required
        if (!$startWithSeperator && str_starts_with($path, $seperator)) {
            $path = substr($path, 1);
        } else if ($startWithSeperator && !str_starts_with($path, $seperator)) {
            $path = $seperator . $path;
        }

        // Return sanitized path
        return $path;
    }

    /**
     * Convert a path to a namespace
     *
     * @param string $path
     * @return string
     */
    public static function ToNamespace(string $path): string
    {
        return static::SanitizePath($path, '\\');
    }

    /**
     * Convert snake_case to PascalCase
     *
     * @param string $snakeCase
     * @return string
     */
    public static function SnakeCaseToPascalCase($snakeCase)
    {
        return str_replace('_', '', ucwords($snakeCase, '_'));
    }

}
