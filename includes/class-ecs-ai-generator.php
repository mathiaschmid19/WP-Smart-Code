<?php
/**
 * AI Code Generator for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace ECS;

/**
 * AI Code Generator class using Gemini AI.
 *
 * @since 1.0.0
 */
class AI_Generator {
	/**
	 * Gemini AI API endpoint.
	 *
	 * @var string
	 */
    private const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

	/**
	 * API key for Gemini AI.
	 *
	 * @var string
	 */
	private string $api_key;

	/**
	 * Constructor.
	 *
	 * @param string $api_key Gemini AI API key.
	 */
	public function __construct( string $api_key = '' ) {
		$this->api_key = $api_key ?: get_option( 'ecs_ai_api_key', '' );
	}

	/**
	 * Generate code based on natural language description.
	 *
	 * @param string $description Natural language description.
	 * @param string $type        Snippet type (php, js, css, html).
	 * @param array  $context     Additional context (WordPress version, etc.).
	 * @return array Generated code and metadata.
	 */
	public function generate_code( string $description, string $type, array $context = [] ): array {
		if ( empty( $this->api_key ) ) {
			return [
				'success' => false,
				'error'   => 'API key not configured',
			];
		}

		$prompt = $this->build_prompt( $description, $type, $context );
		
        $response = $this->make_api_request( $prompt );
		
		if ( is_wp_error( $response ) ) {
			return [
				'success' => false,
				'error'   => $response->get_error_message(),
			];
		}

        return $this->parse_response( $response, $type );
	}

	/**
	 * Improve existing code.
	 *
	 * @param string $code        Existing code.
	 * @param string $type        Code type.
	 * @param string $improvement Type of improvement requested.
	 * @return array Improved code and suggestions.
	 */
	public function improve_code( string $code, string $type, string $improvement = 'general' ): array {
		$prompt = $this->build_improvement_prompt( $code, $type, $improvement );
		
        $response = $this->make_api_request( $prompt );
		
		if ( is_wp_error( $response ) ) {
			return [
				'success' => false,
				'error'   => $response->get_error_message(),
			];
		}

		return $this->parse_improvement_response( $response );
	}

	/**
	 * Explain existing code.
	 *
	 * @param string $code Code to explain.
	 * @param string $type Code type.
	 * @return array Explanation and suggestions.
	 */
	public function explain_code( string $code, string $type ): array {
		$prompt = $this->build_explanation_prompt( $code, $type );
		
        $response = $this->make_api_request( $prompt );
		
		if ( is_wp_error( $response ) ) {
			return [
				'success' => false,
				'error'   => $response->get_error_message(),
			];
		}

		return $this->parse_explanation_response( $response );
	}

	/**
	 * Build prompt for code generation.
	 *
	 * @param string $description Description.
	 * @param string $type       Code type.
	 * @param array  $context    Additional context.
	 * @return string Generated prompt.
	 */
	private function build_prompt( string $description, string $type, array $context ): string {
		$wp_version = $context['wp_version'] ?? get_bloginfo( 'version' );
		$php_version = $context['php_version'] ?? PHP_VERSION;
		
		$templates = [
			'php' => "You are a WordPress PHP expert. Generate ONLY PHP code for WordPress {$wp_version} (PHP {$php_version}).

STRICT REQUIREMENTS:
- Generate ONLY PHP code, no explanations, no markdown, no comments outside code
- Follow WordPress coding standards and best practices
- Include proper sanitization (sanitize_text_field, wp_kses_post, etc.)
- Include proper escaping (esc_html, esc_attr, esc_url, etc.)
- Add security measures (nonce verification, capability checks)
- Include error handling with try-catch blocks
- Use modern PHP features (typed properties, arrow functions when appropriate)
- Add inline comments explaining complex logic
- Ensure code is production-ready and secure

TASK: {$description}

RESPONSE FORMAT: Return ONLY the PHP code without any markdown formatting, explanations, or additional text.",
			
			'js' => "You are a JavaScript expert specializing in WordPress frontend development. Generate ONLY JavaScript code.

STRICT REQUIREMENTS:
- Generate ONLY JavaScript code, no explanations, no markdown, no comments outside code
- Use modern ES6+ features (const/let, arrow functions, template literals)
- Include proper error handling with try-catch blocks
- Add JSDoc comments for functions
- Ensure cross-browser compatibility
- Follow WordPress JavaScript standards
- Use WordPress-specific functions when appropriate (wp.ajax, wp.hooks, etc.)
- Make code production-ready and optimized

TASK: {$description}

RESPONSE FORMAT: Return ONLY the JavaScript code without any markdown formatting, explanations, or additional text.",
			
			'css' => "You are a CSS expert specializing in WordPress themes and responsive design. Generate ONLY CSS code.

STRICT REQUIREMENTS:
- Generate ONLY CSS code, no explanations, no markdown, no comments outside code
- Use modern CSS features (Grid, Flexbox, Custom Properties, CSS Grid)
- Ensure responsive design with mobile-first approach
- Follow WordPress CSS standards and naming conventions
- Include proper vendor prefixes where needed
- Add helpful comments for complex selectors
- Use CSS custom properties for maintainability
- Ensure accessibility and performance

TASK: {$description}

RESPONSE FORMAT: Return ONLY the CSS code without any markdown formatting, explanations, or additional text.",
			
			'html' => "You are an HTML expert specializing in WordPress content and accessibility. Generate ONLY HTML code.

STRICT REQUIREMENTS:
- Generate ONLY HTML code, no explanations, no markdown, no comments outside code
- Use semantic HTML5 elements (header, nav, main, section, article, aside, footer)
- Ensure accessibility with proper ARIA labels and roles
- Follow WordPress HTML standards and structure
- Include proper meta information and attributes
- Add helpful comments for complex structures
- Ensure cross-browser compatibility
- Make code production-ready and valid

TASK: {$description}

RESPONSE FORMAT: Return ONLY the HTML code without any markdown formatting, explanations, or additional text.",
		];

		return $templates[ $type ] ?? $templates['php'];
	}

