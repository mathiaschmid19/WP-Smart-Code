<?php
/**
 * Admin Page Template for WP Smart Code.
 *
 * @package ECS
 * @since 1.0.0
 *
 * @var array        $snippets List of snippets.
 * @var int          $total_count Total count of snippets.
 * @var Admin        $admin Admin class instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap ecs-admin-page">
	<!-- Fixed Header with Logo -->
	<div class="ecs-page-header">
		<div class="ecs-header-content">
			<div class="ecs-logo-section">
				<img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiBoZWlnaHQ9IjQ4IiB2aWV3Qm94PSIwIDAgNDggNDgiIHdpZHRoPSI0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PGZpbHRlciBpZD0iYSIgY29sb3ItaW50ZXJwb2xhdGlvbi1maWx0ZXJzPSJzUkdCIiBmaWx0ZXJVbml0cz0idXNlclNwYWNlT25Vc2UiIGhlaWdodD0iNTQiIHdpZHRoPSI0OCIgeD0iMCIgeT0iLTMiPjxmZUZsb29kIGZsb29kLW9wYWNpdHk9IjAiIHJlc3VsdD0iQmFja2dyb3VuZEltYWdlRml4Ii8+PGZlQmxlbmQgaW49IlNvdXJjZUdyYXBoaWMiIGluMj0iQmFja2dyb3VuZEltYWdlRml4IiBtb2RlPSJub3JtYWwiIHJlc3VsdD0ic2hhcGUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVPZmZzZXQgZHk9Ii0zIi8+PGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMS41Ii8+PGZlQ29tcG9zaXRlIGluMj0iaGFyZEFscGhhIiBrMj0iLTEiIGszPSIxIiBvcGVyYXRvcj0iYXJpdGhtZXRpYyIvPjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwLjEgMCIvPjxmZUJsZW5kIGluMj0ic2hhcGUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QxX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVPZmZzZXQgZHk9IjMiLz48ZmVHYXVzc2lhbkJsdXIgc3RkRGV2aWF0aW9uPSIxLjUiLz48ZmVDb21wb3NpdGUgaW4yPSJoYXJkQWxwaGEiIGsyPSItMSIgazM9IjEiIG9wZXJhdG9yPSJhcml0aG1ldGljIi8+PGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMSAwIDAgMCAwIDEgMCAwIDAgMCAxIDAgMCAwIDAuMSAwIi8+PGZlQmxlbmQgaW4yPSJlZmZlY3QxX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QyX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHJlc3VsdD0iaGFyZEFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiLz48ZmVNb3JwaG9sb2d5IGluPSJTb3VyY2VBbHBoYSIgb3BlcmF0b3I9ImVyb2RlIiByYWRpdXM9IjEiIHJlc3VsdD0iZWZmZWN0M19pbm5lclNoYWRvd18zMDUxXzQ2ODc1Ii8+PGZlT2Zmc2V0Lz48ZmVDb21wb3NpdGUgaW4yPSJoYXJkQWxwaGEiIGsyPSItMSIgazM9IjEiIG9wZXJhdG9yPSJhcml0aG1ldGljIi8+PGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMC4wNjI3NDUxIDAgMCAwIDAgMC4wOTQxMTc2IDAgMCAwIDAgMC4xNTY4NjMgMCAwIDAgMC4yNCAwIi8+PGZlQmxlbmQgaW4yPSJlZmZlY3QyX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiIG1vZGU9Im5vcm1hbCIgcmVzdWx0PSJlZmZlY3QzX2lubmVyU2hhZG93XzMwNTFfNDY4NzUiLz48L2ZpbHRlcj48ZmlsdGVyIGlkPSJiIiBjb2xvci1pbnRlcnBvbGF0aW9uLWZpbHRlcnM9InNSR0IiIGZpbHRlclVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaGVpZ2h0PSI0MiIgd2lkdGg9IjM2IiB4PSI2IiB5PSI1LjI1Ij48ZmVGbG9vZCBmbG9vZC1vcGFjaXR5PSIwIiByZXN1bHQ9IkJhY2tncm91bmRJbWFnZUZpeCIvPjxmZUNvbG9yTWF0cml4IGluPSJTb3VyY2VBbHBoYSIgcmVzdWx0PSJoYXJkQWxwaGEiIHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAxMjcgMCIvPjxmZU1vcnBob2xvZ3kgaW49IlNvdXJjZUFscGhhIiBvcGVyYXRvcj0iZXJvZGUiIHJhZGl1cz0iMS41IiByZXN1bHQ9ImVmZmVjdDFfZHJvcFNoYWRvd18zMDUxXzQ2ODc1Ii8+PGZlT2Zmc2V0IGR5PSIyLjI1Ii8+PGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMi4yNSIvPjxmZUNvbXBvc2l0ZSBpbjI9ImhhcmRBbHBoYSIgb3BlcmF0b3I9Im91dCIvPjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAuMTQxMTc2IDAgMCAwIDAgMC4xNDExNzYgMCAwIDAgMCAwLjE0MTE3NiAwIDAgMCAwLjEgMCIvPjxmZUJsZW5kIGluMj0iQmFja2dyb3VuZEltYWdlRml4IiBtb2RlPSJub3JtYWwiIHJlc3VsdD0iZWZmZWN0MV9kcm9wU2hhZG93XzMwNTFfNDY4NzUiLz48ZmVCbGVuZCBpbj0iU291cmNlR3JhcGhpYyIgaW4yPSJlZmZlY3QxX2Ryb3BTaGFkb3dfMzA1MV80Njg3NSIgbW9kZT0ibm9ybWFsIiByZXN1bHQ9InNoYXBlIi8+PC9maWx0ZXI+PGxpbmVhckdyYWRpZW50IGlkPSJjIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjYiIHkxPSIuMDAwMDAxIiB5Mj0iNDgiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZmZmIiBzdG9wLW9wYWNpdHk9Ii4xMiIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjQiIHkxPSI5IiB5Mj0iMzkiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuOCIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuNSIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJlIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI0IiB4Mj0iMjQiIHkxPSIwIiB5Mj0iNDgiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIuMTIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmZmYiIHN0b3Atb3BhY2l0eT0iMCIvPjwvbGluZWFyR3JhZGllbnQ+PGNsaXBQYXRoIGlkPSJmIj48cmVjdCBoZWlnaHQ9IjQ4IiByeD0iMTIiIHdpZHRoPSI0OCIvPjwvY2xpcFBhdGg+PGcgZmlsdGVyPSJ1cmwoI2EpIj48ZyBjbGlwLXBhdGg9InVybCgjZikiPjxyZWN0IGZpbGw9IiMyMjI2MkYiIGhlaWdodD0iNDgiIHJ4PSIxMiIgd2lkdGg9IjQ4Ii8+PHBhdGggZD0ibTAgMGg0OHY0OGgtNDh6IiBmaWxsPSJ1cmwoI2MpIi8+PGcgZmlsdGVyPSJ1cmwoI2IpIj48cGF0aCBkPSJtOSAxMi43NWMwLTIuMDcxMSAxLjY3ODktMy43NSAzLjc1LTMuNzVoNy41YzIuMDcxMSAwIDMuNzUgMS42Nzg5IDMuNzUgMy43NXY3LjM2NDRjLjAwMDIuMDQ1LjAwMDMuMDkwMi4wMDAzLjEzNTYgMCAyLjA2ODEgMS42NzQxIDMuNzQ1MiAzLjc0MSAzLjc1aC4wMDg3IDcuNWMyLjA3MTEgMCAzLjc1IDEuNjc4OSAzLjc1IDMuNzV2Ny41YzAgMi4wNzExLTEuNjc4OSAzLjc1LTMuNzUgMy43NWgtNy41Yy0yLjA3MTEgMC0zLjc1LTEuNjc4OS0zLjc1LTMuNzV2LTcuNWMwLS4wMTA0IDAtLjAyMDguMDAwMS0uMDMxMi0uMDE2Ny0yLjA1NjctMS42ODkyLTMuNzE4OC0zLjc0OTgtMy43MTg4LS4wMDk3IDAtLjAxOTQgMC0uMDI5MSAwaC03LjQ3MTJjLTIuMDcxMSAwLTMuNzUtMS42Nzg5LTMuNzUtMy43NXoiIGZpbGw9InVybCgjZCkiLz48L2c+PC9nPjxyZWN0IGhlaWdodD0iNDYiIHJ4PSIxMSIgc3Ryb2tlPSJ1cmwoI2UpIiBzdHJva2Utd2lkdGg9IjIiIHdpZHRoPSI0NiIgeD0iMSIgeT0iMSIvPjwvZz48L3N2Zz4=" alt="WP Smart Code" class="ecs-logo-icon" />
				<span class="ecs-logo-text">WP Smart Code</span>
			</div>
			<div class="ecs-header-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets-editor' ) ); ?>" class="button button-primary">
					<?php esc_html_e( '+ Add New Snippet', 'edge-code-snippets' ); ?>
				</a>
			</div>
		</div>
	</div>

	<!-- Subheader with Page Title -->
	<div class="ecs-subheader">
		<div class="ecs-subheader-content">
			<h2 class="ecs-page-title"><?php esc_html_e( 'Code Snippets', 'edge-code-snippets' ); ?></h2>
			<p class="ecs-page-description">
				<?php
					if ( $total_count > 0 ) {
						printf(
							// translators: %d is the number of snippets.
							esc_html( _n( 'You have %d snippet', 'You have %d snippets', $total_count, 'edge-code-snippets' ) ),
							intval( $total_count )
						);
					} else {
						esc_html_e( 'Get started by creating your first code snippet', 'edge-code-snippets' );
					}
				?>
			</p>
		</div>
	</div>

	<!-- Content Wrapper -->
	<div class="ecs-content-wrapper">

	<!-- Tab Navigation -->
	<div class="ecs-tab-navigation">
		<ul class="ecs-tab-list">
			<li class="ecs-tab-item <?php echo ( $current_tab === 'all' ) ? 'ecs-tab-active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets&tab=all' ) ); ?>" class="ecs-tab-link">
					<?php esc_html_e( 'All', 'edge-code-snippets' ); ?>
					<span class="ecs-tab-count">(<?php echo intval( $tab_counts['all'] ); ?>)</span>
				</a>
			</li>
			<li class="ecs-tab-item <?php echo ( $current_tab === 'active' ) ? 'ecs-tab-active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets&tab=active' ) ); ?>" class="ecs-tab-link">
					<?php esc_html_e( 'Active', 'edge-code-snippets' ); ?>
					<span class="ecs-tab-count">(<?php echo intval( $tab_counts['active'] ); ?>)</span>
				</a>
			</li>
			<li class="ecs-tab-item <?php echo ( $current_tab === 'inactive' ) ? 'ecs-tab-active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets&tab=inactive' ) ); ?>" class="ecs-tab-link">
					<?php esc_html_e( 'Inactive', 'edge-code-snippets' ); ?>
					<span class="ecs-tab-count">(<?php echo intval( $tab_counts['inactive'] ); ?>)</span>
				</a>
			</li>
			<li class="ecs-tab-item <?php echo ( $current_tab === 'trash' ) ? 'ecs-tab-active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets&tab=trash' ) ); ?>" class="ecs-tab-link">
					<?php esc_html_e( 'Trash', 'edge-code-snippets' ); ?>
					<span class="ecs-tab-count">(<?php echo intval( $tab_counts['trash'] ); ?>)</span>
				</a>
			</li>
		</ul>
	</div>

	<!-- Snippets Table -->
	<table class="widefat striped ecs-snippets-table">
		<thead>
			<tr>
				<th class="column-title"><?php esc_html_e( 'Title', 'edge-code-snippets' ); ?></th>
				<th class="column-slug"><?php esc_html_e( 'Slug', 'edge-code-snippets' ); ?></th>
				<th class="column-type"><?php esc_html_e( 'Type', 'edge-code-snippets' ); ?></th>
				<th class="column-mode"><?php esc_html_e( 'Mode', 'edge-code-snippets' ); ?></th>
				<th class="column-status"><?php esc_html_e( 'Status', 'edge-code-snippets' ); ?></th>
				<th class="column-author"><?php esc_html_e( 'Author', 'edge-code-snippets' ); ?></th>
				<th class="column-actions"><?php esc_html_e( 'Actions', 'edge-code-snippets' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( ! empty( $snippets ) ) {
				foreach ( $snippets as $snippet ) {
					$author = get_user_by( 'ID', intval( $snippet['author_id'] ) );
					$author_name = $author ? $author->display_name : __( 'Unknown', 'edge-code-snippets' );
					$status_class = intval( $snippet['active'] ) ? 'status-active' : 'status-inactive';
					$status_text = intval( $snippet['active'] ) ? __( 'Active', 'edge-code-snippets' ) : __( 'Inactive', 'edge-code-snippets' );
					$type_label = $admin->get_type_label( $snippet['type'] );
					?>
					<tr class="ecs-snippet-row" data-snippet-id="<?php echo intval( $snippet['id'] ); ?>">
						<td class="column-title">
							<strong><?php echo esc_html( $snippet['title'] ); ?></strong>
						</td>
						<td class="column-slug">
							<code><?php echo esc_html( $snippet['slug'] ); ?></code>
						</td>
						<td class="column-type">
							<span class="badge badge-<?php echo esc_attr( $snippet['type'] ); ?>">
								<?php echo esc_html( $type_label ); ?>
							</span>
						</td>
						<td class="column-mode">
							<?php
							$mode = $snippet['mode'] ?? 'auto_insert';
							$mode_label = $mode === 'shortcode' ? __( 'Shortcode', 'edge-code-snippets' ) : __( 'Auto Insert', 'edge-code-snippets' );
							$mode_class = $mode === 'shortcode' ? 'mode-shortcode' : 'mode-auto-insert';
							?>
							<span class="badge <?php echo esc_attr( $mode_class ); ?>">
								<?php echo esc_html( $mode_label ); ?>
							</span>
						</td>
						<td class="column-status">
							<span class="badge <?php echo esc_attr( $status_class ); ?>">
								<?php echo esc_html( $status_text ); ?>
							</span>
						</td>
						<td class="column-author">
							<?php echo esc_html( $author_name ); ?>
						</td>
						<td class="column-actions">
							<div class="ecs-action-buttons">
								<label class="ecs-toggle-switch">
									<input type="checkbox" 
										   class="ecs-toggle-input" 
										   data-snippet-id="<?php echo intval( $snippet['id'] ); ?>"
										   <?php checked( intval( $snippet['active'] ), 1 ); ?>>
									<span class="ecs-toggle-slider"></span>
								</label>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=edge-code-snippets-editor&snippet_id=' . intval( $snippet['id'] ) ) ); ?>" class="button button-small ecs-btn-edit">
									<?php esc_html_e( 'Edit', 'edge-code-snippets' ); ?>
								</a>
								<a href="#" class="button button-small button-link-delete ecs-btn-delete" data-snippet-id="<?php echo intval( $snippet['id'] ); ?>">
									<?php esc_html_e( 'Delete', 'edge-code-snippets' ); ?>
								</a>
							</div>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="7" class="ecs-no-snippets" style="text-align: center; padding: 40px 20px;">
						<div style="color: #666;">
							<span class="dashicons dashicons-editor-code" style="font-size: 48px; width: 48px; height: 48px; opacity: 0.3;"></span>
							<p style="margin: 10px 0 5px 0; font-size: 16px;"><?php esc_html_e( 'No snippets yet', 'edge-code-snippets' ); ?></p>
							<p style="margin: 0; font-size: 14px; color: #999;"><?php esc_html_e( 'Create your first snippet to get started', 'edge-code-snippets' ); ?></p>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<!-- Footer -->
	<div class="ecs-admin-footer">
		<p class="description">
			<?php
			esc_html_e(
				'Manage your code snippets here. You can add, edit, and delete snippets to customize your WordPress site.',
				'edge-code-snippets'
			);
			?>
		</p>
	</div>
</div>

<!-- Add/Edit Snippet Modal -->
<div id="ecs-snippet-modal" class="ecs-modal" style="display: none;">
	<div class="ecs-modal-content">
		<div class="ecs-modal-header">
			<h2 id="ecs-modal-title"><?php esc_html_e( 'Add New Snippet', 'edge-code-snippets' ); ?></h2>
			<button type="button" class="ecs-modal-close" id="ecs-modal-close">
				<span class="dashicons dashicons-no-alt"></span>
			</button>
		</div>
		<div class="ecs-modal-body">
			<form id="ecs-snippet-form">
				<input type="hidden" id="ecs-snippet-id" name="id" value="">
				
				<div class="ecs-form-row">
					<label for="ecs-snippet-title">
						<?php esc_html_e( 'Snippet Title', 'edge-code-snippets' ); ?>
						<span class="required">*</span>
					</label>
					<input type="text" id="ecs-snippet-title" name="title" class="widefat" required>
				</div>

				<div class="ecs-form-row">
					<label for="ecs-snippet-slug">
						<?php esc_html_e( 'Slug', 'edge-code-snippets' ); ?>
					</label>
					<input type="text" id="ecs-snippet-slug" name="slug" class="widefat">
					<p class="description"><?php esc_html_e( 'Leave empty to auto-generate from title', 'edge-code-snippets' ); ?></p>
				</div>

				<div class="ecs-form-row">
					<label for="ecs-snippet-type">
						<?php esc_html_e( 'Snippet Type', 'edge-code-snippets' ); ?>
						<span class="required">*</span>
					</label>
					<select id="ecs-snippet-type" name="type" class="widefat" required>
						<option value=""><?php esc_html_e( 'Select Type...', 'edge-code-snippets' ); ?></option>
						<option value="php">PHP</option>
						<option value="js">JavaScript</option>
						<option value="css">CSS</option>
						<option value="html">HTML</option>
					</select>
				</div>

				<div class="ecs-form-row">
					<label for="ecs-snippet-code">
						<?php esc_html_e( 'Code', 'edge-code-snippets' ); ?>
						<span class="required">*</span>
					</label>
					<textarea id="ecs-snippet-code" name="code" rows="15" class="widefat code" required></textarea>
					<p class="description"><?php esc_html_e( 'Enter your code snippet here', 'edge-code-snippets' ); ?></p>
				</div>

				<div class="ecs-form-row">
					<label>
						<input type="checkbox" id="ecs-snippet-active" name="active" value="1">
						<?php esc_html_e( 'Active (snippet will execute when saved)', 'edge-code-snippets' ); ?>
					</label>
				</div>
			</form>
		</div>
		<div class="ecs-modal-footer">
			<button type="button" class="button" id="ecs-modal-cancel">
				<?php esc_html_e( 'Cancel', 'edge-code-snippets' ); ?>
			</button>
			<button type="button" class="button button-primary" id="ecs-save-snippet">
				<?php esc_html_e( 'Save Snippet', 'edge-code-snippets' ); ?>
			</button>
		</div>
	</div>
</div>

	</div><!-- .ecs-content-wrapper -->
</div><!-- .ecs-admin-page -->

<!-- Loading Overlay -->
<div id="ecs-loading-overlay" style="display: none;">
	<div class="ecs-loading-spinner">
		<span class="spinner is-active"></span>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	// Handle toggle switch changes
	$('.ecs-toggle-input').on('change', function() {
		const $toggle = $(this);
		const snippetId = $toggle.data('snippet-id');
		const isActive = $toggle.is(':checked');
		
		// Disable toggle during request
		$toggle.prop('disabled', true);
		
		// Make AJAX request to toggle snippet status
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'ecs_toggle_snippet',
				snippet_id: snippetId,
				active: isActive ? 1 : 0,
				nonce: ecsAdmin.nonce
			},
			success: function(response) {
				if (response.success) {
					// Update status badge
					const $statusBadge = $toggle.closest('tr').find('.ecs-status-badge');
					if (isActive) {
						$statusBadge.removeClass('ecs-status-inactive').addClass('ecs-status-active').text('Active');
					} else {
						$statusBadge.removeClass('ecs-status-active').addClass('ecs-status-inactive').text('Inactive');
					}
				} else {
					// Revert toggle on error
					$toggle.prop('checked', !isActive);
					alert('Error: ' + (response.data || 'Failed to update snippet status'));
				}
			},
			error: function() {
				// Revert toggle on error
				$toggle.prop('checked', !isActive);
				alert('Error: Failed to update snippet status');
			},
			complete: function() {
				// Re-enable toggle
				$toggle.prop('disabled', false);
			}
		});
	});

});
</script>
