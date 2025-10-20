<?php
/**
 * PSR-4 Autoloader for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * Autoloader class for loading ECS classes using PSR-4.
 *
 * @since 1.0.0
 */
class Autoloader {
	/**
	 * Namespace prefix.
	 *
	 * @var string
	 */
	private const NAMESPACE_PREFIX = 'ECS\\';

	/**
	 * Base directory for the namespace.
	 *
	 * @var string
	 */
	private const BASE_DIR = __DIR__;

	/**
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public static function register(): void {
		spl_autoload_register( [ __CLASS__, 'load' ] );
	}

	/**
	 * Load a class file based on PSR-4 standards.
	 *
	 * @param string $class The fully qualified class name.
	 * @return void
	 */
	public static function load( string $class ): void {
		// Check if class belongs to ECS namespace.
		if ( strpos( $class, self::NAMESPACE_PREFIX ) === 0 ) {
			// Remove namespace prefix.
			$relative_class = substr( $class, strlen( self::NAMESPACE_PREFIX ) );

			// Convert class name to file name (PascalCase to kebab-case, convert underscores to hyphens)
			// e.g., Sandbox -> sandbox, RestAPI -> rest-api, AI_Ajax -> ai-ajax, AI_Generator -> ai-generator
			$file_name = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $relative_class ) );
			$file_name = str_replace( '_', '-', $file_name );
			
			// Build file path with correct naming convention (class-ecs-*)
			$file = self::BASE_DIR . DIRECTORY_SEPARATOR . 'class-ecs-' . $file_name . '.php';

			// Load the file if it exists.
			if ( file_exists( $file ) ) {
				require_once $file;
			} else {
				// Debug: Log missing file with more details
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( "[ECS] Autoloader: File not found: $file for class: $class" );
					error_log( "[ECS] Autoloader: BASE_DIR: " . self::BASE_DIR );
					error_log( "[ECS] Autoloader: file_name: $file_name" );
					error_log( "[ECS] Autoloader: relative_class: $relative_class" );
				}
			}
		}
	}
}
