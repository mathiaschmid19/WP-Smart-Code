<?php
/**
 * Snippet Model for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * Snippet class for CRUD operations on code snippets.
 *
 * @since 1.0.0
 */
class Snippet {
	/**
	 * Snippet ID.
	 *
	 * @var int
	 */
	private int $id = 0;

	/**
	 * Snippet title.
	 *
	 * @var string
	 */
	private string $title = '';

	/**
	 * Snippet slug.
	 *
	 * @var string
	 */
	private string $slug = '';

	/**
	 * Snippet type (php, js, css, html).
	 *
	 * @var string
	 */
	private string $type = '';

	/**
	 * Snippet code content.
	 *
	 * @var string
	 */
	private string $code = '';

	/**
	 * Whether snippet is active.
	 *
	 * @var bool
	 */
	private bool $active = false;

	/**
	 * Snippet execution mode.
	 *
	 * @var string
	 */
	private string $mode = 'auto_insert';

	/**
	 * Snippet conditions (JSON).
	 *
	 * @var string|null
	 */
	private ?string $conditions = null;

	/**
	 * Author ID.
	 *
	 * @var int
	 */
	private int $author_id = 0;

	/**
	 * Created timestamp.
	 *
	 * @var string
	 */
	private string $created_at = '';

	/**
	 * Updated timestamp.
	 *
	 * @var string
	 */
	private string $updated_at = '';

	/**
	 * Database instance.
	 *
	 * @var DB
	 */
	private DB $db;

	/**
	 * Constructor.
	 *
	 * @param DB $db Database instance.
	 */
	public function __construct( DB $db ) {
		$this->db = $db;
	}

	/**
	 * Create a new snippet.
	 *
	 * @param array{
	 *   title: string,
	 *   slug: string,
	 *   type: string,
	 *   code: string,
	 *   active?: bool,
	 *   mode?: string,
	 *   conditions?: string|null,
	 *   author_id?: int
	 * } $data Snippet data.
	 *
	 * @return int|false Snippet ID on success, false on failure.
	 */
	public function create( array $data ) {
		global $wpdb;

		$table = $this->db->get_table_name();
		$author_id = $data['author_id'] ?? get_current_user_id();
		$active = $data['active'] ?? false;
		$mode = $data['mode'] ?? 'auto_insert';
		$conditions = $data['conditions'] ?? null;

		$insert_data = [
			'title'      => $data['title'] ?? '',
			'slug'       => $data['slug'] ?? '',
			'type'       => $data['type'] ?? '',
			'code'       => $data['code'] ?? '',
			'active'     => $active ? 1 : 0,
			'mode'       => $mode,
			'conditions' => $conditions,
			'author_id'  => $author_id,
			'created_at' => current_time( 'mysql' ),
			'updated_at' => current_time( 'mysql' ),
		];

		$formats = [
			'%s', '%s', '%s', '%s', '%d', '%s', '%s', '%d', '%s', '%s',
		];

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert( $table, $insert_data, $formats );

		if ( $result ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "[ECS] Snippet created: {$wpdb->insert_id}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return $wpdb->insert_id;
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[ECS] Failed to create snippet: ' . $wpdb->last_error ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
		return false;
	}

	/**
	 * Get a snippet by ID.
	 *
	 * @param int $id Snippet ID.
	 * @return array|null Snippet data or null if not found.
	 */
	public function get( int $id ): ?array {
		global $wpdb;

		$table = $this->db->get_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			ARRAY_A
		);

		return $result ?? null;
	}

	/**
	 * Get a snippet by slug.
	 *
	 * @param string $slug Snippet slug.
	 * @return array|null Snippet data or null if not found.
	 */
	public function get_by_slug( string $slug ): ?array {
		global $wpdb;

		$table = $this->db->get_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE slug = %s", $slug ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			ARRAY_A
		);

