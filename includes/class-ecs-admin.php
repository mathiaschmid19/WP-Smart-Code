<?php
/**
 * Admin Handler for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * Admin class for managing the plugin admin interface.
 *
 * @since 1.0.0
 */
class Admin {
	/**
	 * Snippet model instance.
	 *
	 * @var Snippet
	 */
	private Snippet $snippet;

	/**
	 * Constructor.
	 *
	 * @param Snippet $snippet Snippet model instance.
	 */
	public function __construct( Snippet $snippet ) {
		$this->snippet = $snippet;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		// Only initialize in admin area
		if ( ! is_admin() ) {
			return;
		}

		// Register admin menu on admin_menu hook.
		add_action( 'admin_menu', [ $this, 'register_menu' ] );

		// Enqueue admin assets.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Add AJAX handlers
		add_action( 'wp_ajax_ecs_toggle_snippet', [ $this, 'ajax_toggle_snippet' ] );
		add_action( 'wp_ajax_ecs_bulk_action', [ $this, 'ajax_bulk_action' ] );

		// Admin hooks registered
	}

	/**
	 * Register admin menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		$page_hook = add_submenu_page(
			'tools.php',
			__( 'Edge Code Snippets', 'edge-code-snippets' ),
			__( 'Edge Code', 'edge-code-snippets' ),
			'manage_options',
			'edge-code-snippets',
			[ $this, 'render_page' ]
		);

		// Admin menu registered
	}

	/**
	 * Render admin page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'edge-code-snippets' ) );
		}

		// Handle actions
		$this->handle_actions();

		// Make data available in template
		$admin = $this;

		// Include template.
		include ECS_DIR . 'includes/admin/page-snippets.php';
	}

	/**
	 * Get snippets based on current tab.
	 *
	 * @param string $tab Current tab.
	 * @return array
	 */
	private function get_snippets_by_tab( string $tab ): array {
		$args = [ 'limit' => 100 ];
		
		// Check if deleted column exists
		global $wpdb;
		$table_name = $wpdb->prefix . 'ecs_snippets';
		$deleted_column_exists = $wpdb->get_results( $wpdb->prepare(
			"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = 'deleted'",
			DB_NAME,
			$table_name
		) );
		
		switch ( $tab ) {
			case 'active':
				$args['active'] = 1;
				if ( ! empty( $deleted_column_exists ) ) {
					$args['deleted'] = 0; // Exclude deleted snippets
				}
				break;
			case 'inactive':
				$args['active'] = 0;
				if ( ! empty( $deleted_column_exists ) ) {
					$args['deleted'] = 0; // Exclude deleted snippets
				}
				break;
			case 'trash':
				if ( ! empty( $deleted_column_exists ) ) {
					$args['deleted'] = 1;
				} else {
					// No deleted column, return empty array
					return [];
				}
				break;
			case 'all':
			default:
				if ( ! empty( $deleted_column_exists ) ) {
					$args['deleted'] = 0; // Exclude deleted snippets by default
				}
				break;
		}
		
		return $this->snippet->all( $args );
	}

