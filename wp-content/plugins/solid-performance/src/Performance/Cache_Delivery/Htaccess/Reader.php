<?php
/**
 * The .htaccess reader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;
use SolidWP\Performance\Filesystem\Filesystem;

/**
 * The .htaccess reader.
 *
 * @package SolidWP\Performance
 */
final class Reader implements Readable {

	/**
	 * @var Htaccess_File
	 */
	private Htaccess_File $htaccess;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @param Htaccess_File $htaccess The htaccess file object.
	 * @param Filesystem    $filesystem The filesystem component.
	 */
	public function __construct(
		Htaccess_File $htaccess,
		Filesystem $filesystem
	) {
		$this->htaccess   = $htaccess;
		$this->filesystem = $filesystem;
	}

	/**
	 * Acquire a read lock and read the contents of the .htaccess file.
	 *
	 * @throws CacheDeliveryReadException When we are unable to open or lock the .htaccess file for reading.
	 *
	 * @return string
	 */
	public function read(): string {
		try {
			if ( ! $this->htaccess->exists() ) {
				return '';
			}

			return $this->filesystem->read( $this->htaccess->get_file_path() );
		} catch ( RuntimeException $e ) {
			throw new CacheDeliveryReadException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