		return $result ?? null;
	}

	/**
	 * Update a snippet.
	 *
	 * @param int   $id Snippet ID.
	 * @param array $data Data to update.
	 * @return int Number of rows affected.
	 */
	public function update( int $id, array $data ): int {
		global $wpdb;

		$table = $this->db->get_table_name();

		$update_data = [];
		$formats = [];

		if ( isset( $data['title'] ) ) {
			$update_data['title'] = $data['title'];
			$formats[] = '%s';
		}
		if ( isset( $data['slug'] ) ) {
			$update_data['slug'] = $data['slug'];
			$formats[] = '%s';
		}
		if ( isset( $data['type'] ) ) {
			$update_data['type'] = $data['type'];
			$formats[] = '%s';
		}
		if ( isset( $data['code'] ) ) {
			$update_data['code'] = $data['code'];
			$formats[] = '%s';
		}
		if ( isset( $data['active'] ) ) {
			$update_data['active'] = $data['active'] ? 1 : 0;
			$formats[] = '%d';
		}
		if ( isset( $data['mode'] ) ) {
			$update_data['mode'] = $data['mode'];
			$formats[] = '%s';
		}
		if ( isset( $data['conditions'] ) ) {
			$update_data['conditions'] = $data['conditions'];
			$formats[] = '%s';
		}

		$update_data['updated_at'] = current_time( 'mysql' );
		$formats[] = '%s';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->update(
			$table,
			$update_data,
			[ 'id' => $id ],
			$formats,
			[ '%d' ]
		);

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "[ECS] Snippet updated: {$id}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		return (int) $result;
	}

	/**
	 * Delete a snippet.
	 *
	 * @param int $id Snippet ID.
	 * @return bool True on success, false on failure.
	 */
	public function delete( int $id ): bool {
		global $wpdb;

		$table = $this->db->get_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->delete( $table, [ 'id' => $id ], [ '%d' ] );

		if ( $result ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "[ECS] Snippet deleted: {$id}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return true;
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[ECS] Failed to delete snippet: ' . $wpdb->last_error ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
		return false;
	}

	/**
	 * Get all snippets with optional filters.
	 *
	 * @param array{
	 *   type?: string,
	 *   active?: bool,
	 *   author_id?: int,
	 *   limit?: int,
	 *   offset?: int
	 * } $args Query arguments.
	 *
	 * @return array Array of snippets.
	 */
	public function all( array $args = [] ): array {
		global $wpdb;

		$table = $this->db->get_table_name();

		$limit = $args['limit'] ?? 50;
		$offset = $args['offset'] ?? 0;

		$where = [];
		$where_args = [];

		if ( isset( $args['type'] ) ) {
			$where[] = 'type = %s';
			$where_args[] = $args['type'];
		}

		if ( isset( $args['active'] ) ) {
			$where[] = 'active = %d';
			$where_args[] = $args['active'] ? 1 : 0;
		}

		if ( isset( $args['author_id'] ) ) {
			$where[] = 'author_id = %d';
			$where_args[] = $args['author_id'];
		}

		if ( isset( $args['deleted'] ) ) {
			$where[] = 'deleted = %d';
			$where_args[] = $args['deleted'] ? 1 : 0;
		}

		$where_clause = ! empty( $where ) ? 'WHERE ' . implode( ' AND ', $where ) : '';

		$query = "SELECT * FROM {$table} {$where_clause} ORDER BY created_at DESC LIMIT %d OFFSET %d";

		$where_args[] = $limit;
		$where_args[] = $offset;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			$wpdb->prepare( $query, ...$where_args ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			ARRAY_A
		);

		return $results ?? [];
	}

	/**
	 * Get count of snippets with optional filters.
	 *
	 * @param array{
	 *   type?: string,
	 *   active?: bool,
	 *   author_id?: int
	 * } $args Query arguments.
	 *
	 * @return int Total count of snippets.
	 */
	public function count( array $args = [] ): int {
		global $wpdb;

		$table = $this->db->get_table_name();

		$where = [];
		$where_args = [];

		if ( isset( $args['type'] ) ) {
			$where[] = 'type = %s';
			$where_args[] = $args['type'];
		}

		if ( isset( $args['active'] ) ) {
			$where[] = 'active = %d';
			$where_args[] = $args['active'] ? 1 : 0;
		}

		if ( isset( $args['author_id'] ) ) {
			$where[] = 'author_id = %d';
			$where_args[] = $args['author_id'];
		}

		if ( isset( $args['deleted'] ) ) {
			$where[] = 'deleted = %d';
			$where_args[] = $args['deleted'] ? 1 : 0;
		}

		$where_clause = ! empty( $where ) ? 'WHERE ' . implode( ' AND ', $where ) : '';

		$query = "SELECT COUNT(*) as total FROM {$table} {$where_clause}";

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		if ( ! empty( $where_args ) ) {
			$result = $wpdb->get_var(
				$wpdb->prepare( $query, ...$where_args ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			);
		} else {
			$result = $wpdb->get_var( $query );
		}

		return (int) $result;
	}
}
