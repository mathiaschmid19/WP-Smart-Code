<?php
/**
 * Format Adapter for Edge Code Snippets.
 * 
 * Handles importing from different snippet plugin formats.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * FormatAdapter class for converting different plugin formats to ECS format.
 *
 * @since 1.0.0
 */
class FormatAdapter {
	/**
	 * Detect the format of the import data.
	 *
	 * @param array $data Import data.
	 * @return string Format type (ecs, wpcode, code-snippets, unknown).
	 */
	public static function detect_format( array $data ): string {
		// Check for ECS format
		if ( isset( $data['version'] ) && isset( $data['snippets'] ) && isset( $data['exported_at'] ) ) {
			return 'ecs';
		}

		// Check for single ECS snippet
		if ( isset( $data['version'] ) && isset( $data['snippet'] ) ) {
			return 'ecs';
		}

		// Check if it's an array of snippets (could be WPCode or Code Snippets)
		if ( isset( $data[0] ) && is_array( $data[0] ) ) {
			// Check for WPCode format
			if ( isset( $data[0]['code_type'] ) && isset( $data[0]['auto_insert'] ) ) {
				return 'wpcode';
			}

			// Check for Code Snippets format
			if ( isset( $data[0]['scope'] ) && isset( $data[0]['active'] ) ) {
				return 'code-snippets';
			}
		}

		return 'unknown';
	}

	/**
	 * Convert import data to ECS format.
	 *
	 * @param array  $data Import data.
	 * @param string $format Format type.
	 * @return array Converted data in ECS format.
	 */
	public static function convert_to_ecs_format( array $data, string $format ): array {
		switch ( $format ) {
			case 'ecs':
				return $data;

			case 'wpcode':
				return self::convert_from_wpcode( $data );

			case 'code-snippets':
				return self::convert_from_code_snippets( $data );

			default:
				return $data;
		}
	}

	/**
	 * Convert WPCode format to ECS format.
	 *
	 * @param array $wpcode_data WPCode snippets array.
	 * @return array ECS format data.
	 */
	private static function convert_from_wpcode( array $wpcode_data ): array {
		$ecs_snippets = [];

		foreach ( $wpcode_data as $wpcode_snippet ) {
			// Map WPCode fields to ECS fields
			$title = ! empty( $wpcode_snippet['title'] ) ? $wpcode_snippet['title'] : 'Imported Snippet';
			$slug  = sanitize_title( $title );

			// Map code_type to our type
			$type = self::map_wpcode_type( $wpcode_snippet['code_type'] ?? 'php' );

			// Clean up code (remove \r\n and convert to proper line breaks)
			$code = isset( $wpcode_snippet['code'] ) ? str_replace( '\r\n', "\n", $wpcode_snippet['code'] ) : '';
			$code = str_replace( '\/', '/', $code ); // Unescape forward slashes
			$code = stripslashes( $code );

			// Map active status
			$active = isset( $wpcode_snippet['auto_insert'] ) && 1 === $wpcode_snippet['auto_insert'];

			// Map location to conditions
			$conditions = self::map_wpcode_location( $wpcode_snippet['location'] ?? 'everywhere' );

			$ecs_snippets[] = [
				'title'      => $title,
				'slug'       => $slug,
				'type'       => $type,
				'code'       => $code,
				'active'     => $active,
				'conditions' => wp_json_encode( $conditions ),
			];
		}

		return [
			'version'     => ECS_VERSION,
			'exported_at' => current_time( 'mysql' ),
			'exported_by' => get_current_user_id(),
			'site_url'    => get_site_url(),
			'snippets'    => $ecs_snippets,
		];
	}

	/**
	 * Convert Code Snippets format to ECS format.
	 *
	 * @param array $cs_data Code Snippets array.
	 * @return array ECS format data.
	 */
	private static function convert_from_code_snippets( array $cs_data ): array {
		$ecs_snippets = [];

		foreach ( $cs_data as $cs_snippet ) {
			$title = ! empty( $cs_snippet['name'] ) ? $cs_snippet['name'] : 'Imported Snippet';
			$slug  = sanitize_title( $title );

			// Code Snippets is PHP-only by default
			$type = isset( $cs_snippet['scope'] ) && 'front-end' === $cs_snippet['scope'] ? 'php' : 'php';

			$code   = $cs_snippet['code'] ?? '';
			$active = isset( $cs_snippet['active'] ) && ( 1 === $cs_snippet['active'] || '1' === $cs_snippet['active'] );

			// Map scope to conditions
			$conditions = self::map_code_snippets_scope( $cs_snippet['scope'] ?? 'global' );

			$ecs_snippets[] = [
				'title'      => $title,
				'slug'       => $slug,
				'type'       => $type,
				'code'       => $code,
				'active'     => $active,
				'conditions' => wp_json_encode( $conditions ),
			];
		}

		return [
			'version'     => ECS_VERSION,
			'exported_at' => current_time( 'mysql' ),
			'exported_by' => get_current_user_id(),
			'site_url'    => get_site_url(),
			'snippets'    => $ecs_snippets,
		];
	}

	/**
	 * Map WPCode type to ECS type.
	 *
	 * @param string $wpcode_type WPCode type.
	 * @return string ECS type.
	 */
	private static function map_wpcode_type( string $wpcode_type ): string {
		$type_map = [
			'php'        => 'php',
			'js'         => 'js',
			'javascript' => 'js',
			'css'        => 'css',
			'html'       => 'html',
			'text'       => 'html',
		];

		return $type_map[ strtolower( $wpcode_type ) ] ?? 'php';
	}

	/**
	 * Map WPCode location to ECS conditions.
	 *
	 * @param string $location WPCode location.
	 * @return array ECS conditions.
	 */
	private static function map_wpcode_location( string $location ): array {
		$conditions = [];

		switch ( $location ) {
			case 'everywhere':
				// No conditions needed
				break;

			case 'admin':
				$conditions['page_type'] = [ 'admin' ];
				break;

			case 'frontend':
			case 'front-end':
				$conditions['page_type'] = [ 'home', 'front_page', 'single', 'page', 'archive', 'search' ];
				break;

			case 'single':
				$conditions['page_type'] = [ 'single' ];
				break;

			case 'page':
				$conditions['page_type'] = [ 'page' ];
				break;

			case 'archive':
				$conditions['page_type'] = [ 'archive' ];
				break;

			default:
				// Unknown location, default to everywhere
				break;
		}

		return $conditions;
	}

	/**
	 * Map Code Snippets scope to ECS conditions.
	 *
	 * @param string $scope Code Snippets scope.
	 * @return array ECS conditions.
	 */
	private static function map_code_snippets_scope( string $scope ): array {
		$conditions = [];

		switch ( $scope ) {
			case 'global':
				// No conditions needed
				break;

			case 'admin':
				$conditions['page_type'] = [ 'admin' ];
				break;

			case 'front-end':
			case 'frontend':
				$conditions['page_type'] = [ 'home', 'front_page', 'single', 'page', 'archive', 'search' ];
				break;

			case 'single-use':
				// No equivalent in ECS, treat as global
				break;

			default:
				// Unknown scope, default to everywhere
				break;
		}

		return $conditions;
	}

	/**
	 * Get format name for display.
	 *
	 * @param string $format Format type.
	 * @return string Format display name.
	 */
	public static function get_format_name( string $format ): string {
		$names = [
			'ecs'           => 'Edge Code Snippets',
			'wpcode'        => 'WPCode',
			'code-snippets' => 'Code Snippets',
			'unknown'       => 'Unknown Format',
		];

		return $names[ $format ] ?? $names['unknown'];
	}
}

