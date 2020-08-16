<?php
/**
 * Podcast episodes display template
 *
 * @package Podcast Player
 * @since 1.0.0
 */

if ( 'dark' === $props['sets']['skin'] ) {
	$class = 'pp-dark';
} else {
	$class = '';
}

?>

<div id="pp-podcast-<?php echo absint( $props['inst'] ); ?>" class="pp-podcast <?php echo $class; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="pp-podcast__info pod-info">
		<?php
		if ( $props['img'] && ! $props['sets']['hide-cover-img'] ) {
			printf( '<div class="pod-info__image">%s</div>', $props['img'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$follow_menu = '';
		if ( $props['nav'] && ! $props['sets']['hide-subscribe'] ) {
			$follow_menu = sprintf( '<div class="pod-info__menu">%s</div>', $props['nav'] );
		}

		$pod_title = '';
		if ( $props['title'] ) {
			$pod_title = sprintf( '<div class="pod-header__title">%s</div>', esc_html( $props['title'] ) );
		}

		$excerpt = '';
		if ( $props['desc'] && ! $props['sets']['hide-description'] ) {
			$ppmore = '';
			if ( ! $props['sets']['no-excerpt'] ) {
				$ppmore = sprintf( '<a class="pp-more-link" href="#">%s</a>', esc_html__( 'more...', 'podcast-player' ) );
			}
			$excerpt = sprintf( '<div class="pod-header__desc"><div class="desc-text">%s</div>%s</div>', esc_html( wp_strip_all_tags( $props['desc'] ) ), $ppmore );
		}

		printf( '<div class="pod-info__header pod-header">%s%s%s%s</div>', $pod_title, $excerpt, $props['toggle'], $follow_menu ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
	<div class="pp-podcast__content pod-content">
		<?php if ( 0 === $props['max'] ) : ?>
			<div><?php esc_html_e( 'No Items Found. Try again later.', 'podcast-player' ); ?></div>
		<?php else : ?>
			<div class="pod-content__tabs pod-tabs">
				<button class="pod-tabs__list pod-button active" aria-expanded="false"><?php esc_html_e( 'Episodes', 'podcast-player' ); ?></button>
				<button class="pod-tabs__episode pod-button" aria-expanded="false"><?php esc_html_e( 'Now Playing', 'podcast-player' ); ?></button>
			</div>
			<div class="pod-content__list episode-list">
				<?php if ( ! $props['sets']['hide-search'] ) : ?>
					<div class="episode-list__search">
						<input type="text" placeholder="<?php esc_attr_e( 'Search Episodes', 'podcast-player' ); ?>" />
						<span class="episode-list__search-icon"><?php echo podcast_player_get_icon( [ 'icon' => 'search' ] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<button class="episode-list__clear-search pod-button"><?php echo podcast_player_get_icon( [ 'icon' => 'cross' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="ppjs__offscreen"><?php esc_html_e( 'Clear Search Field', 'podcast-player' ); ?></span></button>
					</div>
				<?php endif; ?>
				<div class="episode-list__wrapper">
					<?php

					// Display maximum 10 episodes at a time.
					$feed_items = array_splice( $props['items'], 0, min( $props['max'], $props['step'] ) );
					foreach ( $feed_items as $key => $item ) :
						$ppe_id = $key + 1;
						$ppe_id = $props['inst'] . '-' . $ppe_id;
						?>
						<div id="ppe-<?php echo $ppe_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="episode-list__entry pod-entry" data-search-term="<?php echo esc_attr( strtolower( $item['title'] ) ); ?>">
							<div class="pod-entry__title"><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo $item['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></div>
							<div class="pod-entry__date"><?php echo $item['date']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						</div>
						<?php
					endforeach;

					// Display "load more" button, if there are more than 10 episodes.
					if ( $props['step'] < $props['max'] && ! $props['sets']['hide-loadmore'] ) {

						// Load more episodes.
						printf(
							'<button class="episode-list__load-more" >%s</button>',
							esc_html__( 'Load More', 'podcast-player' )
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}

					// Search results container.
					if ( ! $props['sets']['hide-search'] ) :
						?>
						<div class="episode-list__search-results episode-search">
							<span class="ppjs__offscreen"><?php esc_html__( 'Search Results placeholder', 'podcast-player' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="pod-content__episode episode-single">
				<div class="episode-single__wrapper">
					<div class="episode-single__title"><?php echo $feed_items[0]['title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

					<?php
					echo podcast_player_markup( $feed_items[0]['src'], $props['inst'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					if ( ! $props['sets']['hide-download'] || ! $props['sets']['hide-social'] ) :
						?>
					<div class="episode-single__social-share ppshare">
						<?php
						$ppurl   = rawurlencode( esc_attr( $feed_items[0]['link'] ) );
						$pptitle = rawurlencode( html_entity_decode( $feed_items[0]['title'], ENT_COMPAT, 'UTF-8' ) );

						if ( ! $props['sets']['hide-download'] ) :
							?>
						<a role="button" class="ppshare__download" href="<?php echo esc_url( $feed_items[0]['src'] ); ?>" title="Download" download="" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'download' ] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="download-text"><?php esc_html_e( 'Download', 'podcast-player' ); ?></span></a>
							<?php
						endif;
						if ( ! $props['sets']['hide-social'] ) :
							?>
						<div class="ppshare__social ppsocial">
							<span class="ppsocial__text"><?php esc_html_e( 'Share on', 'podcast-player' ); ?></span>
							<a class="ppsocial__link ppsocial__facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $ppurl;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'facebook' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'facebook', 'podcast-player' ); ?></span></a>
							<a class="ppsocial__link ppsocial__twitter" href="https://twitter.com/intent/tweet?text=<?php echo $pptitle;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>&amp;url=<?php echo $ppurl; ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'twitter' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'twitter', 'podcast-player' ); ?></span></a>
							<a class="ppsocial__link ppsocial__linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $ppurl;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'linkedin' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'linkedin', 'podcast-player' ); ?></span></a>
							<a class="ppsocial__link ppsocial__email" href="mailto:?subject=<?php echo $pptitle;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>&body=Link: <?php echo $ppurl; ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'email' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'email', 'podcast-player' ); ?></span></a>
						</div>
						<?php endif; ?>
					</div>
						<?php
					endif;
					if ( ! $props['sets']['hide-content'] ) :
						?>
						<div class="episode-single__description">
							<?php echo $feed_items[0]['description']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
					<a class="episode-single__link" href="<?php echo esc_url( $feed_items[0]['link'] ); ?>"><?php esc_html_e( 'View Episode', 'podcast-player' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
