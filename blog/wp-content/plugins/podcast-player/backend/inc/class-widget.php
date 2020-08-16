<?php
/**
 * Widget API: Display Podcast from feed url class
 *
 * @package podcast-player
 * @since 1.0.0
 */

namespace Podcast_Player;

/**
 * Class used to display podcast episodes from a feed url.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Widget extends \WP_Widget {

	/**
	 * Holds all display styles.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $styles = [];

	/**
	 * Holds all display styles supported items.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $style_supported = [];

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $defaults = [];

	/**
	 * Are we using modern player.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_modern = true;

	/**
	 * Is this the premium version.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_premium = true;

	/**
	 * Sets up a new Blank widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set widget instance settings default values.
		$this->defaults = [
			'title'               => '',
			'pp_skin'             => 'light',
			'sortby'              => 'sort_date_desc',
			'filterby'            => '',
			'feed_url'            => '',
			'number'              => 10,
			'podcast_menu'        => '',
			'cover_image'         => '',
			'desc'                => '',
			'error'               => '',
			'pp_excerpt_length'   => 25,
			'pp_no_excerpt'       => '',
			'pp_grid_columns'     => 3,
			'pp_header_default'   => '',
			'pp_hide_header'      => '',
			'pp_hide_cover'       => '',
			'pp_hide_title'       => '',
			'pp_hide_description' => '',
			'pp_hide_subscribe'   => '',
			'pp_hide_search'      => '',
			'pp_hide_author'      => '',
			'pp_hide_content'     => '',
			'pp_hide_loadmore'    => '',
			'pp_hide_download'    => '',
			'pp_hide_social'      => '',
			'pp_accent_color'     => '',
			'pp_display_style'    => '',
			'pp_aspect_ratio'     => 'squr',
			'pp_crop_method'      => 'centercrop',
			'pp_fetch_method'     => 'feed',
			'pp_post_type'        => 'post',
			'pp_taxonomy'         => '',
			'pp_terms'            => [],
			'pp_podtitle'         => '',
			'pp_audiosrc'         => '',
			'pp_audiotitle'       => '',
			'pp_audiolink'        => '',
			'pp_ahide_download'   => '',
			'pp_ahide_social'     => '',
			'pp_audio_message'    => '',
			'pp_play_frequency'   => 0,
			'pp_start_time'       => [ 0, 0, 0 ],
			'pp_start_when'       => 'start',
			'pp_msg_text'         => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
			'pp_fonts'            => '',
		];

		$legacy = get_option( 'pp-legacy-player' );
		if ( 'on' === $legacy ) {
			$this->is_modern = false;
		}

		$this->is_premium = apply_filters( 'podcast_player_is_premium', false );

		// Set the widget options.
		$widget_ops = [
			'classname'                   => 'podcast_player',
			'description'                 => esc_html__( 'Create a podcast player widget.', 'podcast-player' ),
			'customize_selective_refresh' => true,
		];
		parent::__construct( 'podcast_player_widget', esc_html__( 'Podcast Player', 'podcast-player' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {

		$args['widget_id'] = isset( $args['widget_id'] ) ? $args['widget_id'] : $this->id;

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$url = '';
		if ( 'feed' === $instance['pp_fetch_method'] ) {
			// Return if there is no feed url.
			$url = ! empty( $instance['feed_url'] ) ? $instance['feed_url'] : '';

			while ( stristr( $url, 'http' ) !== $url ) {
				$url = substr( $url, 1 );
			}

			if ( empty( $url ) ) {
				return;
			}
		}

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$display_args = apply_filters(
			'podcast_player_widget_display',
			[
				'url'              => $url,
				'skin'             => $instance['pp_skin'],
				'sortby'           => $instance['sortby'],
				'filterby'         => $instance['filterby'],
				'number'           => $instance['number'],
				'menu'             => $instance['podcast_menu'],
				'image'            => $instance['cover_image'],
				'description'      => $instance['desc'],
				'no-excerpt'       => $instance['pp_no_excerpt'],
				'header-default'   => $instance['pp_header_default'],
				'hide-header'      => $instance['pp_hide_header'],
				'hide-title'       => $instance['pp_hide_title'],
				'hide-cover-img'   => $instance['pp_hide_cover'],
				'hide-description' => $instance['pp_hide_description'],
				'hide-subscribe'   => $instance['pp_hide_subscribe'],
				'hide-search'      => $instance['pp_hide_search'],
				'hide-author'      => $instance['pp_hide_author'],
				'hide-content'     => $instance['pp_hide_content'],
				'hide-loadmore'    => $instance['pp_hide_loadmore'],
				'hide-download'    => $instance['pp_hide_download'],
				'hide-social'      => $instance['pp_hide_social'],
				'accent-color'     => $instance['pp_accent_color'],
				'display-style'    => $instance['pp_display_style'],
				'from'             => 'widget',
			],
			$instance
		);

		podcast_player_display( $display_args, false );
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get array of all widget options.
	 *
	 * @param array $settings Array of settings for current widget instance.
	 *
	 * @since 1.0.0
	 */
	public function get_widget_options( $settings ) {
		$widget        = $this;
		$menus         = wp_get_nav_menus();
		$menu_arr      = wp_list_pluck( $menus, 'name', 'term_id' );
		$menu_arr      = [ '' => esc_html__( 'None', 'podcast-player' ) ] + $menu_arr;
		$display_style = $this->get_display_styles();

		return apply_filters(
			'podcast_player_widget_options',
			[
				'default' => [
					'title' => esc_html__( 'General Options', 'podcast-player' ),
					'items' => [
						'title'    => [
							'setting' => 'title',
							'label'   => esc_html__( 'Title', 'podcast-player' ),
							'type'    => 'text',
						],
						'feed_url' => [
							'setting'       => 'feed_url',
							'label'         => esc_html__( 'Podcast Feed URL', 'podcast-player' ),
							'type'          => 'text',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_not_equal( 'pp_fetch_method', 'feed', $settings );
							},
						],
						'pp_skin'  => [
							'setting'          => 'pp_skin',
							'label'            => esc_html__( 'Player Skin', 'podcast-player' ),
							'type'             => 'select',
							'display_callback' => [ $this, 'is_legacy' ],
							'choices'          => [
								'light' => esc_html__( 'Light', 'podcast-player' ),
								'dark'  => esc_html__( 'Dark', 'podcast-player' ),
							],
						],
					],
				],
				'info'    => [
					'title' => esc_html__( 'Customize Podcast Content', 'podcast-player' ),
					'items' => [
						'cover_image'   => [
							'setting' => 'cover_image',
							'label'   => esc_html__( 'Podcast Cover Image', 'podcast-player' ),
							'type'    => 'image_upload',
						],
						'desc'          => [
							'setting'     => 'desc',
							'label'       => esc_html__( 'Brief Description', 'podcast-player' ),
							'type'        => 'textarea',
							'input_attrs' => [
								'col' => 50,
								'row' => 3,
							],
						],
						'pp_no_excerpt' => [
							'setting'          => 'pp_no_excerpt',
							'label'            => esc_html__( 'Show full description in place of excerpt.', 'podcast-player' ),
							'type'             => 'checkbox',
							'display_callback' => [ $this, 'is_legacy' ],
						],
						'podcast_menu'  => [
							'setting' => 'podcast_menu',
							'label'   => esc_html__( 'Podcast Custom Menu', 'podcast-player' ),
							'type'    => 'select',
							'choices' => $menu_arr,
						],
						'number'        => [
							'setting'     => 'number',
							'label'       => esc_html__( 'Number of episodes to show at a time.', 'podcast-player' ),
							'type'        => 'number',
							'input_attrs' => [
								'step' => 1,
								'min'  => 1,
								'size' => 3,
							],
						],
					],
				],
				'show'    => [
					'title' => esc_html__( 'Show/Hide Player Items', 'podcast-player' ),
					'items' => [
						'pp_header_default'   => [
							'setting'          => 'pp_header_default',
							'label'            => esc_html__( 'Show Podcast Header by Default.', 'podcast-player' ),
							'type'             => 'checkbox',
							'hide_callback'    => function() use ( $widget, $settings ) {
								return $widget->is_option_not_equal( 'pp_display_style', '', $settings );
							},
							'display_callback' => [ $this, 'is_modern' ],
						],
						'pp_hide_header'      => [
							'setting'          => 'pp_hide_header',
							'label'            => esc_html__( 'Hide Podcast Header Information.', 'podcast-player' ),
							'type'             => 'checkbox',
							'display_callback' => [ $this, 'is_modern' ],
						],
						'pp_hide_cover'       => [
							'setting'       => 'pp_hide_cover',
							'label'         => esc_html__( 'Hide cover image.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						],
						'pp_hide_title'       => [
							'setting'          => 'pp_hide_title',
							'label'            => esc_html__( 'Hide Podcast Title.', 'podcast-player' ),
							'type'             => 'checkbox',
							'display_callback' => [ $this, 'is_modern' ],
							'hide_callback'    => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						],
						'pp_hide_description' => [
							'setting'       => 'pp_hide_description',
							'label'         => esc_html__( 'Hide Podcast Description.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						],
						'pp_hide_subscribe'   => [
							'setting'       => 'pp_hide_subscribe',
							'label'         => esc_html__( 'Hide Custom menu.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						],
						'pp_hide_search'      => [
							'setting' => 'pp_hide_search',
							'label'   => esc_html__( 'Hide Podcast Search.', 'podcast-player' ),
							'type'    => 'checkbox',
						],
						'pp_hide_author'      => [
							'setting'          => 'pp_hide_author',
							'label'            => esc_html__( 'Hide Episode Author/Podcaster Name.', 'podcast-player' ),
							'type'             => 'checkbox',
							'display_callback' => [ $this, 'is_modern' ],
						],
						'pp_hide_content'     => [
							'setting'       => 'pp_hide_content',
							'label'         => esc_html__( 'Hide Episode Text Content/Transcript.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_premium && $widget->is_option_not_equal( 'pp_fetch_method', 'feed', $settings );
							},
						],
						'pp_hide_loadmore'    => [
							'setting' => 'pp_hide_loadmore',
							'label'   => esc_html__( 'Hide Load More Episodes Button.', 'podcast-player' ),
							'type'    => 'checkbox',
						],
						'pp_hide_download'    => [
							'setting' => 'pp_hide_download',
							'label'   => esc_html__( 'Hide Episode Download Link.', 'podcast-player' ),
							'type'    => 'checkbox',
						],
						'pp_hide_social'      => [
							'setting' => 'pp_hide_social',
							'label'   => esc_html__( 'Hide Social Share Links.', 'podcast-player' ),
							'type'    => 'checkbox',
						],
					],
				],
				'style'   => [
					'title' => esc_html__( 'Podcast Player Styling', 'podcast-player' ),
					'items' => [
						'pp_accent_color'  => [
							'setting'          => 'pp_accent_color',
							'label'            => esc_html__( 'Accent Color', 'podcast-player' ),
							'type'             => 'color',
							'display_callback' => [ $this, 'is_modern' ],
						],
						'pp_display_style' => [
							'setting'          => 'pp_display_style',
							'label'            => esc_html__( 'Podcast Player Display Style', 'podcast-player' ),
							'type'             => 'select',
							'choices'          => $display_style,
							'display_callback' => [ $this, 'is_modern' ],
						],
					],
				],
				'filter'  => [
					'title' => esc_html__( 'Sort & Filter Options', 'podcast-player' ),
					'items' => [
						'sortby'   => [
							'setting'          => 'sortby',
							'label'            => esc_html__( 'Sort Podcast Episodes By', 'podcast-player' ),
							'type'             => 'select',
							'display_callback' => [ $this, 'is_modern' ],
							'choices'          => [
								'sort_title_desc' => esc_html__( 'Title Descending', 'podcast-player' ),
								'sort_title_asc'  => esc_html__( 'Title Ascending', 'podcast-player' ),
								'sort_date_desc'  => esc_html__( 'Date Descending', 'podcast-player' ),
								'sort_date_asc'   => esc_html__( 'Date Ascending', 'podcast-player' ),
							],
						],
						'filterby' => [
							'setting'          => 'filterby',
							'label'            => esc_html__( 'Show episodes only if title contains following', 'podcast-player' ),
							'type'             => 'text',
							'display_callback' => [ $this, 'is_modern' ],
						],
					],
				],
			],
			$widget,
			$settings
		);
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$options  = $this->get_widget_options( $instance );

		$default_markup = '';
		$options_markup = '';
		foreach ( $options as $option => $args ) {
			$items  = $args['items'];
			$markup = '';
			foreach ( $items as $item => $attr ) {
				$dcall = isset( $attr['display_callback'] ) && is_callable( $attr['display_callback'] ) ? call_user_func( $attr['display_callback'] ) : true;
				if ( ! $dcall ) {
					continue;
				}

				$set   = $attr['setting'];
				$id    = esc_attr( $this->get_field_id( $set ) );
				$name  = esc_attr( $this->get_field_name( $set ) );
				$type  = $attr['type'];
				$label = isset( $attr['label'] ) ? $attr['label'] : '';
				$desc  = isset( $attr['desc'] ) ? $attr['desc'] : '';
				$iatt  = isset( $attr['input_attrs'] ) ? $attr['input_attrs'] : [];
				$hcal  = isset( $attr['hide_callback'] ) && is_callable( $attr['hide_callback'] ) ? call_user_func( $attr['hide_callback'] ) : false;

				$inputattr = '';
				foreach ( $iatt as $att => $val ) {
					$inputattr .= esc_html( $att ) . '="' . esc_attr( $val ) . '" ';
				}

				switch ( $type ) {
					case 'select':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= $this->select( $set, $attr['choices'], $instance[ $set ], [], false );
						break;
					case 'checkbox':
						$optmar  = sprintf( '<input name="%s" id="%s" type="checkbox" value="yes" %s />', $name, $id, checked( $instance[ $set ], 'yes', false ) );
						$optmar .= $this->label( $set, $label, false );
						break;
					case 'text':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="widefat" name="%1$s" id="%2$s" type="text" value="%3$s" />', $name, $id, esc_attr( $instance[ $set ] ) );
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'number':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="widefat" name="%1$s" id="%2$s" type="number" value="%3$s" %4$s />', $name, $id, absint( $instance[ $set ] ), $inputattr );
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'mmss':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<div class="mmss-time" id="%s">', $id );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s" min="0" max="10" size="2"/> : ', $name, absint( $instance[ $set ][0] ) );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s"  min="0" max="59" size="2" /> : ', $name, absint( $instance[ $set ][1] ) );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s"  min="0" max="59" size="2" />', $name, absint( $instance[ $set ][2] ) );
						$optmar .= '</div>';
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'textarea':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<textarea class="widefat" name="%1$s" id="%2$s" %3$s >%4$s</textarea>', $name, $id, $inputattr, esc_attr( $instance[ $set ] ) );
						break;
					case 'image_upload':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= $this->image_upload( $id, $name, $instance[ $set ] );
						break;
					case 'color':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="pp-color-picker" name="%1$s" id="%2$s" type="text" value="%3$s" />', $name, $id, esc_attr( sanitize_hex_color( $instance[ $set ] ) ) );
						break;
					case 'taxonomy':
						$optmar = $this->taxonomies_select( $instance['pp_post_type'], $instance['pp_taxonomy'] );
						break;
					case 'terms':
						$optmar = $this->terms_checklist( $instance['pp_taxonomy'], $instance['pp_terms'] );
						break;
					default:
						$optmar = apply_filters( 'podcast_player_custom_option_field', false, $item, $attr, $this, $instance );
						break;
				}
				$style   = $hcal ? 'style="display: none;"' : '';
				$markup .= $optmar ? sprintf( '<div class="%1$s pp-widget-option" %2$s>%3$s</div>', $set, $style, $optmar ) : '';
			}
			if ( 'default' === $option ) {
				$default_markup = $markup;
			} else {
				$section         = sprintf( '<a class="pp-settings-toggle">%s</a>', $args['title'] );
				$section        .= sprintf( '<div class="pp-settings-content">%s</div>', $markup );
				$options_markup .= $section;
			}
		}
		printf( '%1$s<div class="pp-options-wrapper">%2$s</div>', $default_markup, $options_markup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( 0 < strlen( $instance['error'] ) ) :
			?>
			<div style="color: red; font-weight: bold;"><?php echo esc_html( $instance['error'] ); ?></div>
			<?php
		endif;
	}

	/**
	 * Handles updating the settings for the current widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		// Merge with defaults.
		$new_instance = wp_parse_args( (array) $new_instance, $this->defaults );

		$instance                = $old_instance;
		$img_id                  = absint( $new_instance['cover_image'] );
		$img_url                 = $img_id ? wp_get_attachment_image_src( $img_id ) : false;
		$instance['cover_image'] = $img_url ? $img_id : '';

		$sanitize_int = [
			'number',
			'podcast_menu',
		];
		foreach ( $sanitize_int as $setting ) {
			$instance[ $setting ] = absint( $new_instance[ $setting ] );
		}

		$sanitize_text = [
			'title',
			'pp_skin',
			'desc',
			'sortby',
			'filterby',
			'pp_display_style',
		];
		foreach ( $sanitize_text as $setting ) {
			$instance[ $setting ] = sanitize_text_field( $new_instance[ $setting ] );
		}

		$sanitize_bool = [
			'pp_no_excerpt',
			'pp_hide_title',
			'pp_hide_cover',
			'pp_hide_description',
			'pp_header_default',
			'pp_hide_header',
			'pp_hide_subscribe',
			'pp_hide_search',
			'pp_hide_author',
			'pp_hide_content',
			'pp_hide_loadmore',
			'pp_hide_download',
			'pp_hide_social',
		];
		foreach ( $sanitize_bool as $setting ) {
			$instance[ $setting ] = ( 'yes' === $new_instance[ $setting ] ) ? 'yes' : '';
		}

		$instance['pp_accent_color'] = sanitize_hex_color( $new_instance['pp_accent_color'] );

		if ( $this->is_premium && isset( $instance['pp_fetch_method'] ) && 'feed' !== $instance['pp_fetch_method'] ) {
			$instance['feed_url'] = '';
			$instance['error']    = '';
		} else {
			$error   = '';
			$feedurl = '';
			if ( isset( $old_instance['feed_url'] ) && ( $new_instance['feed_url'] === $old_instance['feed_url'] ) ) {
				$feedurl = $old_instance['feed_url'];
			} elseif ( $new_instance['feed_url'] ) {
				$feedurl = esc_url_raw( wp_strip_all_tags( $new_instance['feed_url'] ) );

				// Retrieve feed items for url validation.
				$rss = fetch_feed( $feedurl );
				if ( is_wp_error( $rss ) ) {
					$error .= ' ' . $rss->get_error_message();
				} else {
					$rss->__destruct();
				}
				unset( $rss );
			}

			$instance['feed_url'] = $feedurl;
			$instance['error']    = sanitize_text_field( $error );
		}

		return apply_filters( 'podcast_player_widget_update', $instance, $new_instance, $this );
	}

	/**
	 * Check if user is using modern podcast player.
	 */
	public function is_modern() {
		return $this->is_modern;
	}

	/**
	 * Check if user is using legacy podcast player.
	 */
	public function is_legacy() {
		return ! $this->is_modern;
	}

	/**
	 * Check if widget setting contains a particular value.
	 *
	 * @param str   $setting Setting to be checked.
	 * @param str   $val Setting value to be matched.
	 * @param array $settings Array of settings for current widget instance.
	 * @return bool
	 */
	public function is_option_equal( $setting, $val, $settings ) {
		return isset( $settings[ $setting ] ) && $val === $settings[ $setting ];
	}

	/**
	 * Check if widget setting doen not contains a particular value.
	 *
	 * @param str   $setting Setting to be checked.
	 * @param str   $val Setting value to be matched.
	 * @param array $settings Array of settings for current widget instance.
	 * @return bool
	 */
	public function is_option_not_equal( $setting, $val, $settings ) {
		return ! isset( $settings[ $setting ] ) || $val !== $settings[ $setting ];
	}

	/**
	 * Markup for 'label' for widget input options.
	 *
	 * @param str  $for  Label for which ID.
	 * @param str  $text Label text.
	 * @param bool $echo Display or Return.
	 * @return void|string
	 */
	public function label( $for, $text, $echo = true ) {
		$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->get_field_id( $for ) ), esc_html( $text ) );
		if ( $echo ) {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $label;
		}
	}

	/**
	 * Markup for Select dropdown lists for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Options HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function select( $for, $options, $selected, $classes = [], $echo = true ) {
		$select      = '';
		$final_class = '';
		foreach ( $options as $value => $label ) {
			if ( isset( $classes[ $value ] ) ) {
				$option_classes = (array) $classes[ $value ];
				$option_classes = array_map( 'esc_attr', $option_classes );
				$final_class    = 'class="' . join( ' ', $option_classes ) . '"';
			}
			$select .= sprintf( '<option value="%1$s" %2$s %3$s>%4$s</option>', esc_attr( $value ), $final_class, selected( $value, $selected, false ), esc_html( $label ) );
		}

		$select = sprintf(
			'<select id="%1$s" name="%2$s" class="podcast-player-%3$s widefat">%4$s</select>',
			esc_attr( $this->get_field_id( $for ) ),
			esc_attr( $this->get_field_name( $for ) ),
			esc_attr( str_replace( '_', '-', $for ) ),
			$select
		);

		if ( $echo ) {
			echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $select;
		}
	}

	/**
	 * Image upload option markup.
	 *
	 * @since 1.0.0
	 *
	 * @param str $id      Field ID.
	 * @param str $name    Field Name.
	 * @param int $value   Uploaded image id.
	 * @return str Widget form image upload markup.
	 */
	public function image_upload( $id, $name, $value ) {

		$value          = absint( $value );
		$uploader_class = '';
		$class          = 'podcast-player-hidden';

		if ( $value ) {
			$image_src = wp_get_attachment_image_src( $value, 'large' );
			if ( $image_src ) {
				$featured_markup = sprintf( '<img class="custom-widget-thumbnail" src="%s">', esc_url( $image_src[0] ) );
				$class           = '';
				$uploader_class  = 'has-image';
			} else {
				$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
			}
		} else {
			$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
		}

		$markup  = sprintf( '<a class="podcast-player-widget-img-uploader %s">%s</a>', $uploader_class, $featured_markup );
		$markup .= sprintf( '<span class="podcast-player-widget-img-instruct %s">%s</span>', $class, esc_html__( 'Click the image to edit/update', 'podcast-player' ) );
		$markup .= sprintf( '<a class="podcast-player-widget-img-remover %s">%s</a>', $class, esc_html__( 'Remove Featured Image', 'podcast-player' ) );
		$markup .= sprintf( '<input class="podcast-player-widget-img-id" name="%s" id="%s" value="%s" type="hidden" />', $name, $id, $value );
		return $markup;
	}

	/**
	 * Prints select list of all taxonomies for a post type.
	 *
	 * @param str   $post_type Selected post type.
	 * @param array $selected  Selected taxonomy in widget form.
	 */
	public function taxonomies_select( $post_type, $selected = [] ) {

		$options = pp_pro_get_taxonomies();

		// Get HTML classes for select options.
		$taxonomies = get_taxonomies( [], 'objects' );
		$classes    = wp_list_pluck( $taxonomies, 'object_type', 'name' );
		if ( $post_type && 'page' !== $post_type ) {
			foreach ( $classes as $name => $type ) {
				$type = (array) $type;
				if ( ! in_array( $post_type, $type, true ) ) {
					$type[]           = 'podcast-player-hidden';
					$classes[ $name ] = $type;
				}
			}
		}
		$classes[''] = 'always-visible';

		$markup = '';
		// Taxonomy Select markup.
		$markup .= $this->label( 'pp_taxonomy', esc_html__( 'Get Episodes by Taxonomy', 'podcast-player' ), false );
		$markup .= $this->select( 'pp_taxonomy', $options, $selected, $classes, false );
		return $markup;
	}

	/**
	 * Prints a checkbox list of all terms for a taxonomy.
	 *
	 * @param str   $taxonomy       Selected Taxonomy.
	 * @param array $selected_terms Selected Terms.
	 */
	public function terms_checklist( $taxonomy, $selected_terms = [] ) {

		// Get list of all registered terms.
		$terms = get_terms();

		// Get 'checkbox' options as value => label.
		$options = wp_list_pluck( $terms, 'name', 'slug' );

		// Get HTML classes for checkbox options.
		$classes = wp_list_pluck( $terms, 'taxonomy', 'slug' );
		if ( $taxonomy ) {
			foreach ( $classes as $slug => $taxon ) {
				if ( $taxonomy !== $taxon ) {
					$classes[ $slug ] .= ' podcast-player-hidden';
				}
			}
		}

		$markup = '';
		// Terms Checkbox markup.
		$markup .= $this->label( 'pp_terms', esc_html__( 'Select Terms', 'podcast-player' ), false );
		$markup .= $this->mu_checkbox( 'pp_terms', $options, $selected_terms, $classes, false );
		return $markup;
	}

	/**
	 * Markup for multiple checkbox for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Checkbox input HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function mu_checkbox( $for, $options, $selected = [], $classes = [], $echo = true ) {

		$final_class = '';

		$mu_checkbox = '<div class="' . esc_attr( $for ) . '-checklist"><ul id="' . esc_attr( $this->get_field_id( $for ) ) . '">';

		$selected    = array_map( 'strval', $selected );
		$rev_options = $options;

		// Moving selected items on top of the array.
		foreach ( $options as $id => $label ) {
			if ( in_array( strval( $id ), $selected, true ) ) {
				$rev_options = [ $id => $label ] + $rev_options;
			}
		}

		foreach ( $rev_options as $id => $label ) {
			if ( isset( $classes[ $id ] ) ) {
				$final_class = ' class="' . esc_attr( $classes[ $id ] ) . '"';
			}
			$mu_checkbox .= "\n<li$final_class>" . '<label class="selectit"><input value="' . esc_attr( $id ) . '" type="checkbox" name="' . esc_attr( $this->get_field_name( $for ) ) . '[]"' . checked( in_array( strval( $id ), $selected, true ), true, false ) . ' /> ' . esc_html( $label ) . "</label></li>\n";
		}
		$mu_checkbox .= "</ul></div>\n";

		if ( $echo ) {
			echo $mu_checkbox; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $mu_checkbox;
		}
	}

	/**
	 * Get display styles.
	 *
	 * @return array
	 */
	public function get_display_styles() {
		if ( ! empty( $this->styles ) ) {
			return $this->styles;
		}

		$styles = podcast_player_default_styles();
		foreach ( $styles as $style => $args ) {
			$this->styles[ $style ]          = $args['label'];
			$this->style_supported[ $style ] = $args['support'];
		}

		return $this->styles;
	}

	/**
	 * Check if item is supported by the style.
	 *
	 * @param string $style Current display style.
	 * @param string $item  item to be checked for support.
	 * @return bool
	 */
	public function is_style_support( $style, $item ) {
		if ( ! $style ) {
			return false;
		}

		$sup_arr = $this->style_supported[ $style ];
		return in_array( $item, $sup_arr, true );
	}
}