	/**
	 * Get counts for each tab.
	 *
	 * @return array
	 */
	private function get_tab_counts(): array {
		// First, let's check if the deleted column exists
		global $wpdb;
		$table_name = $wpdb->prefix . 'ecs_snippets';
		
		// Check if deleted column exists
		$deleted_column_exists = $wpdb->get_results( $wpdb->prepare(
			"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = 'deleted'",
			DB_NAME,
			$table_name
		) );
		
		// If deleted column doesn't exist, use fallback queries
		if ( empty( $deleted_column_exists ) ) {
			// Fallback: treat all snippets as non-deleted
			$all_count = $this->snippet->count();
			$active_count = $this->snippet->count( [ 'active' => 1 ] );
			$inactive_count = $this->snippet->count( [ 'active' => 0 ] );
			$trash_count = 0; // No deleted snippets yet
		} else {
			// Use the new deleted column
			$all_count = $this->snippet->count( [ 'deleted' => 0 ] );
			$active_count = $this->snippet->count( [ 'active' => 1, 'deleted' => 0 ] );
			$inactive_count = $this->snippet->count( [ 'active' => 0, 'deleted' => 0 ] );
			$trash_count = $this->snippet->count( [ 'deleted' => 1 ] );
		}
		
		return [
			'all' => $all_count,
			'active' => $active_count,
			'inactive' => $inactive_count,
			'trash' => $trash_count,
		];
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook ): void {
		// Only load on our plugin page.
		if ( 'tools_page_edge-code-snippets' !== $hook ) {
			return;
		}

		// Enqueue admin CSS.
		wp_enqueue_style(
			'ecs-admin',
			ECS_URL . 'assets/css/admin.css',
			[],
			ECS_VERSION,
			'all'
		);

		// Enqueue admin JavaScript.
		wp_enqueue_script(
			'ecs-admin',
			ECS_URL . 'assets/js/admin.js',
			[ 'jquery', 'wp-i18n' ],
			ECS_VERSION,
			true
		);

		// Enqueue list table JavaScript.
		wp_enqueue_script(
			'ecs-list-table',
			ECS_URL . 'assets/js/list-table.js',
			[ 'jquery' ],
			ECS_VERSION,
			true
		);

		// Localize script with data.
		wp_localize_script(
			'ecs-admin',
			'ecsData',
			[
				'nonce'    => wp_create_nonce( 'ecs-admin-nonce' ),
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'i18n'     => [
					'confirmDelete' => __( 'Are you sure you want to delete this snippet?', 'edge-code-snippets' ),
					'loading'       => __( 'Loading...', 'edge-code-snippets' ),
					'error'         => __( 'An error occurred.', 'edge-code-snippets' ),
				],
			]
		);

		// Localize list table script.
		wp_localize_script(
			'ecs-list-table',
			'ecsData',
			[
				'nonce'    => wp_create_nonce( 'ecs-admin-nonce' ),
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			]
		);

		// Admin assets enqueued
	}

	/**
	 * Get snippet type label.
	 *
	 * @param string $type Snippet type.
	 * @return string
	 */
	public function get_type_label( string $type ): string {
		$types = [
			'php'  => __( 'PHP', 'edge-code-snippets' ),
			'js'   => __( 'JavaScript', 'edge-code-snippets' ),
			'css'  => __( 'CSS', 'edge-code-snippets' ),
			'html' => __( 'HTML', 'edge-code-snippets' ),
		];

		return $types[ $type ] ?? ucfirst( $type );
	}

	/**
	 * Get snippet status label.
	 *
	 * @param int $active Whether snippet is active.
	 * @return string
	 */
	public function get_status_label( int $active ): string {
		return $active ? __( 'Active', 'edge-code-snippets' ) : __( 'Inactive', 'edge-code-snippets' );
	}

	/**
	 * Get list table instance
	 *
	 * @return Snippets_List_Table
	 */
	public function get_list_table(): Snippets_List_Table {
		$list_table = new Snippets_List_Table();
		$list_table->prepare_items();
		return $list_table;
	}

	/**
	 * Handle actions
	 *
	 * @return void
	 */
	private function handle_actions(): void {
		if ( ! isset( $_GET['action'] ) || ! isset( $_GET['id'] ) ) {
			return;
		}

		$action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
		$id = absint( $_GET['id'] );
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		// Verify nonce
		if ( ! wp_verify_nonce( $nonce, $action . '_snippet_' . $id ) ) {
			wp_die( esc_html__( 'Security check failed.', 'edge-code-snippets' ) );
		}

		switch ( $action ) {
			case 'toggle':
				$this->toggle_snippet( $id );
				break;
			case 'trash':
				$this->trash_snippet( $id );
				break;
			case 'restore':
				$this->restore_snippet( $id );
				break;
			case 'delete':
				$this->delete_snippet( $id );
				break;
		}
	}