	/**
	 * Build improvement prompt.
	 *
	 * @param string $code        Code to improve.
	 * @param string $type        Code type.
	 * @param string $improvement Improvement type.
	 * @return string Generated prompt.
	 */
	private function build_improvement_prompt( string $code, string $type, string $improvement ): string {
		$improvement_types = [
			'security' => 'Focus on security improvements: add sanitization, escaping, nonce verification, capability checks, and input validation.',
			'performance' => 'Focus on performance improvements: optimize queries, reduce database calls, implement caching, and improve efficiency.',
			'readability' => 'Focus on code readability: improve variable names, add comments, refactor complex functions, and improve structure.',
			'error_handling' => 'Focus on error handling: add try-catch blocks, validation, user-friendly error messages, and logging.',
			'general' => 'Provide general improvements: fix bugs, optimize code, improve structure, and add best practices.',
		];

		$focus = $improvement_types[ $improvement ] ?? $improvement_types['general'];

		return "You are a {$type} expert. Improve the following code with focus on: {$focus}

Current code:
```{$type}
{$code}
```

Provide the improved code with explanations of changes made. Format as:
IMPROVED_CODE:
```{$type}
[improved code here]
```

CHANGES:
[explanation of changes]";
	}

	/**
	 * Build explanation prompt.
	 *
	 * @param string $code Code to explain.
	 * @param string $type Code type.
	 * @return string Generated prompt.
	 */
    private function build_explanation_prompt( string $code, string $type ): string {
        return "You are a {$type} expert. Explain the following code thoroughly and practically.

Rules:
- Be clear, direct, and useful — no filler, no intros.
- Use short sections and bullet points.
- Skip any repetition of the code.

CODE TO EXPLAIN (do not repeat it back):
```{$type}
{$code}
```
DELIVER THESE SECTIONS:
1) Summary (1–2 sentences)
2) How it works (detailed explanation)
3) Security, performance, or reliability concerns
4) Improvements – Specific, actionable suggestions

