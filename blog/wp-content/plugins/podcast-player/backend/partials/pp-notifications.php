<?php
/**
 * Podcast episodes options page template
 *
 * @package Podcast Player
 * @since 1.0.0
 */

?>

<div class="updated notice is-dismissible pp-welcome-notice">
	<p class="intro-msg">
		<?php esc_html_e( 'Thanks for trying/updating Podcast Player.', 'podcast-player' ); ?>
	</p>
	<h4 style="margin-bottom: 0.25em;padding: 5px;">
		<?php esc_html_e( 'What\'s New in this version?.', 'podcast-player' ); ?>
	</h4>
	<ol>
	<li class="premium">
		<?php esc_html_e( 'Podcast player critical error-fix. If you face any issues, please reset the player using ', 'podcast-player' ); ?>
		<a href="<?php echo ( esc_url( admin_url( 'options-general.php?page=pp-options' ) ) ); ?>">
			<?php esc_html_e( 'Podcast Feed Updation Tool', 'podcast-player' ); ?>
		</a>
	</li>
	</ol>

	<h4 style="margin-bottom: 0.25em;padding: 5px;">
		<?php esc_html_e( 'What\'s New in Podcast Player Pro.', 'podcast-player' ); ?>
	</h4>
	<ol>
	<li class="premium">
		<?php esc_html_e( 'Improved social sharing links for facebook, twitter and linkedin. Create effective and better looking social sharing links and bring more traffic to your website. ', 'podcast-player' ); ?>
		<a href="https://vedathemes.com/pp-demo/" target="_blank">
			<?php esc_html_e( 'Podcast Player Demo.', 'podcast-player' ); ?>
		</a>
	</li>
	<li class="premium">
		<?php esc_html_e( 'Apply google fonts to the Podcast Player. Easily choose from more than 900 Google Fonts for your podcast player. Know more about', 'podcast-player' ); ?>
		<a href="https://vedathemes.com/blog/vedaitems/podcast-player-pro/" target="_blank">
			<?php esc_html_e( 'Podcast player pro.', 'podcast-player' ); ?>
		</a>
	</li>

	<div class="common-links">
		<p class="pp-link">
			<a href="https://wordpress.org/support/plugin/podcast-player/" target="_blank">
				<?php esc_html_e( 'Raise a support request', 'podcast-player' ); ?>
			</a>
		</p>
		<p class="pp-link">
			<a href="https://wordpress.org/support/plugin/podcast-player/reviews/" target="_blank">
				<?php esc_html_e( 'Give us 5 stars rating', 'podcast-player' ); ?>
			</a>
		</p>
		<p class="pp-link">
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'pp-dismiss', 'dismiss_admin_notices' ), 'pp-dismiss-' . get_current_user_id() ) ); ?>" target="_parent">
				<?php esc_html_e( 'Dismiss this notice', 'podcast-player' ); ?>
			</a>
		</p>
	</div>
</div>
