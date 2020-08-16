<?php
/**
 * The admin-options page of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player;

/**
 * The admin-options page of the plugin.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Options {

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
		add_action( 'admin_menu', [ self::get_instance(), 'add_options_page' ] );
		add_action( 'admin_init', [ self::get_instance(), 'add_settings' ] );
		add_action( 'admin_init', [ self::get_instance(), 'implement_options' ] );
		add_action( 'podcast_player_options_page_content', [ self::get_instance(), 'display_content' ] );
	}

	/**
	 * Add plugin specific options page.
	 *
	 * @since    1.5
	 */
	public function add_options_page() {
		add_options_page(
			esc_html__( 'Podcast Player', 'podcast-player' ),
			esc_html__( 'Podcast Player', 'podcast-player' ),
			'manage_options',
			'pp-options',
			array( $this, 'pp_options' )
		);
	}

	/**
	 * Display podcast player options page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings() {
		// Register settings with Validation callback.
		register_setting(
			'podcast-player-options',
			'pp-legacy-player',
			array( 'sanitize_callback' => array( $this, 'validate_settings' ) )
		);

		// Settings section.
		add_settings_section(
			'podcast-player-general-section',
			esc_html__( 'Podcast Player Options', 'podcast-player' ),
			array( $this, 'display_section' ),
			'pp-options'
		);

		add_settings_field(
			'pp-legacy-player',
			esc_html__( 'Switch back to legacy player', 'podcast-player' ),
			array( $this, 'display_setting' ),
			'pp-options',
			'podcast-player-general-section',
			array( 'id' => 'pp-legacy-player' ) // Extra arguments used when outputting the field.
		);

		register_setting(
			'pp-options-group',
			'pp-common-options',
			array( 'sanitize_callback' => array( $this, 'sanitize_common_options' ) )
		);
	}

	/**
	 * Function to validate plugin options.
	 *
	 * @since    1.0.0
	 *
	 * @param bool $input Checkbox option.
	 */
	public function validate_settings( $input ) {
		return $input ? 'on' : '';
	}

	/**
	 * Function to validate plugin options.
	 *
	 * @since    1.0.0
	 *
	 * @param array|false $val Podcast Option Value.
	 */
	public function sanitize_common_options( $val ) {
		if ( $val && is_array( $val ) ) {
			$val['feed_url']    = isset( $val['feed_url'] ) ? esc_url_raw( $val['feed_url'] ) : '';
			$val['feed_action'] = isset( $val['feed_action'] ) ? sanitize_text_field( $val['feed_action'] ) : '';
		}
		return $val;
	}

	/**
	 * Function to validate plugin options.
	 *
	 * @since 1.0.0
	 */
	public function implement_options() {
		$val = get_option( 'pp-common-options' );
		if ( $val && is_array( $val ) ) {
			$feed_url = isset( $val['feed_url'] ) ? $val['feed_url'] : false;
			while ( stristr( $feed_url, 'http' ) !== $feed_url ) {
				$feed_url = substr( $feed_url, 1 );
			}

			$fprn = $feed_url ? md5( $feed_url ) : false;
			$data = $fprn ? 'pp_feed_data_' . $fprn : false;
			$time = $fprn ? 'pp_feed_time_' . $fprn : false;

			if ( isset( $val['feed_action'] ) ) {
				if ( 'reset' === $val['feed_action'] ) {
					if ( $data ) {
						delete_option( $data );
					}
				} else {
					if ( $time ) {
						delete_transient( $time );
					}
				}
			}
			delete_option( 'pp-common-options' );
			$base_url = admin_url( 'options-general.php?page=pp-options' );
			$redirect = add_query_arg(
				array(
					'ppoptionmsg' => rawurlencode( esc_html__( 'Action Successful', 'podcast-player' ) ),
				),
				$base_url
			);
			wp_safe_redirect( $redirect );
			exit();
		}
	}

	/**
	 * Function to add extra text to display on podcast player section.
	 *
	 * @since    1.0.0
	 */
	public function display_section() {
		printf(
			'<p>%1$s</p><p>%2$s</p><p>%3$s<a href="mailto:contact@vedathemes.com">contact@vedathemes.com</a></p>',
			esc_html__( 'We observe that you are still using legacy version of podcast player. Although this version is well supported, still, we recommend you to try the new version for many new and improved features.', 'podcast-player' ),
			esc_html__( 'Please note that Podcast Player Pro is NOT compatible with legacy version. Therefore, kindly do not purchase if you plan to use leagacy version only.', 'podcast-player' ),
			esc_html__( 'Also, kindly help us to improve the latest design. Give your improvement suggestions at ', 'podcast-player' )
		);
	}

	/**
	 * Function to add options page content.
	 *
	 * @since    1.0.0
	 */
	public function display_content() {
		include_once PODCAST_PLAYER_DIR . '/backend/partials/pp-options-page.php';
	}

	/**
	 * Function to display the settings on the page.
	 *
	 * @since    1.0.0
	 */
	public function display_setting() {
		$option = get_option( 'pp-legacy-player' );

		$checked = '';
		if ( $option && 'on' === $option ) {
			$checked = 'checked="checked"';
		}

		echo '<label class="switch">';
		echo '<input type="checkbox" name="pp-legacy-player" ' . $checked . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</label>';
	}

	/**
	 * Render Manta Plus settings page.
	 *
	 * @since    1.0.0
	 */
	public function pp_options() {
		$option = get_option( 'pp-legacy-player' );

		if ( $option && 'on' === $option ) {
			printf( '<form action="options.php" method="post">' );
			settings_fields( 'podcast-player-options' );
			do_settings_sections( 'pp-options' );
			submit_button( esc_html__( 'Save', 'podcast-player' ) );
			echo '</form>';
		} else {
			do_action( 'podcast_player_options_page_content', 'pp-options' );
		}
	}

	/**
	 * Add Podcast reset options.
	 *
	 * @since    2.6.0
	 */
	public function podcast_reset_options() {
		$status = false;
		if ( isset( $_GET['ppoptionmsg'] ) ) {
			$status = sanitize_text_field( wp_unslash( $_GET['ppoptionmsg'] ) );
		}
		?>
		<h3><?php esc_html_e( 'Feed Updation Tool', 'pp-pro' ); ?></h3>
		<form method="post" action="options.php">
			<?php settings_fields( 'pp-options-group' ); ?>
			<div class="feed-updation-form">
				<?php if ( $status ) : ?>
					<div style="color: green;"><span> <?php echo esc_html( $status ); ?> </span></div>
				<?php endif; ?>
				<input id="pp-options-feed-url" name="pp-common-options[feed_url]" type="text" placeholder="<?php esc_html_e( 'Enter Your Feed url', 'pp-pro' ); ?>" class="regular-text" value="" />
				<select id="pp-options-feed-action" name="pp-common-options[feed_action]">
					<option value=""><?php esc_html_e( 'Update Only', 'podcast-player' ); ?></option>
					<option value="reset"><?php esc_html_e( 'Full Reset', 'podcast-player' ); ?></option>
				</select>
			</div>
			<?php submit_button( esc_html__( 'Update Now', 'podcast-player' ) ); ?>
		</form>
		<?php
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

Options::init();
