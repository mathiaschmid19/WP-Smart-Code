<?php
/**
 * Uninstall Hook for Edge Code Snippets.
 *
 * Handles cleanup when the plugin is uninstalled.
 *
 * @package ECS
 * @since 1.0.0
 */

// Exit if uninstall.php is accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Plugin uninstall logic.
 *
 * - Remove plugin options
 * - Remove plugin data tables (when fully implemented)
 * - Clear transients
 */

// Delete all plugin options.
delete_option( 'ecs_version' );

// Additional cleanup logic will be added as the plugin develops.
