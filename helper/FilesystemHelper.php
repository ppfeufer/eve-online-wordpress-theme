<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class FilesystemHelper {
	/**
	 * Removing either the content of a directory or the directory recursively
	 *
	 * @param string $directory
	 * @param boolean $removeDirectory Remove the given Directoy as well? (true or false)
	 */
	public static function deleteDirectoryRecursive($directory, $removeDirectory = false) {
		// open dir and save it in a handle
		$entry = \opendir($directory);

		// read content of $dir and save it in $file
		while($file = \readdir($entry)) {
			$path = $directory . '/' . $file;

			if ($file !== '.' && $file !== '..') {
				// check if handle is a dir or a file
				if(\is_dir($path)) {
					self::deleteDirectoryRecursive($path);
				} else {
					\unlink($path);
				}
			}
		}

		// close dir handle
		\closedir($entry);

		if($removeDirectory === true) {
			//remove dir
			rmdir($directory);
		}
	}
}
