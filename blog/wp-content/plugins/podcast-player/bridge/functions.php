<?php
/**
 * Multipurpose helper functions for the plugin.
 *
 * @package Podcast Player
 * @since 1.0.0
 */

/**
 * Return font icon SVG markup.
 *
 * This function incorporates code from Twenty Seventeen WordPress Theme,
 * Copyright 2016-2017 WordPress.org. Twenty Seventeen is distributed
 * under the terms of the GNU GPL.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string Font icon SVG markup.
 */
function podcast_player_get_icon( $args = [] ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'podcast-player' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'podcast-player' );
	}

	// Set defaults.
	$defaults = [
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false,
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	/*
	 * Podcast Player doesn't use the SVG title or description attributes; non-decorative icons are
	 * described with .ppjs__offscreen. However, child themes can use the title and description
	 * to add information to non-decorative SVG icons to improve accessibility.
	 *
	 * Example 1 with title: <?php echo podcast_player_get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ] ); ?>
	 *
	 * Example 2 with title and description: <?php echo podcast_player_get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ] ); ?>
	 *
	 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
	 */
	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id       = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
		}
	}

	// Begin SVG markup.
	$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img" focusable="false">';

	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}

	/*
	 * Display the icon.
	 *
	 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	 *
	 * See https://core.trac.wordpress.org/ticket/38387.
	 */
	$svg .= ' <use href="#icon-' . esc_attr( $args['icon'] ) . '" xlink:href="#icon-' . esc_attr( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
	}

	$svg .= '</svg>';

	return $svg;
}

/**
 * Get navigation menu markup.
 *
 * Create navigation menu markup based on arguments provided.
 *
 * @since 1.0.0
 *
 * @param string $nav_classes Menu container ID.
 * @param string $label       Menu label.
 * @param array  $args        Additional wp_nav_menu args.
 */
function podcast_player_nav_menu( $nav_classes, $label, $args = [] ) {

	$menu  = sprintf( '<h2 class="ppjs__offscreen">%s</h2>', esc_html( $label ) );
	$menu .= wp_nav_menu( array_merge( $args, [ 'echo' => false ] ) );

	if ( is_array( $nav_classes ) ) {
		$nav_id      = $nav_classes[0];
		$nav_classes = array_map( 'esc_attr', $nav_classes );
		$nav_classes = join( ' ', $nav_classes );
	} else {
		$nav_id = $nav_classes;
	}

	return sprintf(
		'<nav id="%1$s" class="%2$s" aria-label="%3$s">%4$s</nav>',
		esc_attr( $nav_id ),
		esc_attr( $nav_classes ),
		esc_attr( $label ),
		$menu
	); // WPCS xss ok. $menu contains HTML, variable values escaped properly.
}

/**
 * Display a podcast instance.
 *
 * @since 1.0.0
 *
 * @param array $args    Podcast display args.
 * @param bool  $return  Display or return.
 */
