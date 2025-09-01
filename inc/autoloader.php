<?php

namespace Ppfeufer\Theme\EVEOnline;

// phpcs:disable
spl_autoload_register('\Ppfeufer\Theme\EVEOnline\autoload');
// phpcs:enable

function autoload($className) {
    // If the specified $className does not include our namespace, duck out.
    if (strpos($className, 'Ppfeufer\Theme\EVEOnline') === false) {
        return;
    }

    // Split the class name into an array to read the namespace and class.
    $fileParts = explode('\\', $className);

    // Do a reverse loop through $fileParts to build the path to the file.
    $namespace = '';
    for ($i = count($fileParts) - 1; $i > 0; $i--) {
        // Read the current component of the file part.
        $current = str_ireplace('_', '-', $fileParts[$i]);

        $namespace = '/' . $current . $namespace;

        // If we're at the first entry, then we're at the filename.
        if (count($fileParts) - 1 === $i) {
            $namespace = '';
            $fileName = $current . '.php';

            /* If 'interface' is contained in the parts of the file name, then
             * define the $file_name differently so that it's properly loaded.
             * Otherwise, just set the $file_name equal to that of the class
             * filename structure.
             */
            if (strpos(strtolower($fileParts[count($fileParts) - 1]), 'interface')) {
                // Grab the name of the interface from its qualified name.
                $interfaceNameParts = explode('_', $fileParts[count($fileParts) - 1]);
                $interfaceName = $interfaceNameParts[0];

                $fileName = $interfaceName . '.php';
            }
        }

        // Now build a path to the file using mapping to the file location.
        $filepath = trailingslashit(dirname(__FILE__, 2) . $namespace);
        $filepath .= $fileName;

        // If the file exists in the specified path, then include it.
        if (file_exists($filepath)) {
            include_once($filepath);
        }
    }
}

/**
 * In preparation to switch the ESI Client to an WPMU plugin
 */
if (!defined('\WordPress\EsiClient\WP_ESI_CLIENT_LOADED')) {
    /**
     * Autoloading ESI classes
     */
    // phpcs:disable
    spl_autoload_register('\Ppfeufer\Theme\EVEOnline\autoloadEsiClient');
    // phpcs:enable
}

function autoloadEsiClient($className) {
    // If the specified $className does not include our namespace, duck out.
    if (strpos($className, 'WordPress\EsiClient') === false) {
        return;
    }

    // Split the class name into an array to read the namespace and class.
    $fileParts = explode('\\', $className);

    // Do a reverse loop through $fileParts to build the path to the file.
    $namespace = '';
    for ($i = count($fileParts) - 1; $i > 0; $i--) {
        // Read the current component of the file part.
        $current = str_ireplace('_', '-', $fileParts[$i]);

        $namespace = '/' . $current . $namespace;

        // If we're at the first entry, then we're at the filename.
        if (count($fileParts) - 1 === $i) {
            $namespace = '';
            $fileName = $current . '.php';

            /* If 'interface' is contained in the parts of the file name, then
             * define the $file_name differently so that it's properly loaded.
             * Otherwise, just set the $file_name equal to that of the class
             * filename structure.
             */
            if (strpos(strtolower($fileParts[count($fileParts) - 1]), 'interface')) {
                // Grab the name of the interface from its qualified name.
                $interfaceNameParts = explode('_', $fileParts[count($fileParts) - 1]);
                $interfaceName = $interfaceNameParts[0];

                $fileName = $interfaceName . '.php';
            }
        }

        // Now build a path to the file using mapping to the file location.
        $filepath = trailingslashit(WP_CONTENT_DIR . '/EsiClient' . $namespace);
        $filepath .= $fileName;

        // If the file exists in the specified path, then include it.
        if (file_exists($filepath)) {
            include_once($filepath);
        }
    }
}
