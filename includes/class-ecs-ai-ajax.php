<?php
/**
 * AI AJAX Handler for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * AI AJAX Handler class.
 *
 * @since 1.0.0
 */
class AI_Ajax {
	/**
	 * AI Generator instance.
	 *
	 * @var AI_Generator
	 */
	private AI_Generator $ai_generator;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->ai_generator = new AI_Generator();
	}

	/**
	 * Initialize AJAX handlers.
	 */
	public function init(): void {
		add_action( 'wp_ajax_ecs_ai_generate_code', [ $this, 'handle_generate_code' ] );
		add_action( 'wp_ajax_ecs_ai_improve_code', [ $this, 'handle_improve_code' ] );
		add_action( 'wp_ajax_ecs_ai_explain_code', [ $this, 'handle_explain_code' ] );
		add_action( 'wp_ajax_ecs_test_ai_api_key', [ $this, 'handle_test_api_key' ] );
	}

	/**
	 * Handle code generation AJAX request.
	 */
	public function handle_generate_code(): void {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'ecs_ai_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$prompt = sanitize_textarea_field( $_POST['prompt'] ?? '' );
		$type = sanitize_text_field( $_POST['type'] ?? 'php' );

		if ( empty( $prompt ) ) {
			wp_send_json_error( 'Prompt is required' );
		}

		if ( ! in_array( $type, [ 'php', 'js', 'css', 'html' ], true ) ) {
			wp_send_json_error( 'Invalid code type' );
		}

		$context = [
			'wp_version' => get_bloginfo( 'version' ),
			'php_version' => PHP_VERSION,
		];

		$result = $this->ai_generator->generate_code( $prompt, $type, $context );

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result['error'] ?? 'Failed to generate code' );
		}
	}

	/**
	 * Handle code improvement AJAX request.
	 */
	public function handle_improve_code(): void {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'ecs_ai_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$code = wp_unslash( $_POST['code'] ?? '' );
		$type = sanitize_text_field( $_POST['type'] ?? 'php' );
		$improvement = sanitize_text_field( $_POST['improvement'] ?? 'general' );

		if ( empty( $code ) ) {
			wp_send_json_error( 'Code is required' );
		}

		if ( ! in_array( $type, [ 'php', 'js', 'css', 'html' ], true ) ) {
			wp_send_json_error( 'Invalid code type' );
		}

		$result = $this->ai_generator->improve_code( $code, $type, $improvement );

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result['error'] ?? 'Failed to improve code' );
		}
	}

	/**
	 * Handle code explanation AJAX request.
	 */
	public function handle_explain_code(): void {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'ecs_ai_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$code = wp_unslash( $_POST['code'] ?? '' );
		$type = sanitize_text_field( $_POST['type'] ?? 'php' );

		if ( empty( $code ) ) {
			wp_send_json_error( 'Code is required' );
		}

		if ( ! in_array( $type, [ 'php', 'js', 'css', 'html' ], true ) ) {
			wp_send_json_error( 'Invalid code type' );
		}

		$result = $this->ai_generator->explain_code( $code, $type );

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result['error'] ?? 'Failed to explain code' );
		}
	}

	/**
	 * Handle AI settings save AJAX request.
	 */
	public function handle_test_api_key(): void {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'ecs_test_ai_api_key' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		$api_key = sanitize_text_field( $_POST['api_key'] ?? '' );
		$ai_enabled = isset( $_POST['ai_enabled'] ) ? (bool) $_POST['ai_enabled'] : false;

		if ( empty( $api_key ) ) {
			wp_send_json_error( 'API key is required' );
		}

		// Save all AI settings
		update_option( 'ecs_ai_api_key', $api_key );
		update_option( 'ecs_ai_enabled', $ai_enabled );
		
		wp_send_json_success( 'AI settings saved successfully! You can now use the AI Assistant.' );
	}
}
