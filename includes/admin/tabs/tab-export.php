<?php
/**
 * Export Tab Content.
 *
 * @package ECS
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get snippet count
global $wpdb;
$table_name = $wpdb->prefix . 'ecs_snippets';
$total_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
$active_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE active = 1" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

?>
<div class="ecs-tools-panel">
	<div class="ecs-tools-header">
		<h2><?php esc_html_e( 'Export Snippets', 'code-snippet' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Export your snippets to a JSON file for backup or migration to another site.', 'code-snippet' ); ?>
		</p>
	</div>

	<div class="ecs-tools-body">
		<div class="ecs-export-stats">
			<div class="ecs-stat-card">
				<div class="ecs-stat-content">
					<h3><?php echo esc_html( $total_count ); ?></h3>
					<p><?php esc_html_e( 'Total Snippets', 'code-snippet' ); ?></p>
				</div>
			</div>
			<div class="ecs-stat-card">
				<div class="ecs-stat-content">
					<h3><?php echo esc_html( $active_count ); ?></h3>
					<p><?php esc_html_e( 'Active Snippets', 'code-snippet' ); ?></p>
				</div>
			</div>
		</div>

		<div class="ecs-export-options">
			<h3><?php esc_html_e( 'Export Options', 'code-snippet' ); ?></h3>
			
			<div class="ecs-export-option-card">
				<div class="ecs-export-option-header">
					<h4>
						<?php esc_html_e( 'Export All Snippets', 'code-snippet' ); ?>
					</h4>
				</div>
				<p class="description">
					<?php esc_html_e( 'Export all snippets (active and inactive) to a single JSON file.', 'code-snippet' ); ?>
				</p>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=ecs_export_snippets&include_inactive=1' ), 'ecs_export_snippets' ) ); ?>" class="button button-primary button-large">
					<?php esc_html_e( 'Export All Snippets', 'code-snippet' ); ?>
				</a>
			</div>

			<div class="ecs-export-option-card">
				<div class="ecs-export-option-header">
					<h4>
						<?php esc_html_e( 'Export Active Only', 'code-snippet' ); ?>
					</h4>
				</div>
				<p class="description">
					<?php esc_html_e( 'Export only active snippets, excluding inactive ones.', 'code-snippet' ); ?>
				</p>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=ecs_export_snippets&include_inactive=0' ), 'ecs_export_snippets' ) ); ?>" class="button button-secondary button-large">
					<?php esc_html_e( 'Export Active Snippets', 'code-snippet' ); ?>
				</a>
			</div>
		</div>

		<div class="ecs-tools-info">
			<h3><?php esc_html_e( 'Export Information', 'code-snippet' ); ?></h3>
			<ul>
				<li><?php esc_html_e( '📦 Exported files are in JSON format', 'code-snippet' ); ?></li>
				<li><?php esc_html_e( '📝 Includes snippet code, settings, and conditions', 'code-snippet' ); ?></li>
				<li><?php esc_html_e( '🔒 Keep exports secure - they contain your code', 'code-snippet' ); ?></li>
				<li><?php esc_html_e( '💾 Regular exports recommended for backup purposes', 'code-snippet' ); ?></li>
				<li><?php esc_html_e( '🔄 Exports are compatible with Edge Code Snippets v1.0+', 'code-snippet' ); ?></li>
			</ul>
		</div>
	</div>
</div>