	/**
	 * Toggle snippet status
	 *
	 * @param int $id Snippet ID
	 * @return void
	 */
	private function toggle_snippet( int $id ): void {
		$snippet = $this->snippet->get( $id );
		if ( ! $snippet ) {
			wp_die( esc_html__( 'Snippet not found.', 'edge-code-snippets' ) );
		}

		$new_status = $snippet['active'] ? 0 : 1;
		$this->snippet->update( $id, [ 'active' => $new_status ] );

		wp_redirect( admin_url( 'admin.php?page=edge-code-snippets' ) );
		exit;
	}

	/**
	 * Trash snippet
	 *
	 * @param int $id Snippet ID
	 * @return void
	 */
	private function trash_snippet( int $id ): void {
		$this->snippet->update( $id, [ 'deleted' => 1 ] );
		wp_redirect( admin_url( 'admin.php?page=edge-code-snippets' ) );
		exit;
	}

	/**
	 * Restore snippet
	 *
	 * @param int $id Snippet ID
	 * @return void
	 */
	private function restore_snippet( int $id ): void {
		$this->snippet->update( $id, [ 'deleted' => 0 ] );
		wp_redirect( admin_url( 'admin.php?page=edge-code-snippets&view=trash' ) );
		exit;
	}

	/**
	 * Delete snippet permanently
	 *
	 * @param int $id Snippet ID
	 * @return void
	 */
	private function delete_snippet( int $id ): void {
		$this->snippet->delete( $id );
		wp_redirect( admin_url( 'admin.php?page=edge-code-snippets&view=trash' ) );
		exit;
	}

	/**
	 * AJAX handler for toggling snippet status
	 *
	 * @return void
	 */
	public function ajax_toggle_snippet(): void {
		check_ajax_referer( 'ecs-admin-nonce', 'nonce' );

		$id = absint( $_POST['id'] ?? 0 );
		if ( ! $id ) {
			wp_send_json_error( [ 'message' => __( 'Invalid snippet ID.', 'edge-code-snippets' ) ] );
		}

		$snippet = $this->snippet->get( $id );
		if ( ! $snippet ) {
			wp_send_json_error( [ 'message' => __( 'Snippet not found.', 'edge-code-snippets' ) ] );
		}

		$new_status = isset( $_POST['active'] ) ? absint( $_POST['active'] ) : ( $snippet['active'] ? 0 : 1 );
		$result = $this->snippet->update( $id, [ 'active' => $new_status ] );

		if ( $result ) {
			wp_send_json_success( [
				'active' => $new_status,
				'status' => $new_status ? __( 'Active', 'edge-code-snippets' ) : __( 'Inactive', 'edge-code-snippets' ),
			] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to update snippet.', 'edge-code-snippets' ) ] );
		}
	}

	/**
	 * AJAX handler for bulk actions
	 *
	 * @return void
	 */
	public function ajax_bulk_action(): void {
		check_ajax_referer( 'ecs-admin-nonce', 'nonce' );

		$action = sanitize_text_field( $_POST['bulk_action'] ?? '' );
		$snippets = array_map( 'absint', $_POST['snippet'] ?? [] );

		if ( empty( $snippets ) || empty( $action ) ) {
			wp_send_json_error( [ 'message' => __( 'No snippets selected or invalid action.', 'edge-code-snippets' ) ] );
		}

		$updated = 0;
		foreach ( $snippets as $id ) {
			switch ( $action ) {
				case 'activate':
					if ( $this->snippet->update( $id, [ 'active' => 1 ] ) ) {
						$updated++;
					}
					break;
				case 'deactivate':
					if ( $this->snippet->update( $id, [ 'active' => 0 ] ) ) {
						$updated++;
					}
					break;
				case 'trash':
					if ( $this->snippet->update( $id, [ 'deleted' => 1 ] ) ) {
						$updated++;
					}
					break;
				case 'restore':
					if ( $this->snippet->update( $id, [ 'deleted' => 0 ] ) ) {
						$updated++;
					}
					break;
				case 'delete':
					if ( $this->snippet->delete( $id ) ) {
						$updated++;
					}
					break;
			}
		}

		wp_send_json_success( [
			'message' => sprintf(
				/* translators: %d: Number of updated snippets */
				_n( '%d snippet updated.', '%d snippets updated.', $updated, 'edge-code-snippets' ),
				$updated
			),
		] );
	}
}