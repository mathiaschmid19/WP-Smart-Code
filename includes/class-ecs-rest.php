<?php
/**
 * REST API Handler for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * REST API class for managing snippets via REST endpoints.
 *
 * @since 1.0.0
 */
class REST extends WP_REST_Controller {
	/**
	 * Snippet model instance.
	 *
	 * @var Snippet
	 */
	private Snippet $snippet;

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'ecs/v1';

	/**
	 * REST API resource base.
	 *
	 * @var string
	 */
	protected $rest_base = 'snippets';

	/**
	 * Constructor.
	 *
	 * @param Snippet $snippet Snippet model instance.
	 */
	public function __construct( Snippet $snippet ) {
		$this->snippet = $snippet;
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		// List all snippets - GET /ecs/v1/snippets
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);

		// Get single snippet - GET /ecs/v1/snippets/{id}
		// Update snippet - PUT /ecs/v1/snippets/{id}
		// Delete snippet - DELETE /ecs/v1/snippets/{id}
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			[
				'args' => [
					'id' => [
						'description' => __( 'Unique identifier for the snippet.', 'edge-code-snippets' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'context' => $this->get_context_param( [ 'default' => 'view' ] ),
					],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [
						'force' => [
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'edge-code-snippets' ),
						],
					],
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);

		// Get available condition options - GET /ecs/v1/conditions
		register_rest_route(
			$this->namespace,
			'/conditions',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_conditions' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
			]
		);

