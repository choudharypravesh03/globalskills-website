<?php
/**
 * Podcast player display class.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player;

/**
 * Display podcast player instance.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Display {

	/**
	 * Holds all display styles supported items.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $style_supported = [];

	/**
	 * Display init method.
	 *
	 * @param array $props podcast player display props.
	 *
	 * @since  1.0.0
	 */
	protected function render( $props ) {
		if ( ! $props['max'] ) {
			printf( '<div class="error-no-items">%s</div>', esc_html__( 'No Items Found. Try again later.', 'podcast-player' ) );
			return;
		}

		$args = $props['sets'];
		$amsg = '';
		if ( isset( $args['audio-msg'] ) && $args['audio-msg'] ) {
			$type = wp_check_filetype( $args['audio-msg'], wp_get_mime_types() );
			if ( in_array( strtolower( $type['ext'] ), wp_get_audio_extensions(), true ) ) {
				$amsg = $args['audio-msg'];
			}
		}

		$fprint = false;
		if ( 'feed' === $args['fetch-method'] && ( isset( $args['url'] ) && $args['url'] ) ) {
			$fprint = md5( $args['url'] );
		}

		$this->generate_inline_css( $props );
		$wrapper_class = $this->get_wrapper_classes( $props, $fprint );
		include PODCAST_PLAYER_DIR . 'frontend/partials/podcast-player-public-display.php';
	}

	/**
	 * Display Podcast wrapper classes.
	 *
	 * @param array $props podcast player display props.
	 * @param str   $key   Podcast player unique key.
	 *
	 * @since  1.0.0
	 */
	protected function get_wrapper_classes( $props, $key ) {
		$wrapper_class = [ 'pp-podcast' ];
		if ( 1 === $props['max'] ) {
			$wrapper_class[] = 'single-episode';
		}

		if ( $props['sets']['display-style'] ) {
			$wrapper_class[] = $props['sets']['display-style'];
			$wrapper_class[] = 'special-style';
		}

		if ( isset( $props['items'][0] ) && isset( $props['items'][0]['mediatype'] ) && $props['items'][0]['mediatype'] ) {
			$wrapper_class[] = 'media-' . $props['items'][0]['mediatype'];
		}

		$wrapper_class = apply_filters( 'podcast_player_wrapper_classes', $wrapper_class, $props['sets'], $key );
		$wrapper_class = array_map( 'esc_attr', $wrapper_class );
		return join( ' ', $wrapper_class );
	}

	/**
	 * Display Podcast head info top.
	 *
	 * @param array $props podcast player display props.
	 *
	 * @since  1.0.0
	 */
	protected function header_info_top( $props ) {
		$class               = 'pod-info__toggle';
		$podcast_info_toggle = sprintf( '<button aria-expanded="false" class="%1$s"><span class="ppjs__offscreen">%2$s</span>%3$s%4$s</button>', esc_attr( $class ), esc_html__( 'Show Podcast Details', 'podcast-player' ), podcast_player_get_icon( [ 'icon' => 'pp-menu' ] ), podcast_player_get_icon( [ 'icon' => 'pp-x' ] ) );

		printf( '<div class="pod-header__title"><div class="pod-title">%s</div>%s</div>', esc_html( $props['title'] ), $podcast_info_toggle ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Display Podcast episode download link.
	 *
	 * @param str $src podcast player display props.
	 *
	 * @since  1.0.0
	 */
	protected function download_link( $src ) {
		?>
		<a role="button" class="ppshare__download" href="<?php echo esc_url( $src ); ?>" title="Download" download="" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-download' ] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="download-text"><?php esc_html_e( 'Download', 'podcast-player' ); ?></span></a>
		<?php
	}

	/**
	 * Display Podcast episode social sharing links.
	 *
	 * @param array $item   podcast player first item.
	 * @param str   $fprint Feed unique key.
	 *
	 * @since  1.0.0
	 */
	protected function social_sharing( $item, $fprint = false ) {
		$url   = apply_filters( 'podcast_player_social_links', $item['link'], $item, $fprint );
		$url   = rawurlencode( $url );
		$title = rawurlencode( html_entity_decode( $item['title'], ENT_COMPAT, 'UTF-8' ) );
		?>
		<div class="ppshare__social ppsocial">
			<span class="ppsocial__text"><?php esc_html_e( 'Share on', 'podcast-player' ); ?></span>
			<a class="ppsocial__link ppsocial__facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-facebook' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'facebook', 'podcast-player' ); ?></span></a>
			<a class="ppsocial__link ppsocial__twitter" href="https://twitter.com/intent/tweet?text=<?php echo $title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>&amp;url=<?php echo $url; ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-twitter' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'twitter', 'podcast-player' ); ?></span></a>
			<a class="ppsocial__link ppsocial__linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-linkedin' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'linkedin', 'podcast-player' ); ?></span></a>
			<a class="ppsocial__link ppsocial__email" href="mailto:?subject=<?php echo $title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>&body=Link: <?php echo $url; ?>" target="_blank"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-mail' ] ); ?><span class="screen-reader-text"><?php esc_html_e( 'email', 'podcast-player' ); ?></span></a>
		</div>
		<?php
	}

	/**
	 * Display Podcast episode search field.
	 *
	 * @since  1.0.0
	 */
	protected function search_field() {
		?>
		<div class="episode-list__search">
			<input type="text" placeholder="<?php esc_attr_e( 'Search Episodes', 'podcast-player' ); ?>" title="<?php esc_attr_e( 'Search Podcast Episodes', 'podcast-player' ); ?>"/>
			<span class="episode-list__search-icon"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-search' ] );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<button class="episode-list__clear-search pod-button"><?php echo podcast_player_get_icon( [ 'icon' => 'pp-x' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="ppjs__offscreen"><?php esc_html_e( 'Clear Search Field', 'podcast-player' ); ?></span></button>
		</div>
		<?php
	}

	/**
	 * Display Podcast inline css.
	 *
	 * @param array $props podcast player display props.
	 *
	 * @since  1.0.0
	 */
	protected function generate_inline_css( $props ) {
		$css = '';
		$id  = '#pp-podcast-' . absint( $props['inst'] );
		$mod = '.modal-' . absint( $props['inst'] );
		if ( isset( $props['sets']['accent-color'] ) && $props['sets']['accent-color'] ) {
			$color = $props['sets']['accent-color'];
			$rgb   = podcast_player_hex_to_rgb( $color, true );
			$css  .= sprintf(
				'
				%1$s a,
				%4$s a {
					color: %2$s;
				}
				%1$s.postview .episode-list__load-more {
					color: %2$s !important;
				}
				%1$s.postview .episode-list__load-more {
					border-color: %2$s !important;
				}
				%1$s .ppjs__audio .ppjs__button.ppjs__playpause-button button,
				%1$s button.episode-list__load-more,
				%1$s .ppjs__audio-time-rail,
				%1$s.lv3 .pod-entry__play,
				%4$s .ppjs__audio .ppjs__button.ppjs__playpause-button button,
				%4$s button.episode-list__load-more,
				%4$s .ppjs__audio-time-rail,
				%4$s button.pp-modal-close {
					background-color: %2$s !important;
				}
				%1$s .hasCover .ppjs__audio .ppjs__button.ppjs__playpause-button button {
					background-color: rgba(0, 0, 0, 0.5) !important;
				}
				%1$s button.episode-list__load-more:hover,
				%1$s button.episode-list__load-more:focus,
				%4$s button.episode-list__load-more:hover,
				%4$s button.episode-list__load-more:focus {
					background-color: rgba( %3$s, 0.1 ) !important;
				}
				%1$s .pod-entry.activeEpisode,
				%1$s .ppjs__button.toggled-on,
				%4$s .pod-entry.activeEpisode,
				%4$s .ppjs__button.toggled-on {
					background-color: rgba( %3$s, 0.1 );
				}
				%1$s.postview .episode-list__load-more {
					background-color: transparent !important;
				}
				',
				$id,
				$color,
				$rgb,
				$mod
			);
		}

		if ( $props['sets']['hide-download'] && $props['sets']['hide-social'] ) {
			$css .= sprintf(
				'
				%1$s .ppjs__share-button,
				%2$s .ppjs__share-button {
					display: none;
				}
				',
				$id,
				$mod
			);
		}

		if ( $props['sets']['hide-content'] ) {
			$css .= sprintf(
				'
				%1$s .ppjs__script-button {
					display: none;
				}
				',
				$id
			);
		}

		if ( $props['sets']['hide-author'] ) {
			$css .= sprintf(
				'
				%1$s .pod-entry__author {
					display: none;
				}
				',
				$id
			);
		}

		if ( $props['sets']['header-default'] ) {
			$css .= sprintf(
				'
				%1$s .pod-info__header {
					display: block;
				}
				',
				$id
			);
		}

		if ( isset( $props['sets']['font-family'] ) && $props['sets']['font-family'] ) {
			$ff     = $props['sets']['font-family'];
			$fflist = apply_filters( 'podcast_player_fonts', [] );
			if ( isset( $fflist[ $ff ] ) ) {
				$font = $fflist[ $ff ];
				$css .= sprintf(
					'
					%1$s,
					%1$s input,
					%1$s .episode-list__load-more,
					%2$s {
						font-family: "%3$s",-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol !important;
					}
					',
					$id,
					$mod,
					$font
				);
			}
		}
		?>
		<style type="text/css"><?php echo wp_strip_all_tags( $css, true ); ?></style>
		<?php
	}

	/**
	 * Get elements supported by selected style.
	 *
	 * @return array
	 */
	public function get_style_supported() {
		if ( ! empty( $this->style_supported ) ) {
			return $this->style_supported;
		}

		$styles = podcast_player_default_styles();
		foreach ( $styles as $style => $args ) {
			$this->style_supported[ $style ] = $args['support'];
		}

		return $this->style_supported;
	}

	/**
	 * Check if item is supported by the style.
	 *
	 * @param string $style Current display style.
	 * @param string $item  item to be checked for support.
	 * @return bool
	 */
	public function is_style_support( $style, $item ) {
		$supported = $this->get_style_supported();

		if ( ! $style || ! isset( $supported[ $style ] ) ) {
			return false;
		}
		return in_array( $item, $supported[ $style ], true );
	}
}