Provide a complete and detailed explanation without any character or length restrictions.";
    }

	/**
	 * Make API request to Gemini.
	 *
	 * @param string $prompt Prompt to send.
	 * @return array|WP_Error Response data or error.
	 */
    private function make_api_request( string $prompt, int $retry_count = 0 ): array|\WP_Error {
        $url = self::GEMINI_API_URL;
		
        $body = [
			'contents' => [
				[
					'parts' => [
						[
							'text' => $prompt,
						],
					],
				],
			],
			'generationConfig' => [
                'temperature' => 0.3,
				'topK' => 40,
				'topP' => 0.95,
                'responseMimeType' => 'text/plain',
			],
			'safetySettings' => [
				[
					'category' => 'HARM_CATEGORY_HARASSMENT',
					'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
				],
				[
					'category' => 'HARM_CATEGORY_HATE_SPEECH',
					'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
				],
				[
					'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
					'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
				],
				[
					'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
					'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
				],
			],
		];

        $response = wp_remote_post(
            $url,
            [
                'headers' => [
                    'Content-Type'   => 'application/json',
                    'x-goog-api-key' => $this->api_key,
                ],
                'body' => wp_json_encode( $body ),
                'timeout' => 120, // Increased to 2 minutes for longer responses
                'blocking' => true,
            ]
        );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
		// API request failed
			
			// Retry logic for timeout and network errors
			if ( $retry_count < 2 && ( 
				strpos( $error_message, 'timeout' ) !== false || 
				strpos( $error_message, 'cURL error 28' ) !== false ||
				strpos( $error_message, 'cURL error' ) !== false
			) ) {
				// Retrying request
				sleep( 2 ); // Wait 2 seconds before retry
				return $this->make_api_request( $prompt, $retry_count + 1 );
			}
			
			// Provide user-friendly error messages
			if ( strpos( $error_message, 'timeout' ) !== false ) {
				return new \WP_Error( 'timeout_error', 'The AI request timed out after multiple attempts. This may be due to a large response or slow network. Please try again with a shorter prompt.' );
			} elseif ( strpos( $error_message, 'cURL error 28' ) !== false ) {
				return new \WP_Error( 'timeout_error', 'The AI request timed out after multiple attempts. Please check your internet connection and try again.' );
			} elseif ( strpos( $error_message, 'cURL error' ) !== false ) {
				return new \WP_Error( 'network_error', 'Network error occurred after multiple attempts. Please check your internet connection and try again.' );
			}
			
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$data = json_decode( $response_body, true );

		// API response received

		if ( $response_code !== 200 ) {
			return new \WP_Error( 'api_error', 'HTTP ' . $response_code . ': ' . $response_body );
		}

		if ( ! $data || isset( $data['error'] ) ) {
			$error_message = $data['error']['message'] ?? 'Unknown API error';
			// Keep critical error logging for API failures
			error_log( '[ECS AI] API Error: ' . $error_message );
			return new \WP_Error( 'api_error', $error_message );
		}

		return $data;
	}

	/**
	 * Parse generation response.
	 *
	 * @param array  $response API response.
	 * @param string $type     Code type.
	 * @return array Parsed response.
	 */
    private function parse_response( array $response, string $type ): array {
        $candidates = $response['candidates'] ?? [];
        
        if ( empty( $candidates ) ) {
            return [
                'success' => false,
                'error'   => 'No response from AI',
            ];
        }

        // Extract content from the new Gemini API response format
        $content = $this->extract_content_from_candidate( $candidates[0] );

        if ( '' === trim( $content ) ) {
            // Debug: Log the full response structure for troubleshooting
            // Empty content received
            return [
                'success' => false,
                'error'   => 'Empty response from AI',
            ];
        }
        
        // Extract code from response
        $code = $this->extract_code_from_response( $content, $type );
        
        return [
            'success' => true,
            'code'    => $code,
            'raw'     => $content,
            'type'    => $type,
        ];
    }

	/**
	 * Parse improvement response.
	 *
	 * @param array $response API response.
	 * @return array Parsed response.
	 */
    private function parse_improvement_response( array $response ): array {
        $candidates = $response['candidates'] ?? [];
        
        if ( empty( $candidates ) ) {
            return [
                'success' => false,
                'error'   => 'No response from AI',
            ];
        }

        // Extract content from the new Gemini API response format
        $content = $this->extract_content_from_candidate( $candidates[0] );
        
        if ( '' === trim( $content ) ) {
            // Debug: Log the full response structure for troubleshooting
            // Empty content received (improvement)
            return [
                'success' => false,
                'error'   => 'Empty response from AI',
            ];
        }
        
        // Extract improved code and changes
        $improved_code = $this->extract_improved_code( $content );
        $changes = $this->extract_changes( $content );
        
        return [
            'success' => true,
            'code'    => $improved_code,
            'changes' => $changes,
            'raw'     => $content,
        ];
    }

	/**
	 * Parse explanation response.
	 *
	 * @param array $response API response.
	 * @return array Parsed response.
	 */
    private function parse_explanation_response( array $response ): array {
        $candidates = $response['candidates'] ?? [];
        
        if ( empty( $candidates ) ) {
            return [
                'success' => false,
                'error'   => 'No response from AI',
            ];
        }

        // Extract content from the new Gemini API response format
        $content = $this->extract_content_from_candidate( $candidates[0] );
        
        if ( '' === trim( $content ) ) {
            // Debug: Log the full response structure for troubleshooting
            // Empty content received (explanation)
            return [
                'success' => false,
                'error'   => 'Empty response from AI',
            ];
        }
        
        return [
            'success' => true,
            'explanation' => $content,
        ];
    }

    /**
     * Extract content from a candidate in the new Gemini API response format.
     *
     * @param array $candidate Candidate from API response.
     * @return string Extracted content.
     */
    private function extract_content_from_candidate( array $candidate ): string {
        // Debug: Log the candidate structure for troubleshooting
        // Processing candidate structure
        
        // Check if response is in the new text/plain format
        if ( isset( $candidate['content']['parts'][0]['text'] ) ) {
            return $candidate['content']['parts'][0]['text'];
        }
        
        // Fallback to old format - concatenate all text parts
        if ( isset( $candidate['content']['parts'] ) && is_array( $candidate['content']['parts'] ) ) {
            return $this->concatenate_text_parts( $candidate['content']['parts'] );
        }
        
        // Check for alternative response formats
        if ( isset( $candidate['content']['text'] ) ) {
            return $candidate['content']['text'];
        }
        
        // If no content found, return empty string
        return '';
    }

    /**
     * Concatenate all text parts from a candidate's parts array.
     *
     * @param array $parts Parts array from response.
     * @return string Concatenated text.
     */
    private function concatenate_text_parts( array $parts ): string {
        $buffer = '';
        foreach ( $parts as $part ) {
            if ( isset( $part['text'] ) && is_string( $part['text'] ) ) {
                $buffer .= (string) $part['text'] . "\n";
            }
        }
        return trim( $buffer );
    }

	/**
	 * Extract code from AI response.
	 *
	 * @param string $content Raw response content.
	 * @param string $type    Code type.
	 * @return string Extracted code.
	 */
	private function extract_code_from_response( string $content, string $type ): string {
		// Look for code blocks
		$pattern = '/```(?:' . $type . ')?\s*\n(.*?)\n```/s';
		
		if ( preg_match( $pattern, $content, $matches ) ) {
			return trim( $matches[1] );
		}

		// If no code blocks, return the content as-is
		return trim( $content );
	}

	/**
	 * Extract improved code from response.
	 *
	 * @param string $content Raw response content.
	 * @return string Improved code.
	 */
	private function extract_improved_code( string $content ): string {
		$pattern = '/IMPROVED_CODE:\s*```[a-z]*\s*\n(.*?)\n```/s';
		
		if ( preg_match( $pattern, $content, $matches ) ) {
			return trim( $matches[1] );
		}

		return '';
	}

	/**
	 * Extract changes explanation from response.
	 *
	 * @param string $content Raw response content.
	 * @return string Changes explanation.
	 */
	private function extract_changes( string $content ): string {
		$pattern = '/CHANGES:\s*\n(.*?)(?:\n\n|\Z)/s';
		
		if ( preg_match( $pattern, $content, $matches ) ) {
			return trim( $matches[1] );
		}

		return '';
	}

	/**
	 * Validate API key.
	 *
	 * @param string $api_key API key to validate.
	 * @return bool True if valid.
	 */
	public function validate_api_key( string $api_key ): bool {
		if ( empty( $api_key ) ) {
			return false;
		}

		$temp_generator = new self( $api_key );
		
		// Use a simple, specific test prompt
		$test_response = $temp_generator->generate_code( 'Create a simple PHP function that returns "Hello World"', 'php' );
		
		// Check if the response was successful and contains actual code
		if ( $test_response['success'] && ! empty( $test_response['code'] ) ) {
			return true;
		}
		
		// Log the error for debugging
		// API key validation failed
		
		return false;
	}

	/**
	 * Get usage statistics.
	 *
	 * @return array Usage stats.
	 */
	public function get_usage_stats(): array {
		return [
			'requests_today' => get_transient( 'ecs_ai_requests_today' ) ?: 0,
			'requests_month' => get_option( 'ecs_ai_requests_month', 0 ),
			'last_request'   => get_option( 'ecs_ai_last_request', '' ),
		];
	}

	/**
	 * Track API usage.
	 *
	 * @return void
	 */
	private function track_usage(): void {
		// Track daily usage
		$today = get_transient( 'ecs_ai_requests_today' ) ?: 0;
		set_transient( 'ecs_ai_requests_today', $today + 1, DAY_IN_SECONDS );
		
		// Track monthly usage
		$month = get_option( 'ecs_ai_requests_month', 0 );
		update_option( 'ecs_ai_requests_month', $month + 1 );
		
		// Track last request
		update_option( 'ecs_ai_last_request', current_time( 'mysql' ) );
	}
}
