<?php

namespace Ppfeufer\Theme\EVEOnline;

use Exception;
use RuntimeException;

// Register the autoloader.
// phpcs:disable
spl_autoload_register(callback: '\\' . __NAMESPACE__ . '\autoload');
// phpcs:enable

/**
 * Autoloader for the theme classes and interfaces to be loaded dynamically.
 * This will allow us to include only the files we need when we need them.
 *
 * @param string $className The name of the class to load
 * @return void
 * @package Ppfeufer\Theme\Ppfeufer
 */
function autoload(string $className): void {
    // Check if the class name starts with the base namespace or includes `Libs' in the path
    if (!str_starts_with(haystack: $className, needle: __NAMESPACE__)
        || str_contains(haystack: $className, needle: '\Libs')
    ) {
        return;
    }

    // Convert the class name to a relative file path
    $relativeClass = str_replace(
        search: [
            __NAMESPACE__ . '\\',
            '\\'
        ],
        replace: [
            '',
            DIRECTORY_SEPARATOR
        ],
        subject: $className
    );

    // Construct the full file path
    $file = __DIR__ . DIRECTORY_SEPARATOR . $relativeClass . '.php';

    // Include the file if it exists
    try {
        if (file_exists(filename: $file)) {
            include_once $file;
        } else {
            throw new RuntimeException(
                sprintf(
                    '[%1$s] Autoloader error: Class file for %2$s not found at %3$s',
                    __NAMESPACE__,
                    $className,
                    $file
                )
            );
        }
    } catch (Exception $e) {
        error_log(message: $e->getMessage());
    }
}
