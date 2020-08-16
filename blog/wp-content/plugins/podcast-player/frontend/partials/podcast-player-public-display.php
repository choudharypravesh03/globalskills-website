<?php
/**
 * Podcast episodes display template
 *
 * @package Podcast Player
 * @since 1.0.0
 */

?>
<div id="pp-podcast-<?php echo absint( $props['inst'] ); ?>" class="<?php echo esc_html( $wrapper_class ); ?>">
	<?php
	$pp_show_header = false;
	if ( ! $props['sets']['hide-cover-img'] || ! $props['sets']['hide-title'] || ! $props['sets']['hide-subscribe'] || ! $props['sets']['hide-description'] ) {
		$pp_show_header = true;
	}
	?>
	<?php if ( ! $props['sets']['hide-header'] && $pp_show_header ) : ?>
	<div class="pp-podcast__info pod-info">
		<?php
		if ( '' === $props['sets']['display-style'] && ! $props['sets']['header-default'] ) {
			$this->header_info_top( $props );
		}

		$pod_img = '';
		if ( $props['img'] && ! $props['sets']['hide-cover-img'] ) {
			$pod_img = sprintf( '<div class="pod-header__image">%s</div>', $props['img'] );
		}

		$pod_title     = '';
		$podcast_title = $props['title'] ? esc_html( $props['title'] ) : '';
		if ( $podcast_title && ! $props['sets']['hide-title'] ) {
			$pod_title = sprintf( '<div class="pod-items__title">%s</div>', $podcast_title );
		}

		$follow_menu = '';
		if ( $props['nav'] && ! $props['sets']['hide-subscribe'] ) {
			$follow_menu = sprintf( '<div class="pod-items__menu">%s</div>', $props['nav'] );
		}

		$excerpt = '';
		if ( $props['desc'] && ! $props['sets']['hide-description'] ) {
			$excerpt = sprintf( '<div class="pod-items__desc">%s</div>', esc_html( wp_strip_all_tags( $props['desc'] ) ) );
		}

		printf( '<div class="pod-info__header pod-header">%s<div class="pod-header__items pod-items">%s%s%s</div></div>', $pod_img, $pod_title, $excerpt, $follow_menu ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
	<?php endif; ?>
	<div class="pp-podcast__content pod-content">
		<div class="pp-podcast__single">
			<div class="pp-podcast__player">
				<div class="pp-player__episode">
					<?php echo podcast_player_markup( $props['items'][0]['src'], $props['inst'] ); // 	phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( $amsg ) : ?>
					<div class="pp-player__amsg">
						<?php echo podcast_player_markup( $amsg, $props['inst'] . '-amsg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="pod-content__social-share ppshare">
				<?php
				$ppitem = $props['items'][0];

				if ( ! $props['sets']['hide-download'] ) :
					$this->download_link( $ppitem['src'] );
				endif;

				if ( ! $props['sets']['hide-social'] ) :
					$this->social_sharing( $ppitem, $fprint );
				endif;
				?>
			</div>
			<div class="pod-content__episode episode-single">
				<?php
				printf(
					'<button aria-expanded="false" class="episode-single__close" ><span class="ppjs__offscreen">%1$s</span>%2$s</button>',
					esc_html__( 'Close Single Episode', 'podcast-player' ),
					podcast_player_get_icon( [ 'icon' => 'pp-x' ] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
				<div class="episode-single__wrapper">
					<div class="episode-single__header">
						<div class="episode-single__title"><?php echo esc_html( $ppitem['title'] ); ?></div>
						<?php if ( $ppitem['author'] && ! $props['sets']['hide-author'] ) : ?>
						<div class="episode-single__author">
							<span class="byname"><?php esc_html_e( 'by', 'podcast-player' ); ?></span>
							<span class="single-author"><?php echo esc_html( $ppitem['author'] ); ?></span>
						</div>
						<?php endif; ?>
					</div>
					<?php if ( $ppitem['description'] && ! $props['sets']['hide-content'] ) : ?>
						<div class="episode-single__description">
							<?php echo $ppitem['description']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if ( 1 < $props['max'] ) : ?>
		<div class="pod-content__list episode-list">
			<?php
			if ( ! $props['sets']['hide-search'] ) :
				$this->search_field();
			endif;
			?>
			<div class="episode-list__wrapper">
				<?php
				// Display maximum 10 episodes at a time.
				$feed_items = array_splice( $props['items'], 0, min( $props['max'], $props['step'] ) );
				foreach ( $feed_items as $key => $item ) :
					$ppe_id = $key + 1;
					$ppe_id = $props['inst'] . '-' . $ppe_id;

					$pp_show_img = false;
					if ( $this->is_style_support( $props['sets']['display-style'], 'thumbnail' ) ) {
						$pp_show_img = true;
					}

					if ( $this->is_style_support( $props['sets']['display-style'], 'playbtn' ) ) {
						$pp_show_img = true;
					}

					$pp_show_excerpt = false;
					if ( $this->is_style_support( $props['sets']['display-style'], 'excerpt' ) ) {
						$pp_show_excerpt = true;
					}

					if ( ! $props['sets']['excerpt-length'] ) {
						$pp_show_excerpt = false;
					}
					?>
					<div id="ppe-<?php echo esc_html( $ppe_id ); ?>" class="episode-list__entry pod-entry" data-search-term="<?php echo esc_attr( strtolower( $item['title'] ) ); ?>">
						<?php
						if ( $pp_show_img ) :
							$pp_img = $item['featured'] ? $item['featured'] : ( $props['imgurl'] ? $props['imgurl'] : '' );
							$pp_img = $pp_img ? sprintf( '<div class="pod-entry__thumb"><img class="pod-entry__image" src="%s"></div>', esc_url( $pp_img ) ) : '';

							if ( $this->is_style_support( $props['sets']['display-style'], 'playbtn' ) ) {
								$pp_img = '';
							}

							$play_icon = sprintf( '<div class="pod-entry__play"><span class="ppjs__offscreen">%s</span>%s%s</div>', esc_html__( 'Episode play icon', 'podcast-player' ), podcast_player_get_icon( [ 'icon' => 'pp-play' ] ), podcast_player_get_icon( [ 'icon' => 'pp-pause' ] ) );
							printf( '<div class="pod-entry__featured">%s%s</div>', $play_icon, $pp_img ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						endif;

						$title_cls = 'post' === $props['sets']['fetch-method'] ? 'fetch-post-title' : '';
						?>
						<div class="pod-entry__content">
							<div class="pod-entry__title"><a class="<?php echo esc_attr( $title_cls ); ?>" href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></div>

							<?php if ( $pp_show_excerpt && isset( $item['description'] ) && $item['description'] ) : ?>
								<div class="pod-entry__excerpt">
									<?php echo wp_trim_words( wp_strip_all_tags( $item['description'], true ), absint( $props['sets']['excerpt-length'] ) ); ?>
								</div>
							<?php endif; ?>
							<div class="pod-entry__date"><?php echo esc_html( $item['date'] ); ?></div>

							<?php if ( $item['author'] && ! $props['sets']['hide-author'] ) : ?>
								<div class="pod-entry__author"><?php echo esc_html( $item['author'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
					<?php
				endforeach;

				// Display "load more" button, if there are more than 10 episodes.
				if ( $props['step'] < $props['max'] && ! $props['sets']['hide-loadmore'] ) {

					// Load more episodes.
					printf(
						'<div class="lm-button-wrapper"><button class="episode-list__load-more" >%s</button></div>',
						esc_html__( 'Load More', 'podcast-player' )
					);
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
		<?php endif; ?>
	</div>
</div>
