<?php
/**
 * Snippet Editor Page Template for WP Smart Code.
 *
 * @package ECS
 * @since 1.0.0
 *
 * @var array|null   $snippet Snippet data (null for new snippet).
 * @var Admin        $admin Admin class instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_new = empty( $snippet );
$snippet_id = $is_new ? 0 : intval( $snippet['id'] );
$title = $is_new ? '' : esc_attr( $snippet['title'] );
$slug = $is_new ? '' : esc_attr( $snippet['slug'] );
$type = $is_new ? 'php' : esc_attr( $snippet['type'] );
$code = $is_new ? '' : esc_textarea( $snippet['code'] );
$active = $is_new ? false : (bool) $snippet['active'];
$page_title = $is_new ? __( 'Add New Snippet', 'code-snippet' ) : __( 'Edit Snippet', 'code-snippet' );

?>
<div class="wrap ecs-admin-page ecs-editor-page">
	<!-- Fixed Header with Logo -->
	<div class="ecs-page-header">
		<div class="ecs-header-content">
			<div class="ecs-logo-section">
				<img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBoZWlnaHQ9IjQ4IiB2aWV3Qm94PSIwIDAgNDggNDgiIHdpZHRoPSI0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PGZpbHRlciBpZD0iYSIgY29sb3ItaW50ZXJwb2xhdGlvbi1maWx0ZXJzPSJzUkdCIiBmaWx0ZXJVbml0cz0idXNlclNwYWNlT25Vc2UiIGhlaWdodD0iNTQiIHdpZHRoPSI0OCIgeD0iMCIgeT0iLTMiPjxmZUZsb29kIGZsb29kLW9wYWNpdHk9IjAiIHJlc3VsdD0iQmFja2dyb3VuZEltYWdlRml4Ii8+PGZlQmxlbmQgaW49IlNvdXJjZUdyYXBoaWMiIGluMj0iQmFja2dyb3VuZEltYWdlRml4IiBtb2RlPSJub3JtYWwiIHJlc3VsdD0ic2hhcGUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVPZmZzZXQgZHk9Ii0zIi8+PGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMS41Ii8+PGZlQ29tcG9zaXRlIGluMj0iaGFyZEFscGhhIiBrMj0iLTEiIGszPSIxIiBvcGVyYXRvcj0iYXJpdGhtZXRpYyIvPjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwLjEgMCIvPjxmZUJsZW5kIGluMj0ic2hhcGUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QxX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVPZmZzZXQgZHk9IjMiLz48ZmVHYXVzc2lhbkJsdXIgc3RkRGV2aWF0aW9uPSIxLjUiLz48ZmVDb21wb3NpdGUgaW4yPSJoYXJkQWxwaGEiIGsyPSItMSIgazM9IjEiIG9wZXJhdG9yPSJhcml0aG1ldGljIi8+PGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMSAwIDAgMCAwIDEgMCAwIDAgMCAxIDAgMCAwIDAuMSAwIi8+PGZlQmxlbmQgaW4yPSJlZmZlY3QxX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QyX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVNb3JwaG9sb2d5IGluPSJTb3VyY2VBbHBoYSIgb3BlcmF0b3I9ImVyb2RlIiByYWRpdXM9IjEiIHJlc3VsdD0iZWZmZWN0M19pbm5lclNoYWRvd18zMDUxXzQ2ODc1Ii8+PGZlT2Zmc2V0Lz48ZmVDb21wb3NpdGUgaW4yPSJoYXJkQWxwaGEiIGsyPSItMSIgazM9IjEiIG9wZXJhdG9yPSJhcml0aG1ldGljIi8+PGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMC4wNjI3NDUxIDAgMCAwIDAgMC4wOTQxMTc2IDAgMCAwIDAgMC4xNTY4NjMgMCAwIDAgMC4yNCAwIi8+PGZlQmxlbmQgaW4yPSJlZmZlY3QyX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QzX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48L2ZpbHRlcj48ZmlsdGVyIGlkPSJiIiBjb2xvci1pbnRlcnBvbGF0aW9uLWZpbHRlcnM9InNSR0IiIGZpbHRlclVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaGVpZ2h0PSI0MiIgd2lkdGg9IjM2IiB4PSI2IiB5PSI1LjI1Ij48ZmVGbG9vZCBmbG9vZC1vcGFjaXR5PSIwIiByZXN1bHQ9IkJhY2tncm91bmRJbWFnZUZpeCIvPjxmZUNvbG9yTWF0cml4IGluPSJTb3VyY2VBbHBoYSIgcmVzdWx0PSJoYXJkQWxwaGEiIHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAxMjcgMCIvPjxmZU1vcnBob2xvZ3kgaW49IlNvdXJjZUFscGhhIiBvcGVyYXRvcj0iZXJvZGUiIHJhZGl1cz0iMS41IiByZXN1bHQ9ImVmZmVjdDFfZHJvcFNoYWRvd18zMDUxXzQ2ODc1Ii8+PGZlT2Zmc2V0IGR5PSIyLjI1Ii8+PGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMi4yNSIvPjxmZUNvbXBvc2l0ZSBpbjI9ImhhcmRBbHBoYSIgb3BlcmF0b3I9Im91dCIvPjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAuMTQxMTc2IDAgMCAwIDAgMC4xNDExNzYgMCAwIDAgMCAwLjE0MTE3NiAwIDAgMCAwLjEgMCIvPjxmZUJsZW5kIGluMj0iQmFja2dyb3VuZEltYWdlRml4IiBtb2RlPSJub3JtYWwiIHJlc3VsdD0iZWZmZWN0MV9kcm9wU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVCbGVuZCBpbj0iU291cmNlR3JhcGhpYyIgaW4yPSJlZmZlY3QxX2Ryb3BTaGFkb3dfMzA1MV80Njg3NSIgbW9kZT0ibm9ybWFsIiByZXN1bHQ9InNoYXBlIi8+PC9maWx0ZXI+PGxpbmVhckdyYWRpZW50IGlkPSJjIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjYiIHkxPSIuMDAwMDAxIiB5Mj0iNDgiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZmZmIiBzdG9wLW9wYWNpdHk9Ii4xMiIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjQiIHkxPSI5IiB5Mj0iMzkiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuOCIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuNSIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJlIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjQiIHkxPSIwIiB5Mj0iNDgiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuMTIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmZmYiIHN0b3Atb3BhY2l0eT0iMCIvPjwvbGluZWFyR3JhZGllbnQ+PGNsaXBQYXRoIGlkPSJmIj48cmVjdCBoZWlnaHQ9IjQ4IiByeD0iMTIiIHdpZHRoPSI0OCIvPjwvY2xpcFBhdGg+PGcgZmlsdGVyPSJ1cmwoI2EpIj48ZyBjbGlwLXBhdGg9InVybCgjZikiPjxyZWN0IGZpbGw9IiMyMjI2MkYiIGhlaWdodD0iNDgiIHJ4PSIxMiIgd2lkdGg9IjQ4Ii8+PHBhdGggZD0ibTAgMGg0OHY0OGgtNDh6IiBmaWxsPSJ1cmwoI2MpIi8+PGcgZmlsdGVyPSJ1cmwoI2IpIj48cGF0aCBkPSJtOSAxMi43NWMwLTIuMDcxMSAxLjY3ODktMy43NSAzLjc1LTMuNzVoNy41YzIuMDcxMSAwIDMuNzUgMS42Nzg5IDMuNzUgMy43NXY3LjM2NDRjLjAwMDIuMDQ1LjAwMDMuMDkwMi4wMDAzLjEzNTYgMCAyLjA2ODEgMS42NzQxIDMuNzQ1MiAzLjc0MSAzLjc1aC4wMDg3IDcuNWMyLjA3MTEgMCAzLjc1IDEuNjc4OSAzLjc1IDMuNzV2Ny41YzAgMi4wNzExLTEuNjc4OSAzLjc1LTMuNzUgMy43NWgtNy41Yy0yLjA3MTEgMC0zLjc1LTEuNjc4OS0zLjc1LTMuNzV2LTcuNWMwLS4wMTA0IDAtLjAyMDguMDAwMS0uMDMxMi0uMDE2Ny0yLjA1NjctMS42ODkyLTMuNzE4OC0zLjc0OTgtMy43MTg4LS4wMDk3IDAtLjAxOTQgMC0uMDI5MSAwaC03LjQ3MTJjLTIuMDcxMSAwLTMuNzUtMS42Nzg5LTMuNzUtMy43NXoiIGZpbGw9InVybCgjZCkiLz48L2c+PC9nPjxyZWN0IGhlaWdodD0iNDYiIHJ4PSIxMSIgc3Ryb2tlPSJ1cmwoI2UpIiBzdHJva2Utd2lkdGg9IjIiIHdpZHRoPSI0NiIgeD0iMSIgeT0iMSIvPjwvZz48L3N2Zz4=" alt="WP Smart Code" class="ecs-logo-icon" />
				<span class="ecs-logo-text">WP Smart Code</span>
			</div>
			<div class="ecs-header-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=code-snippet' ) ); ?>" class="button">
					<?php esc_html_e( '← Back to Snippets', 'code-snippet' ); ?>
				</a>
				<button type="submit" form="ecs-snippet-editor-form" class="button button-primary button-large">
					<?php echo $is_new ? esc_html__( 'Publish', 'code-snippet' ) : esc_html__( 'Update', 'code-snippet' ); ?>
				</button>
			</div>
		</div>
	</div>

	<!-- Main Content Area -->
	<div class="ecs-editor-content">
		<?php
		// Display success message
		if ( isset( $_GET['message'] ) ) {
			$message_type = sanitize_text_field( wp_unslash( $_GET['message'] ) );
			$message_text = '';
			
			switch ( $message_type ) {
				case 'updated':
					$message_text = __( 'Snippet updated successfully.', 'code-snippet' );
					break;
				case 'created':
					$message_text = __( 'Snippet created successfully.', 'code-snippet' );
					break;
			}
			
			if ( ! empty( $message_text ) ) {
				?>
				<div class="notice notice-success is-dismissible ecs-editor-notice">
					<p><?php echo esc_html( $message_text ); ?></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'code-snippet' ); ?></span>
					</button>
				</div>
				<?php
			}
		}
		?>
		
		<form id="ecs-snippet-editor-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'ecs_save_snippet', 'ecs_snippet_nonce' ); ?>
			<input type="hidden" name="action" value="ecs_save_snippet">
			<input type="hidden" name="snippet_id" value="<?php echo esc_attr( $snippet_id ); ?>">

			<!-- Snippet Title Card -->
			<div class="ecs-card">
				<div class="ecs-card-content">
					<div class="ecs-title-row">
						<div class="ecs-title-field">
							<label for="ecs-snippet-title" class="ecs-label">
								<?php esc_html_e( 'Snippet Title', 'code-snippet' ); ?>
							</label>
							<input 
								type="text" 
								id="ecs-snippet-title" 
								name="title" 
								class="ecs-input ecs-input-large" 
								placeholder="<?php esc_attr_e( 'Add title for snippet', 'code-snippet' ); ?>"
								value="<?php echo esc_attr( $title ); ?>"
								required
							>
					</div>
					<div class="ecs-title-toggle">
						<div class="ecs-toggle-wrapper">
							<label class="ecs-toggle-switch">
								<input 
									type="checkbox" 
									id="ecs-snippet-active" 
									name="active" 
									value="1" 
									<?php checked( $active, true ); ?>
									class="ecs-toggle-input"
								>
								<span class="ecs-toggle-slider"></span>
							</label>
							<span class="ecs-toggle-label" id="ecs-status-text">
								<?php echo $active ? esc_html__( 'Active', 'code-snippet' ) : esc_html__( 'Inactive', 'code-snippet' ); ?>
							</span>
						</div>
					</div>
					</div>
				</div>
			</div>

			<!-- Code Editor Card -->
			<div class="ecs-card ecs-card-code">
				<div class="ecs-card-header">
					<h3 class="ecs-card-title">
						<?php esc_html_e( 'Code', 'code-snippet' ); ?>
					</h3>
					<div class="ecs-code-header-actions">
						<select id="ecs-snippet-type" name="type" class="ecs-type-select">
							<option value="php" <?php selected( $type, 'php' ); ?>>PHP</option>
							<option value="js" <?php selected( $type, 'js' ); ?>>JavaScript</option>
							<option value="css" <?php selected( $type, 'css' ); ?>>CSS</option>
							<option value="html" <?php selected( $type, 'html' ); ?>>HTML</option>
						</select>
						<button type="button" id="ecs-toggle-ai-assistant" class="button button-secondary ecs-ai-toggle-btn">
							🤖 AI Assistant
						</button>
					</div>
				</div>
				<div class="ecs-code-editor-wrapper">
					<textarea 
						id="ecs-snippet-code" 
						name="code" 
						class="ecs-code-editor" 
						placeholder="<?php esc_attr_e( 'Enter your code here...', 'code-snippet' ); ?>"
					><?php echo esc_textarea( $code ); ?></textarea>
				</div>
			</div>

			<!-- Insertion Method Card -->
			<div class="ecs-card">
				<div class="ecs-card-header">
					<h3 class="ecs-card-title">
						<?php esc_html_e( 'Insertion', 'code-snippet' ); ?>
					</h3>
				</div>
				<div class="ecs-card-content">
					<p class="ecs-card-description">
						<?php esc_html_e( 'Choose "Auto Insert" if you want the snippet to be automatically executed in one of the locations available. In "Shortcode" mode, the snippet will only be executed where the shortcode is inserted.', 'code-snippet' ); ?>
					</p>
					
					<?php
					// Get current mode
					$current_mode = $snippet['mode'] ?? 'auto_insert';
					?>

					<div class="ecs-insertion-method">
						<label class="ecs-radio-card">
							<input type="radio" name="mode" value="auto_insert" <?php checked( $current_mode, 'auto_insert' ); ?> class="ecs-radio-input">
							<div class="ecs-radio-content">
								<div class="ecs-radio-text">
									<span class="ecs-radio-title"><?php esc_html_e( 'Auto Insert', 'code-snippet' ); ?></span>
									<span class="ecs-radio-description"><?php esc_html_e( 'Automatically execute the snippet in the selected location', 'code-snippet' ); ?></span>
								</div>
							</div>
						</label>

						<label class="ecs-radio-card <?php echo in_array( $type, [ 'css', 'js' ], true ) ? 'ecs-radio-disabled' : ''; ?>">
							<input type="radio" name="mode" value="shortcode" <?php checked( $current_mode, 'shortcode' ); ?> <?php disabled( in_array( $type, [ 'css', 'js' ], true ) ); ?> class="ecs-radio-input">
							<div class="ecs-radio-content">
								<div class="ecs-radio-text">
									<span class="ecs-radio-title"><?php esc_html_e( 'Shortcode', 'code-snippet' ); ?></span>
									<span class="ecs-radio-description">
										<?php if ( in_array( $type, [ 'css', 'js' ], true ) ) : ?>
											<?php esc_html_e( 'Not available for CSS/JavaScript snippets', 'code-snippet' ); ?>
										<?php else : ?>
											<?php
											/* translators: %s: snippet ID or X for new snippets */
											echo esc_html( sprintf( __( 'Only execute when shortcode is inserted: [ecs_snippet id="%s"]', 'code-snippet' ), $is_new ? 'X' : $snippet_id ) );
											?>
										<?php endif; ?>
									</span>
								</div>
							</div>
						</label>
					</div>

					<!-- Location Settings -->
					<div class="ecs-location-settings">
						<label for="ecs-location-preset" class="ecs-label">
							<?php esc_html_e( 'Location', 'code-snippet' ); ?>
						</label>
						<select id="ecs-location-preset" name="location_preset" class="ecs-input">
							<option value="everywhere"><?php esc_html_e( 'Site Wide Header', 'code-snippet' ); ?></option>
							<option value="frontend"><?php esc_html_e( 'Frontend Only', 'code-snippet' ); ?></option>
							<option value="admin"><?php esc_html_e( 'Admin Area Only', 'code-snippet' ); ?></option>
						</select>
					</div>
				</div>
			</div>

			<!-- Device Type Card -->
			<div class="ecs-card">
				<div class="ecs-card-header">
					<h3 class="ecs-card-title">
						<?php esc_html_e( 'Device Type', 'code-snippet' ); ?>
					</h3>
				</div>
				<div class="ecs-card-content">
					<p class="ecs-card-description">
						<?php esc_html_e( 'Limit where you want this snippet to be loaded by device type. By default, snippets are loaded on all devices.', 'code-snippet' ); ?>
					</p>
					
					<div class="ecs-device-options">
						<label class="ecs-radio-card">
							<input type="radio" name="device_type" value="any" checked class="ecs-radio-input">
							<div class="ecs-radio-content">
								<div class="ecs-radio-text">
									<span class="ecs-radio-title"><?php esc_html_e( 'Any device type', 'code-snippet' ); ?></span>
								</div>
							</div>
						</label>

						<label class="ecs-radio-card">
							<input type="radio" name="device_type" value="desktop" class="ecs-radio-input">
							<div class="ecs-radio-content">
								<div class="ecs-radio-text">
									<span class="ecs-radio-title"><?php esc_html_e( 'Desktop only', 'code-snippet' ); ?></span>
								</div>
							</div>
						</label>

						<label class="ecs-radio-card">
							<input type="radio" name="device_type" value="mobile" class="ecs-radio-input">
							<div class="ecs-radio-content">
								<div class="ecs-radio-text">
									<span class="ecs-radio-title"><?php esc_html_e( 'Mobile only', 'code-snippet' ); ?></span>
								</div>
							</div>
						</label>
					</div>
				</div>
			</div>

			<!-- Smart Conditional Logic Card -->
			<div class="ecs-card">
				<div class="ecs-card-header">
					<h3 class="ecs-card-title">
						<?php esc_html_e( 'Smart Conditional Logic', 'code-snippet' ); ?>
					</h3>
				</div>
				<div class="ecs-card-content">
					<p class="ecs-card-description">
						<?php esc_html_e( 'Using conditional logic you can limit the pages where you want the snippet to be auto-inserted.', 'code-snippet' ); ?>
					</p>
					
					<div class="ecs-conditional-logic">
						<input type="checkbox" id="ecs-enable-logic" name="enable_logic" class="ecs-logic-toggle">
						<label for="ecs-enable-logic" class="ecs-logic-label">
							<?php esc_html_e( 'Enable Logic', 'code-snippet' ); ?>
						</label>
					</div>

					<div class="ecs-advanced-conditions" style="display: none;">
						<div class="ecs-condition-group">
							<h4 class="ecs-condition-title"><?php esc_html_e( 'Page Types', 'code-snippet' ); ?></h4>
							<div class="ecs-checkbox-group">
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="front_page" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Front Page', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="home" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Blog Page', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="single" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Single Posts', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="page" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Pages', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="archive" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Archives', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-checkbox-item">
									<input type="checkbox" name="page_types[]" value="search" class="ecs-checkbox-input">
									<span class="ecs-checkbox-text"><?php esc_html_e( 'Search Results', 'code-snippet' ); ?></span>
								</label>
							</div>
						</div>

						<div class="ecs-condition-group">
							<h4 class="ecs-condition-title"><?php esc_html_e( 'Login Status', 'code-snippet' ); ?></h4>
							<div class="ecs-radio-group">
								<label class="ecs-radio-item">
									<input type="radio" name="login_status" value="any" checked class="ecs-radio-input">
									<span class="ecs-radio-text"><?php esc_html_e( 'Any', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-radio-item">
									<input type="radio" name="login_status" value="logged_in" class="ecs-radio-input">
									<span class="ecs-radio-text"><?php esc_html_e( 'Logged In', 'code-snippet' ); ?></span>
								</label>
								<label class="ecs-radio-item">
									<input type="radio" name="login_status" value="logged_out" class="ecs-radio-input">
									<span class="ecs-radio-text"><?php esc_html_e( 'Logged Out', 'code-snippet' ); ?></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Hidden fields for conditions -->
			<input type="hidden" name="conditions" id="ecs-conditions" value="">

		</form>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	// Handle active/inactive toggle
	$('#ecs-snippet-active').on('change', function() {
		const $statusText = $('#ecs-status-text');
		if ($(this).is(':checked')) {
			$statusText.text('<?php echo esc_js( __( 'Active', 'code-snippet' ) ); ?>');
			$statusText.addClass('ecs-status-active');
		} else {
			$statusText.text('<?php echo esc_js( __( 'Inactive', 'code-snippet' ) ); ?>');
			$statusText.removeClass('ecs-status-active');
		}
	});

	// Set initial status text color
	if ($('#ecs-snippet-active').is(':checked')) {
		$('#ecs-status-text').addClass('ecs-status-active');
	}

	// Handle logic toggle
	$('#ecs-enable-logic').on('change', function() {
		if ($(this).is(':checked')) {
			$('.ecs-advanced-conditions').slideDown(200);
		} else {
			$('.ecs-advanced-conditions').slideUp(200);
		}
	});

	// Handle mode change
	$('input[name="mode"]').on('change', function() {
		const mode = $(this).val();
		const $locationSettings = $('.ecs-location-settings');
		
		if (mode === 'shortcode') {
			$locationSettings.hide();
		} else {
			$locationSettings.show();
		}
	});

	// Handle code type change
	$('#ecs-snippet-type').on('change', function() {
		const type = $(this).val();
		const $shortcodeOption = $('input[name="mode"][value="shortcode"]');
		const $shortcodeCard = $shortcodeOption.closest('.ecs-radio-card');
		
		// Disable shortcode option for CSS/JS
		if (type === 'css' || type === 'js') {
			$shortcodeCard.addClass('ecs-radio-disabled');
			$shortcodeOption.prop('disabled', true);
			
			// If shortcode is selected, switch to auto insert
			if ($shortcodeOption.is(':checked')) {
				$('input[name="mode"][value="auto_insert"]').prop('checked', true);
			}
		} else {
			$shortcodeCard.removeClass('ecs-radio-disabled');
			$shortcodeOption.prop('disabled', false);
		}
	});

	// Build conditions JSON before form submission
	$('#ecs-snippet-editor-form').on('submit', function() {
		const conditions = {
			page_types: [],
			login_status: $('input[name="login_status"]:checked').val(),
			device_type: $('input[name="device_type"]:checked').val()
		};

		$('input[name="page_types[]"]:checked').each(function() {
			conditions.page_types.push($(this).val());
		});

		$('#ecs-conditions').val(JSON.stringify(conditions));
	});
});
</script>