		// REST routes registered
	}

	/**
	 * Get a collection of snippets.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$params = $request->get_params();

		$args = [
			'limit'  => isset( $params['per_page'] ) ? absint( $params['per_page'] ) : 50,
			'offset' => isset( $params['page'] ) ? ( absint( $params['page'] ) - 1 ) * absint( $params['per_page'] ?? 50 ) : 0,
		];

		// Add filters if provided
		if ( isset( $params['type'] ) && ! empty( $params['type'] ) ) {
			$args['type'] = sanitize_text_field( $params['type'] );
		}

		if ( isset( $params['active'] ) ) {
			$args['active'] = (bool) $params['active'];
		}

		$snippets = $this->snippet->all( $args );
		$total = $this->snippet->count( $args );

		$data = [];
		foreach ( $snippets as $snippet ) {
			$data[] = $this->prepare_item_for_response( $snippet, $request );
		}

		$response = rest_ensure_response( $data );
		$response->header( 'X-WP-Total', $total );
		$response->header( 'X-WP-TotalPages', ceil( $total / $args['limit'] ) );

		return $response;
	}

	/**
	 * Get a single snippet.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {
		$id = (int) $request['id'];
		$snippet = $this->snippet->get( $id );

		if ( ! $snippet ) {
			return new WP_Error(
				'ecs_snippet_not_found',
				__( 'Snippet not found.', 'edge-code-snippets' ),
				[ 'status' => 404 ]
			);
		}

		$data = $this->prepare_item_for_response( $snippet, $request );

		return rest_ensure_response( $data );
	}

	/**
	 * Create a new snippet.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function create_item( $request ) {
		try {
			$params = $request->get_params();

			// Log incoming request for debugging
		// Create item called

			// Validate required fields
			if ( empty( $params['title'] ) ) {
				return new WP_Error(
					'ecs_missing_title',
					__( 'Snippet title is required.', 'edge-code-snippets' ),
					[ 'status' => 400 ]
				);
			}

			if ( empty( $params['code'] ) ) {
				return new WP_Error(
					'ecs_missing_code',
					__( 'Snippet code is required.', 'edge-code-snippets' ),
					[ 'status' => 400 ]
				);
			}

			if ( empty( $params['type'] ) ) {
				return new WP_Error(
					'ecs_missing_type',
					__( 'Snippet type is required.', 'edge-code-snippets' ),
					[ 'status' => 400 ]
				);
			}

			// Validate syntax before saving
			try {
				$syntax_validation = SyntaxValidator::validate( $params['code'], $params['type'] );
				if ( ! $syntax_validation['valid'] ) {
					$error_message = $syntax_validation['error'];
					if ( $syntax_validation['line'] > 0 ) {
						/* translators: %1$s: Error message, %2$d: Line number */
						$error_message = sprintf( __( '%1$s on line %2$d', 'edge-code-snippets' ), $error_message, $syntax_validation['line'] );
					}
					
					// Log syntax error for debugging
				// Syntax validation failed
					
					return new WP_Error(
						'ecs_syntax_error',
						$error_message,
						[ 'status' => 400 ]
					);
				}
			} catch ( \Throwable $e ) {
				// Log validation error
				// Syntax validation exception
				
				return new WP_Error(
					'ecs_validation_error',
					__( 'Failed to validate snippet syntax: ', 'edge-code-snippets' ) . $e->getMessage(),
					[ 'status' => 500 ]
				);
			}

			// Generate slug if not provided
			if ( empty( $params['slug'] ) ) {
				$params['slug'] = sanitize_title( $params['title'] );
			}

			// Check if slug already exists
			$existing = $this->snippet->get_by_slug( $params['slug'] );
			if ( $existing ) {
				return new WP_Error(
					'ecs_slug_exists',
					__( 'A snippet with this slug already exists.', 'edge-code-snippets' ),
					[ 'status' => 409 ]
				);
			}

			// Prepare data for insertion
			$data = [
				'title'      => sanitize_text_field( $params['title'] ),
				'slug'       => sanitize_title( $params['slug'] ),
				'type'       => sanitize_text_field( $params['type'] ),
				'code'       => $params['code'], // Don't sanitize code content
				'active'     => isset( $params['active'] ) ? (bool) $params['active'] : false,
				'mode'       => isset( $params['mode'] ) ? sanitize_text_field( $params['mode'] ) : 'auto_insert',
				'conditions' => isset( $params['conditions'] ) ? wp_json_encode( $params['conditions'] ) : null,
				'author_id'  => get_current_user_id(),
			];

			$id = $this->snippet->create( $data );

			if ( ! $id ) {
				// Log detailed error information
				// Database error - keep this for critical errors
				global $wpdb;
				error_log( '[ECS REST] Database error: ' . $wpdb->last_error );
				
				return new WP_Error(
					'ecs_create_failed',
					__( 'Failed to create snippet.', 'edge-code-snippets' ),
					[ 'status' => 500 ]
				);
			}

			$snippet = $this->snippet->get( $id );
			$response = $this->prepare_item_for_response( $snippet, $request );

		// Snippet created successfully

			return rest_ensure_response( $response );
		} catch ( \Throwable $e ) {
			// Catch any unexpected errors
		// Keep critical error logging for unexpected errors
		error_log( '[ECS REST] Unexpected error in create_item: ' . $e->getMessage() );
			
			return new WP_Error(
				'ecs_unexpected_error',
				__( 'An unexpected error occurred: ', 'edge-code-snippets' ) . $e->getMessage(),
				[ 'status' => 500 ]
			);
		}
	}

	/**
	 * Update a snippet.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update_item( $request ) {
		$id = (int) $request['id'];
		$snippet = $this->snippet->get( $id );

		if ( ! $snippet ) {
			return new WP_Error(
				'ecs_snippet_not_found',
				__( 'Snippet not found.', 'edge-code-snippets' ),
				[ 'status' => 404 ]
			);
		}

		$params = $request->get_params();
		$data = [];

		// Update only provided fields
		if ( isset( $params['title'] ) ) {
			$data['title'] = sanitize_text_field( $params['title'] );
		}

		if ( isset( $params['slug'] ) ) {
			$new_slug = sanitize_title( $params['slug'] );
			// Check if new slug conflicts with another snippet
			if ( $new_slug !== $snippet['slug'] ) {
				$existing = $this->snippet->get_by_slug( $new_slug );
				if ( $existing && intval( $existing['id'] ) !== $id ) {
					return new WP_Error(
						'ecs_slug_exists',
						__( 'A snippet with this slug already exists.', 'edge-code-snippets' ),
						[ 'status' => 409 ]
					);
				}
			}
			$data['slug'] = $new_slug;
		}

		if ( isset( $params['type'] ) ) {
			$data['type'] = sanitize_text_field( $params['type'] );
		}

		if ( isset( $params['code'] ) ) {
			$data['code'] = $params['code']; // Don't sanitize code content
			
			// Validate syntax before updating
			$type = $data['type'] ?? $snippet['type'];
			$syntax_validation = SyntaxValidator::validate( $params['code'], $type );
			if ( ! $syntax_validation['valid'] ) {
				$error_message = $syntax_validation['error'];
				if ( $syntax_validation['line'] > 0 ) {
					/* translators: %1$s: Error message, %2$d: Line number */
					$error_message = sprintf( __( '%1$s on line %2$d', 'edge-code-snippets' ), $error_message, $syntax_validation['line'] );
				}
				
				return new WP_Error(
					'ecs_syntax_error',
					$error_message,
					[ 'status' => 400 ]
				);
			}
		}

		if ( isset( $params['active'] ) ) {
			$data['active'] = (bool) $params['active'];
		}

		if ( isset( $params['mode'] ) ) {
			$data['mode'] = sanitize_text_field( $params['mode'] );
		}

		if ( isset( $params['conditions'] ) ) {
			$data['conditions'] = wp_json_encode( $params['conditions'] );
		}

		$result = $this->snippet->update( $id, $data );

		if ( ! $result ) {
			return new WP_Error(
				'ecs_update_failed',
				__( 'Failed to update snippet.', 'edge-code-snippets' ),
				[ 'status' => 500 ]
			);
		}

		$updated_snippet = $this->snippet->get( $id );
		$response = $this->prepare_item_for_response( $updated_snippet, $request );

		return rest_ensure_response( $response );
	}

	/**
	 * Delete a snippet.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function delete_item( $request ) {
		$id = (int) $request['id'];
		$snippet = $this->snippet->get( $id );

		if ( ! $snippet ) {
			return new WP_Error(
				'ecs_snippet_not_found',
				__( 'Snippet not found.', 'edge-code-snippets' ),
				[ 'status' => 404 ]
			);
		}

		$previous = $this->prepare_item_for_response( $snippet, $request );
		$result = $this->snippet->delete( $id );

		if ( ! $result ) {
			return new WP_Error(
				'ecs_delete_failed',
				__( 'Failed to delete snippet.', 'edge-code-snippets' ),
				[ 'status' => 500 ]
			);
		}

		$response = new WP_REST_Response();
		$response->set_data(
			[
				'deleted'  => true,
				'previous' => $previous,
			]
		);

		return $response;
	}

	/**
	 * Prepare snippet for response.
	 *
	 * @param array           $item    Snippet data.
	 * @param WP_REST_Request $request Request object.
	 * @return array Prepared snippet data.
	 */
	public function prepare_item_for_response( $item, $request ): array {
		$data = [
			'id'         => intval( $item['id'] ),
			'title'      => $item['title'],
			'slug'       => $item['slug'],
			'type'       => $item['type'],
			'code'       => $item['code'],
			'active'     => (bool) intval( $item['active'] ),
			'mode'       => $item['mode'] ?? 'auto_insert',
			'conditions' => $item['conditions'] ? json_decode( $item['conditions'], true ) : null,
			'author_id'  => intval( $item['author_id'] ),
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
		];

		return $data;
	}

	/**
	 * Check permissions for reading snippets.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return bool|WP_Error True if the request has permission, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'ecs_rest_forbidden',
				__( 'Sorry, you are not allowed to view snippets.', 'edge-code-snippets' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Check permissions for reading a single snippet.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return bool|WP_Error True if the request has permission, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Check permissions for creating snippets.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return bool|WP_Error True if the request has permission, WP_Error object otherwise.
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'ecs_rest_forbidden_create',
				__( 'Sorry, you are not allowed to create snippets.', 'edge-code-snippets' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Check permissions for updating snippets.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return bool|WP_Error True if the request has permission, WP_Error object otherwise.
	 */
	public function update_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'ecs_rest_forbidden_update',
				__( 'Sorry, you are not allowed to update snippets.', 'edge-code-snippets' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Check permissions for deleting snippets.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return bool|WP_Error True if the request has permission, WP_Error object otherwise.
	 */
	public function delete_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'ecs_rest_forbidden_delete',
				__( 'Sorry, you are not allowed to delete snippets.', 'edge-code-snippets' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Get collection parameters.
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params(): array {
		return [
			'page'     => [
				'description'       => __( 'Current page of the collection.', 'edge-code-snippets' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
				'minimum'           => 1,
			],
			'per_page' => [
				'description'       => __( 'Maximum number of items to be returned in result set.', 'edge-code-snippets' ),
				'type'              => 'integer',
				'default'           => 50,
				'minimum'           => 1,
				'maximum'           => 100,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			],
			'type'     => [
				'description'       => __( 'Filter by snippet type.', 'edge-code-snippets' ),
				'type'              => 'string',
				'enum'              => [ 'php', 'js', 'css', 'html' ],
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			],
			'active'   => [
				'description'       => __( 'Filter by active status.', 'edge-code-snippets' ),
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'validate_callback' => 'rest_validate_request_arg',
			],
		];
	}

	/**
	 * Get available condition options.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response Response object.
	 */
	public function get_conditions( $request ) {
		$conditions = [
			'page_types'     => Conditions::get_page_types(),
			'post_types'     => get_post_types( [ 'public' => true ], 'objects' ),
			'user_roles'     => Conditions::get_user_roles(),
			'device_types'   => Conditions::get_device_types(),
			'login_statuses' => Conditions::get_login_statuses(),
		];

		// Format post types for easier consumption
		$formatted_post_types = [];
		foreach ( $conditions['post_types'] as $post_type ) {
			$formatted_post_types[ $post_type->name ] = $post_type->label;
		}
		$conditions['post_types'] = $formatted_post_types;

		return rest_ensure_response( $conditions );
	}
}

