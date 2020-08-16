<?php
/**
 * Compatibility with pp pro v 1.0.0.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 */

namespace Podcast_Player;

/**
 * Compatibility with pp pro v 1.0.0.
 *
 * @package    Podcast_Player
 * @author     vedathemes <contact@vedathemes.com>
 */
class Compat {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {

		// Premium version does not work with legacy mode.
		$legacy = get_option( 'pp-legacy-player' );
		if ( 'on' === $legacy ) {
			return;
		}

		if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '1.1.0', '<' ) ) {
			add_filter( 'podcast_player_widget_options', [ self::get_instance(), 'widget_options' ], 10, 3 );
			add_filter( 'podcast_player_widget_update', [ self::get_instance(), 'widget_update' ], 10, 3 );
			add_filter( 'podcast_player_widget_display', [ self::get_instance(), 'widget_display' ], 10, 2 );
			add_filter( 'podcast_player_shcode_display', [ self::get_instance(), 'shcode_display' ], 10, 2 );
			add_filter( 'podcast_player_block_display', [ self::get_instance(), 'block_display' ], 10, 2 );
		}
	}

	/**
	 * Podcast player premium widget options.
	 *
	 * @param array $options Podcast player widget options array.
	 * @param Obj   $widget Current widget instance.
	 * @param Array $settings Settings for current widget instance.
	 *
	 * @since  1.0.0
	 */
	public function widget_options( $options = [], $widget, $settings ) {

		$options['default']['items'] = $this->insert_array(
			$options['default']['items'],
			[
				'pp_fetch_method' => [
					'setting' => 'pp_fetch_method',
					'label'   => esc_html__( 'Podcast Episodes', 'podcast-player' ),
					'type'    => 'select',
					'choices' => [
						'feed' => esc_html__( 'From FeedURL', 'podcast-player' ),
						'post' => esc_html__( 'From Posts', 'podcast-player' ),
						'link' => esc_html__( 'From Audio/Video Link', 'podcast-player' ),
					],
				],
			],
			'title'
		);

		$options['default']['items'] = $this->insert_array(
			$options['default']['items'],
			[
				'pp_post_type'      => [
					'setting'       => 'pp_post_type',
					'label'         => esc_html__( 'Select Post Type', 'podcast-player' ),
					'type'          => 'select',
					'choices'       => pp_pro_get_post_types(),
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'post', $settings );
					},
				],
				'pp_taxonomy'       => [
					'setting'       => 'pp_taxonomy',
					'type'          => 'taxonomy',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'post', $settings );
					},
				],
				'pp_terms'          => [
					'setting'       => 'pp_terms',
					'type'          => 'terms',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'post', $settings ) && $widget->is_option_equal( 'pp_taxonomy', '', $settings );
					},
				],
				'pp_audiosrc'       => [
					'setting'       => 'pp_audiosrc',
					'label'         => esc_html__( 'Valid Audio/Video File Link (i.e, mp3, ogg, m4a etc.)', 'podcast-player' ),
					'type'          => 'text',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
				],
				'pp_audiotitle'     => [
					'setting'       => 'pp_audiotitle',
					'label'         => esc_html__( 'Episode Title', 'podcast-player' ),
					'type'          => 'text',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
				],
				'pp_audiolink'      => [
					'setting'       => 'pp_audiolink',
					'label'         => esc_html__( 'Podcast episode link for social sharing (optional)', 'podcast-player' ),
					'type'          => 'text',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
				],
				'pp_ahide_download' => [
					'setting'       => 'pp_ahide_download',
					'label'         => esc_html__( 'Hide Episode Download Link', 'podcast-player' ),
					'type'          => 'checkbox',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
				],
				'pp_ahide_social'   => [
					'setting'       => 'pp_ahide_social',
					'label'         => esc_html__( 'Hide Social Share Links', 'podcast-player' ),
					'type'          => 'checkbox',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
				],
			]
		);

		$options['info']['items'] = $this->insert_array(
			$options['info']['items'],
			[
				'pp_podtitle' => [
					'setting'       => 'pp_podtitle',
					'label'         => esc_html__( 'Podcast Title', 'podcast-player' ),
					'type'          => 'text',
					'hide_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_equal( 'pp_fetch_method', 'feed', $settings );
					},
				],
			],
			0
		);

		$options['info']['items'] = $this->insert_array(
			$options['info']['items'],
			[
				'pp_excerpt_length' => [
					'setting'       => 'pp_excerpt_length',
					'label'         => esc_html__( 'Excerpt Length', 'podcast-player' ),
					'type'          => 'number',
					'input_attrs'   => [
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
						'size' => 3,
					],
					'hide_callback' => function() use ( $widget, $settings ) {
						return ! $widget->is_style_support( $settings['pp_display_style'], 'excerpt' );
					},
				],
			]
		);

		$options['style']['items'] = $this->insert_array(
			$options['style']['items'],
			[
				'pp_aspect_ratio' => [
					'setting'       => 'pp_aspect_ratio',
					'label'         => esc_html__( 'Thumbnail Cropping', 'podcast-player' ),
					'type'          => 'select',
					'choices'       => [
						''       => esc_html__( 'No Cropping', 'podcast-player' ),
						'land1'  => esc_html__( 'Landscape (4:3)', 'podcast-player' ),
						'land2'  => esc_html__( 'Landscape (3:2)', 'podcast-player' ),
						'port1'  => esc_html__( 'Portrait (3:4)', 'podcast-player' ),
						'port2'  => esc_html__( 'Portrait (2:3)', 'podcast-player' ),
						'wdscrn' => esc_html__( 'Widescreen (16:9)', 'podcast-player' ),
						'squr'   => esc_html__( 'Square (1:1)', 'podcast-player' ),
					],
					'hide_callback' => function() use ( $widget, $settings ) {
						return ! $widget->is_style_support( $settings['pp_display_style'], 'thumbnail' );
					},
				],
				'pp_crop_method'  => [
					'setting'       => 'pp_crop_method',
					'label'         => esc_html__( 'Thumbnail Cropping Position', 'podcast-player' ),
					'type'          => 'select',
					'choices'       => [
						'topleftcrop'      => esc_html__( 'Top Left Cropping', 'podcast-player' ),
						'topcentercrop'    => esc_html__( 'Top Center Cropping', 'podcast-player' ),
						'centercrop'       => esc_html__( 'Center Cropping', 'podcast-player' ),
						'bottomleftcrop'   => esc_html__( 'Bottom Left Cropping', 'podcast-player' ),
						'bottomcentercrop' => esc_html__( 'Bottom Center Cropping', 'podcast-player' ),
					],
					'hide_callback' => function() use ( $widget, $settings ) {
						return ! ( $widget->is_style_support( $settings['pp_display_style'], 'thumbnail' ) && '' !== $settings['pp_aspect_ratio'] );
					},
				],
				'pp_grid_columns' => [
					'setting'       => 'pp_grid_columns',
					'label'         => esc_html__( 'Maximum Grid Columns.', 'podcast-player' ),
					'type'          => 'number',
					'input_attrs'   => [
						'step' => 1,
						'min'  => 2,
						'max'  => 6,
						'size' => 1,
					],
					'hide_callback' => function() use ( $widget, $settings ) {
						return ! $widget->is_style_support( $settings['pp_display_style'], 'grid' );
					},
				],
			]
		);

		return $options;
	}

	/**
	 * Podcast player premium widget options update.
	 *
	 * @param Array $instance Widget options to be saved.
	 * @param Array $new_instance Modified widget options.
	 * @param Obj   $widget Current widget instance.
	 *
	 * @since  1.0.0
	 */
	public function widget_update( $instance, $new_instance, $widget ) {
		$sanitize_int = [
			'pp_excerpt_length',
			'pp_grid_columns',
		];
		foreach ( $sanitize_int as $setting ) {
			$instance[ $setting ] = absint( $new_instance[ $setting ] );
		}

		$sanitize_text = [
			'pp_aspect_ratio',
			'pp_crop_method',
			'pp_fetch_method',
			'pp_post_type',
			'pp_podtitle',
			'pp_audiotitle',
		];
		foreach ( $sanitize_text as $setting ) {
			$instance[ $setting ] = sanitize_text_field( $new_instance[ $setting ] );
		}

		$sanitize_url = [
			'pp_audiosrc',
			'pp_audiolink',
		];
		foreach ( $sanitize_url as $url ) {
			$instance[ $url ] = esc_url_raw( $new_instance[ $url ] );
		}

		$sanitize_bool = [
			'pp_ahide_download',
			'pp_ahide_social',
		];
		foreach ( $sanitize_bool as $setting ) {
			$instance[ $setting ] = ( 'yes' === $new_instance[ $setting ] ) ? 'yes' : '';
		}

		if ( 'post' !== $instance['pp_fetch_method'] ) {
			$instance['pp_taxonomy'] = '';
			$instance['pp_terms']    = [];
		} else {
			if ( $instance['pp_post_type'] && $new_instance['pp_taxonomy'] ) {
				$instance['pp_taxonomy'] = sanitize_text_field( $new_instance['pp_taxonomy'] );
			} else {
				$instance['pp_taxonomy'] = '';
			}

			if ( $instance['pp_taxonomy'] && $new_instance['pp_terms'] ) {
				$instance['pp_terms'] = array_map( 'sanitize_text_field', $new_instance['pp_terms'] );
			} else {
				$instance['pp_terms'] = [];
			}
		}

		return $instance;
	}

	/**
	 * Podcast player premium widget options update.
	 *
	 * @param Array $display_args Podcast player display args.
	 * @param Array $instance     Settings for current widget instance.
	 *
	 * @since  1.0.0
	 */
	public function widget_display( $display_args, $instance ) {
		return array_merge(
			$display_args,
			[
				'excerpt-length' => $instance['pp_excerpt_length'],
				'aspect-ratio'   => $instance['pp_aspect_ratio'],
				'crop-method'    => $instance['pp_crop_method'],
				'grid-columns'   => $instance['pp_grid_columns'],
				'fetch-method'   => $instance['pp_fetch_method'],
				'post-type'      => $instance['pp_post_type'],
				'taxonomy'       => $instance['pp_taxonomy'],
				'terms'          => $instance['pp_terms'],
				'podtitle'       => $instance['pp_podtitle'],
				'audiosrc'       => $instance['pp_audiosrc'],
				'audiotitle'     => $instance['pp_audiotitle'],
				'audiolink'      => $instance['pp_audiolink'],
				'ahide-download' => $instance['pp_ahide_download'],
				'ahide-social'   => $instance['pp_ahide_social'],
			]
		);
	}

	/**
	 * Podcast player premium shortcode options update.
	 *
	 * @param Array $display_args Podcast player display args.
	 * @param Array $atts         Attributes for current shortcode instance.
	 *
	 * @since  1.0.0
	 */
	public function shcode_display( $display_args, $atts ) {
		$terms = [];
		if ( ! empty( $atts['terms'] ) ) {
			$terms = explode( ',', $atts['terms'] );
			$terms = array_map( 'trim', $terms );
		}

		return array_merge(
			$display_args,
			[
				'excerpt-length' => $atts['excerpt_length'],
				'aspect-ratio'   => $atts['aspect_ratio'],
				'crop-method'    => $atts['crop_method'],
				'grid-columns'   => $atts['grid_columns'],
				'fetch-method'   => $atts['fetch_method'],
				'post-type'      => $atts['post_type'],
				'taxonomy'       => $atts['taxonomy'],
				'terms'          => $terms,
				'podtitle'       => $atts['podtitle'],
				'audiosrc'       => $atts['mediasrc'],
				'audiotitle'     => $atts['episodetitle'],
				'audiolink'      => $atts['episodelink'],
			]
		);
	}

	/**
	 * Podcast player premium block options update.
	 *
	 * @param Array $display_args Podcast player display args.
	 * @param Array $atts         Attributes for current block instance.
	 *
	 * @since  1.0.0
	 */
	public function block_display( $display_args, $atts ) {
		return array_merge(
			$display_args,
			[
				'excerpt-length' => $atts['excerptLength'],
				'aspect-ratio'   => $atts['aspectRatio'],
				'crop-method'    => $atts['cropMethod'],
				'grid-columns'   => $atts['gridColumns'],
				'fetch-method'   => $atts['fetchMethod'],
				'post-type'      => $atts['postType'],
				'taxonomy'       => $atts['taxonomy'],
				'terms'          => $atts['terms'],
				'podtitle'       => $atts['podtitle'],
				'audiosrc'       => $atts['audioSrc'],
				'audiotitle'     => $atts['audioTitle'],
				'audiolink'      => $atts['audioLink'],
				'ahide-download' => true === $atts['ahideDownload'] ? 1 : 0,
				'ahide-social'   => true === $atts['ahideSocial'] ? 1 : 0,
			]
		);
	}

	/**
	 * Add an element to an array after certain associative key.
	 *
	 * @param array $array Main array.
	 * @param array $added Array items to be added.
	 * @param str   $key   Array key after which items to be added.
	 *
	 * @since  1.0.0
	 */
	public function insert_array( $array, $added, $key = false ) {

		if ( is_int( $key ) ) {
			$pos = $key;
		} else {
			$pos = $key ? array_search( $key, array_keys( $array ), true ) : false;
			$pos = ( false !== $pos ) ? $pos + 1 : $pos;
		}

		if ( false !== $pos ) {
			return array_merge(
				array_slice( $array, 0, $pos ),
				$added,
				array_slice( $array, $pos )
			);
		}

		return array_merge( $array, $added );
	}

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Compat::init();
