<?php
/**
 * Podcast episodes options page template
 *
 * @package Podcast Player
 * @since 1.0.0
 */

?>

<div id="pp-options-page" class="pp-options-page">
	<div class="pp-options-header">
		<div class="pp-options-title">
			<h3><a class="pp-options-title-link" href="https://vedathemes.com/blog/vedaitems/podcast-player-pro/"><?php esc_html_e( 'Podcast Player', 'podcast-player' ); ?></a></h3>
			<span class="pp-version"><?php echo esc_html( PODCAST_PLAYER_VERSION ); ?></span>
		</div>
		<div class="pp-options-links">
			<a class="pp-options-link" href="https://wordpress.org/support/plugin/podcast-player/" target="_blank"><?php esc_html_e( 'Support', 'podcast-player' ); ?></a>
			<a class="pp-options-link" href="https://vedathemes.com/documentation/podcast-player/" target="_blank"><?php esc_html_e( 'Documentation', 'podcast-player' ); ?></a>
			<a class="pp-options-link" href="https://vedathemes.com/documentation/podcast-player-pro/" target="_blank"><?php esc_html_e( 'Pro Docs', 'podcast-player' ); ?></a>
		</div>
	</div>
	<div class="pp-options-main">
		<div class="pp-options-content">
			<?php require_once PODCAST_PLAYER_DIR . '/backend/partials/pp-pro-features.php'; ?>
		</div>
		<div class="pp-options-sidebar">
			<div class="pp-sidebar-section">
				<?php
				if ( function_exists( 'pp_pro_license_options' ) ) {
					pp_pro_license_options();
				} else {
					?>
					<h3 class="pp-premium-title"><?php esc_html_e( 'Try Podcast Player Pro', 'podcast-player' ); ?></h3>
					<a class="pp-premium-link button" href="https://vedathemes.com/blog/vedaitems/podcast-player-pro/" target="_blank"><?php esc_html_e( 'Get It Now', 'podcast-player' ); ?></a>
					<?php
				}
				?>
			</div>
			<div class="pp-sidebar-section">
				<?php $this->podcast_reset_options(); ?>
			</div>
		</div>
	</div>
</div>