function podcast_player_display( $args, $return = true ) {

	$is_premium = apply_filters( 'podcast_player_is_premium', false );
	$defaults   = [
		'url'              => '',
		'skin'             => 'light',
		'sortby'           => 'sort_date_desc',
		'filterby'         => '',
		'number'           => 10,
		'menu'             => '',
		'image'            => '',
		'description'      => '',
		'img_url'          => '',
		'excerpt-length'   => 25,
		'aspect-ratio'     => 'squr',
		'crop-method'      => 'centercrop',
		'no-excerpt'       => '',
		'header-default'   => '',
		'hide-header'      => '',
		'hide-title'       => '',
		'hide-cover-img'   => '',
		'hide-description' => '',
		'hide-subscribe'   => '',
		'hide-search'      => '',
		'hide-author'      => '',
		'hide-content'     => '',
		'hide-loadmore'    => '',
		'hide-download'    => '',
		'hide-social'      => '',
		'accent-color'     => '',
		'display-style'    => '',
		'grid-columns'     => 3,
		'fetch-method'     => 'feed',
		'post-type'        => 'post',
		'taxonomy'         => '',
		'terms'            => '',
		'podtitle'         => '',
		'audiosrc'         => '',
		'audiotitle'       => '',
		'audiolink'        => '',
		'ahide-download'   => '',
		'ahide-social'     => '',
		'audio-msg'        => '',
		'play-freq'        => 0,
		'msg-start'        => 'start',
		'msg-time'         => [ 0, 0 ],
		'msg-text'         => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
		'font-family'      => '',
		'from'             => false,
	];
	$args       = wp_parse_args( $args, $defaults );

	if ( 'feed' === $args['fetch-method'] ) {
		$podcast = Podcast_Player\Feed::get_instance();
	} elseif ( $is_premium && 'post' === $args['fetch-method'] ) {
		$podcast = Podcast_Player\Post_Type::get_instance();
	} elseif ( $is_premium && 'link' === $args['fetch-method'] ) {
		$podcast = Podcast_Player\Single_Audio::get_instance();
	} else {
		return esc_html__( 'Please enter appropriate fetch method.', 'podcast-player' );
	}

	ob_start();
	$podcast->display_podcast( $args );
	$episodes = ob_get_clean();

	if ( $return ) {
		return $episodes;
	}

	echo $episodes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Podcast audio player markup manager.
 *
 * @since 1.0.0
 *
 * @param array $src   Podcast media src.
 * @param int   $instance_counter current podcast player instance.
 * @return string
 */
function podcast_player_markup( $src, $instance_counter ) {

	if ( ! $src ) {
		return '';
	}

	// Strip querystring variables to validate media src.
	$src_url = preg_replace( '/\?.*/', '', $src );

	$html             = '';
	$type             = wp_check_filetype( $src_url, wp_get_mime_types() );
	$audio_extensions = wp_get_audio_extensions();
	$video_extensions = wp_get_video_extensions();

	if ( in_array( strtolower( $type['ext'] ), $audio_extensions, true ) ) {
		$html_atts = [
			'id'      => 'pp-podcast-' . esc_attr( $instance_counter ) . '-player',
			'preload' => 'none',
			'class'   => 'pp-podcast-episode',
			'style'   => 'width: 100%;',
		];

		$attr_strings = [];
		foreach ( $html_atts as $k => $v ) {
			$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		$html .= sprintf( '<audio %s controls="controls">', join( ' ', $attr_strings ) );
		$html .= sprintf( '<source type="%s" src="%s" />', $type['type'], esc_url( $src ) );
		$html .= '</audio>';
	} elseif ( in_array( strtolower( $type['ext'] ), $video_extensions, true ) ) {
		$html_atts = [
			'id'      => 'pp-podcast-' . absint( $instance_counter ) . '-player',
			'preload' => 'metadata',
			'class'   => 'pp-podcast-episode',
			'width'   => 800,
			'height'  => 450,
		];

		$attr_strings = [];
		foreach ( $html_atts as $k => $v ) {
			$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		$html .= sprintf( '<video %s controls="controls">', join( ' ', $attr_strings ) );
		$html .= sprintf( '<source type="%s" src="%s" />', $type['type'], esc_url( $src ) );
		$html .= '</video>';

		$width_rule = sprintf( 'width: %dpx;', $html_atts['width'] );
		$html       = sprintf( '<div style="%s" class="wp-video">%s</div>', $width_rule, $html );
	}

	return $html;
}

/**
 * Escape html while preserving paragraphs.
 *
 * @since 1.0.0
 *
 * @param str $html    HTML to be escaped.
 * @return str
 */
function podcast_player_esc_desc( $html = '' ) {

	if ( $html ) {
		$html = strip_shortcodes( $html );
		$html = str_replace( ']]>', ']]&gt;', $html );
		$html = nl2br( $html );
		$html = str_replace( [ '</p>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />' ], '<br>', $html );
		$html = explode( '<br>', $html );
		$html = array_map( 'wp_strip_all_tags', $html );
		$html = array_map(
			function( $str ) {
				$str = preg_replace( '/&nbsp;/', '', $str );
				return trim( preg_replace( '/\xc2\xa0/', '', $str ) );
			},
			$html
		);
		$html = array_filter( $html );
		$html = array_map( 'esc_html', $html );
		$html = wpautop( implode( '<br><br>', $html ) );
	} else {
		$html = '';
	}

	return $html;
}

/**
 * Convert hex color code to equivalent RGB code.
 *
 * @since 1.5
 *
 * @param string  $hex_color Hexadecimal color value.
 * @param boolean $as_string Return as string or associative array.
 * @param string  $sep       String to separate RGB values.
 * @return string
 */
function podcast_player_hex_to_rgb( $hex_color, $as_string, $sep = ',' ) {
	$hex_color = preg_replace( '/[^0-9A-Fa-f]/', '', $hex_color );
	$rgb_array = [];
	if ( 6 === strlen( $hex_color ) ) {
		$color_val          = hexdec( $hex_color );
		$rgb_array['red']   = 0xFF & ( $color_val >> 0x10 );
		$rgb_array['green'] = 0xFF & ( $color_val >> 0x8 );
		$rgb_array['blue']  = 0xFF & $color_val;
	} elseif ( 3 === strlen( $hex_color ) ) {
		$rgb_array['red']   = hexdec( str_repeat( substr( $hex_color, 0, 1 ), 2 ) );
		$rgb_array['green'] = hexdec( str_repeat( substr( $hex_color, 1, 1 ), 2 ) );
		$rgb_array['blue']  = hexdec( str_repeat( substr( $hex_color, 2, 1 ), 2 ) );
	} else {
		return false; // Invalid hex color code.
	}
	return $as_string ? implode( $sep, $rgb_array ) : $rgb_array;
}

/**
 * Get default podcast player styles.
 *
 * @since 2.3
 *
 * @return array
 */
function podcast_player_default_styles() {
	return apply_filters(
		'podcast_player_display_styles',
		[
			''       => [
				'label'   => esc_html__( 'Default Player', 'podcast-player' ),
				'support' => [],
			],
			'legacy' => [
				'label'   => esc_html__( 'Legacy Player', 'podcast-player' ),
				'support' => [],
			],
		]
	);
}
