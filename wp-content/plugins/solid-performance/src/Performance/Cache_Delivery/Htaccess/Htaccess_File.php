<?php
/**
 * Represents an .htaccess file.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Contracts\Config_File;

/**
 * Represents an .htaccess file.
 *
 * @package SolidWP\Performance
 */
class Htaccess_File extends Config_File {

	/**
	 * Get the server path to the .htaccess file.
	 *
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return string
	 */
	public function get_file_path(): string {
		if ( $this->filepath !== null ) {
			return $this->filepath;
		}

		$home_path = swpsp_get_document_root();

		$this->filepath = $home_path . '.htaccess';

		return $this->filepath;
	}
}
