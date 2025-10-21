<?php
/**
 * AI Settings Tab Content for Edge Code Snippets.
 *
 * @package ECS
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current AI settings
$ai_enabled = get_option( 'ecs_ai_enabled', false );
$ai_api_key = get_option( 'ecs_ai_api_key', '' );
?>

<div class="ecs-tools-panel">
	<!-- Hero Card -->
	<div class="ecs-card ecs-tools-hero-card">
		<h2 class="ecs-tools-hero-title"><?php esc_html_e( 'AI Assistant Settings', 'code-snippet' ); ?></h2>
		<p class="ecs-tools-hero-description">
			<?php esc_html_e( 'Configure AI-powered code generation and assistance features for your snippets.', 'code-snippet' ); ?>
		</p>
	</div>

	<!-- Main Settings Card -->
	<div class="ecs-card ecs-tools-main-card">
		<div class="ecs-card-header">
			<h3 class="ecs-card-title"><?php esc_html_e( 'AI Configuration', 'code-snippet' ); ?></h3>
		</div>
		<div class="ecs-card-content">
			<form id="ecs-ai-settings-form" class="ecs-ai-form">
				<!-- Enable AI Toggle -->
				<div class="ecs-form-group">
					<div class="ecs-toggle-wrapper">
						<label class="ecs-toggle-switch">
							<input type="checkbox" id="ecs-ai-enabled" name="ai_enabled" <?php checked( $ai_enabled ); ?> class="ecs-toggle-input">
							<span class="ecs-toggle-slider"></span>
						</label>
						<div class="ecs-toggle-content">
							<label for="ecs-ai-enabled" class="ecs-toggle-label">
								<strong><?php esc_html_e( 'Enable AI Assistant', 'code-snippet' ); ?></strong>
								<span class="ecs-toggle-description"><?php esc_html_e( 'Allow AI-powered code generation and assistance in the snippet editor', 'code-snippet' ); ?></span>
							</label>
						</div>
					</div>
				</div>

				<!-- API Key Section -->
				<div class="ecs-form-group">
					<label for="ecs-ai-api-key" class="ecs-form-label">
						<?php esc_html_e( 'Gemini AI API Key', 'code-snippet' ); ?>
						<span class="ecs-required">*</span>
					</label>
					<div class="ecs-input-group">
						<input 
							type="password" 
							id="ecs-ai-api-key" 
							name="api_key"
							value="<?php echo esc_attr( $ai_api_key ); ?>" 
							class="ecs-input ecs-input-large"
							placeholder="<?php esc_attr_e( 'Enter your Gemini AI API key', 'code-snippet' ); ?>"
						>
						<button type="button" id="ecs-toggle-api-key" class="ecs-input-toggle" title="<?php esc_attr_e( 'Show/Hide API Key', 'code-snippet' ); ?>">
							👁️
						</button>
					</div>
					<p class="ecs-form-description">
						<?php esc_html_e( 'Get your free API key from', 'code-snippet' ); ?> 
						<a href="https://makersuite.google.com/app/apikey" target="_blank" rel="noopener noreferrer" class="ecs-link">
							<?php esc_html_e( 'Google AI Studio', 'code-snippet' ); ?>
						</a>
					</p>
				</div>

				<!-- Save Button -->
				<div class="ecs-form-actions">
					<button type="button" id="ecs-save-ai-settings" class="button button-primary button-hero">
						<?php esc_html_e( 'Save AI Settings', 'code-snippet' ); ?>
					</button>
					<span id="ecs-ai-save-result" class="ecs-test-result"></span>
				</div>
			</form>
		</div>
	</div>

	<!-- AI Features Grid -->
	<div class="ecs-info-cards-grid">
		<!-- AI Features Card -->
		<div class="ecs-card ecs-info-card">
			<div class="ecs-card-header-compact">
				<span class="ecs-card-icon">✨</span>
				<h3 class="ecs-card-title-compact"><?php esc_html_e( 'AI Features', 'code-snippet' ); ?></h3>
			</div>
			<div class="ecs-card-content">
				<ul class="ecs-feature-list">
					<li class="ecs-feature-item">
						<span class="ecs-guideline-icon">✓</span>
						<div class="ecs-feature-content">
							<strong><?php esc_html_e( 'Code Generation', 'code-snippet' ); ?></strong>
							<span class="ecs-feature-description"><?php esc_html_e( 'Generate code snippets from natural language descriptions', 'code-snippet' ); ?></span>
						</div>
					</li>
					<li class="ecs-feature-item">
						<span class="ecs-guideline-icon">✓</span>
						<div class="ecs-feature-content">
							<strong><?php esc_html_e( 'Code Improvement', 'code-snippet' ); ?></strong>
							<span class="ecs-feature-description"><?php esc_html_e( 'Enhance existing code with security, performance, and readability improvements', 'code-snippet' ); ?></span>
						</div>
					</li>
					<li class="ecs-feature-item">
						<span class="ecs-guideline-icon">✓</span>
						<div class="ecs-feature-content">
							<strong><?php esc_html_e( 'Code Explanation', 'code-snippet' ); ?></strong>
							<span class="ecs-feature-description"><?php esc_html_e( 'Get detailed explanations of how your code works', 'code-snippet' ); ?></span>
						</div>
					</li>
					<li class="ecs-feature-item">
						<span class="ecs-guideline-icon">✓</span>
						<div class="ecs-feature-content">
							<strong><?php esc_html_e( 'WordPress Integration', 'code-snippet' ); ?></strong>
							<span class="ecs-feature-description"><?php esc_html_e( 'AI understands WordPress best practices and generates compliant code', 'code-snippet' ); ?></span>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<!-- Usage Guidelines Card -->
		<div class="ecs-card ecs-info-card">
			<div class="ecs-card-header-compact">
				<span class="ecs-card-icon">📋</span>
				<h3 class="ecs-card-title-compact"><?php esc_html_e( 'Usage Guidelines', 'code-snippet' ); ?></h3>
			</div>
			<div class="ecs-card-content">
				<div class="ecs-guidelines-section">
					<h4 class="ecs-guidelines-title"><?php esc_html_e( 'Best Practices', 'code-snippet' ); ?></h4>
					<ul class="ecs-guideline-list">
						<li class="ecs-guideline-item ecs-guideline-success">
							<span class="ecs-guideline-icon">✓</span>
							<?php esc_html_e( 'Be specific in your prompts for better results', 'code-snippet' ); ?>
						</li>
						<li class="ecs-guideline-item ecs-guideline-success">
							<span class="ecs-guideline-icon">✓</span>
							<?php esc_html_e( 'Review generated code before using in production', 'code-snippet' ); ?>
						</li>
						<li class="ecs-guideline-item ecs-guideline-success">
							<span class="ecs-guideline-icon">✓</span>
							<?php esc_html_e( 'Test snippets in a staging environment first', 'code-snippet' ); ?>
						</li>
						<li class="ecs-guideline-item ecs-guideline-warning">
							<span class="ecs-guideline-icon">!</span>
							<?php esc_html_e( 'Use the improvement feature to enhance existing code', 'code-snippet' ); ?>
						</li>
					</ul>
				</div>

				<div class="ecs-examples-section">
					<h4 class="ecs-examples-title"><?php esc_html_e( 'Example Prompts', 'code-snippet' ); ?></h4>
					<div class="ecs-prompt-examples">
						<div class="ecs-prompt-example">
							<div class="ecs-prompt-header">
								<span class="ecs-prompt-language">PHP</span>
							</div>
							<code class="ecs-prompt-code">"Create a custom post type for events with custom fields and admin interface"</code>
						</div>
						<div class="ecs-prompt-example">
							<div class="ecs-prompt-header">
								<span class="ecs-prompt-language">JavaScript</span>
							</div>
							<code class="ecs-prompt-code">"Add smooth scroll animation to navigation links"</code>
						</div>
						<div class="ecs-prompt-example">
							<div class="ecs-prompt-header">
								<span class="ecs-prompt-language">CSS</span>
							</div>
							<code class="ecs-prompt-code">"Create a responsive card layout with hover effects"</code>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	// Toggle API key visibility
	$('#ecs-toggle-api-key').on('click', function() {
		const $input = $('#ecs-ai-api-key');
		const $button = $(this);
		
		if ($input.attr('type') === 'password') {
			$input.attr('type', 'text');
			$button.text('🙈');
		} else {
			$input.attr('type', 'password');
			$button.text('👁️');
		}
	});

	// Handle AI settings save
	$('#ecs-save-ai-settings').on('click', function() {
		const $button = $(this);
		const $result = $('#ecs-ai-save-result');
		const apiKey = $('#ecs-ai-api-key').val();
		const aiEnabled = $('#ecs-ai-enabled').is(':checked');
		
		if (!apiKey) {
			$result.html('<span class="error">Please enter an API key first</span>');
			return;
		}
		
		$button.prop('disabled', true).text('Saving...');
		$result.html('<span class="testing">Saving AI settings...</span>');
		
		$.post(ajaxurl, {
			action: 'ecs_test_ai_api_key',
			api_key: apiKey,
			ai_enabled: aiEnabled ? 1 : 0,
			nonce: '<?php echo esc_js( wp_create_nonce( 'ecs_test_ai_api_key' ) ); ?>'
		}, function(response) {
			if (response.success) {
				$result.html('<span class="success">✓ ' + response.data + '</span>');
			} else {
				$result.html('<span class="error">✗ ' + response.data + '</span>');
			}
		}).fail(function() {
			$result.html('<span class="error">✗ Save failed. Please try again.</span>');
		}).always(function() {
			$button.prop('disabled', false).text('Save AI Settings');
		});
	});
});
</script>
