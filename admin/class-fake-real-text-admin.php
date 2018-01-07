<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://developpeur-web-toulouse.fr/
 * @since      1.0.0
 *
 * @package    Fake_Real_Text
 * @subpackage Fake_Real_Text/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fake_Real_Text
 * @subpackage Fake_Real_Text/admin
 * @author     Benoît Hubert <benoithubert@gmail.com>
 */
class Fake_Real_Text_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

		/**
		 * Add an options page under the Settings submenu
		 *
		 * @since  1.0.0
		 */
		public function add_tools_page() {

			$this->plugin_screen_hook_suffix = add_management_page(
				__( 'Fake Real Text', 'fake-real-text' ),
				__( 'Fake Real Text', 'fake-real-text' ),
				'manage_options',
				$this->plugin_name,
				array( $this, 'display_tools_page' )
			);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_tools_page() {
			include_once 'partials/fake-real-text-admin-display.php';
	}

	/**
	 * Generate a post (called via AJAX request)
	 */
	public function generate_posts() {

		// Must be admin
		if( ! current_user_can('manage_options') ) {
			header("HTTP/1.1 403 Forbidden" );
			exit;
		}

		// Call generator function with right options
		$post = $this->generate_fake_post( [
			'time_interval' => $_POST['time_interval'],
			'post_type'     => $_POST['post_type']
		] );

		if( ! $post ) {
			header("HTTP/1.1 400 Bad Request" );
			return json_encode( [
				'error' => 'Post could not be created'
			] );
		}

		echo json_encode( $post );
		exit;
	}


	/**
	 * Generate a fake post
	 *
	 * @since  1.0.0
	 */
	protected function generate_fake_post( $options ) {

		// Set default options
		$default_options = [
			'post_type'     => 'post',
			'locale'        => get_locale(),
			'time_interval' => '1 month'
		];
		$options = array_merge( $default_options, $options );

		// Check that provided post type is valid
		// This shouldn't happen unless a request is forged
		$wp_types = get_post_types();
		if( ! array_key_exists( $options['post_type'], $wp_types ) ) {
			throw new Exception( "Invalid post type $post_type" );
		}

		// Initialize faker with locale
		$faker = Faker\Factory::create( $options['locale'] );

		// Get WordPress registered users
		$users = get_users();
		$user_index = rand(0, count( $users ) - 1);

		// Generate fake post data
		$intv_start = '-' . $options['time_interval'];
		$intv_duration = '+' . $options['time_interval'];
		$fake_post_data = [
			'post_author'  => $users[ $user_index ]->ID,
			'post_title'   => $faker->realText($faker->numberBetween(30, 50)),
			'post_content' => $faker->realText($faker->numberBetween(300, 1000)),
			'post_status'  => 'publish',
			'post_type'    => $options['post_type'],
			'post_date'    => $faker->dateTimeInInterval($intv_start, $intv_duration)->format('Y-m-d')
		];

		$post_id = wp_insert_post( $fake_post_data );

		// Handle error
		if( $post_id === 0 || ! is_int( $post_id ) ) {
			return false;
		}

		// Add a meta data to indicate this is a fake post
		add_post_meta($post_id, '_frt_fake', 1, true);

		// Get generated post from db and send it
		$post = get_post( $post_id );
		return $post;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fake_Real_Text_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fake_Real_Text_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fake-real-text-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fake_Real_Text_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fake_Real_Text_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$with_timestamp = WP_DEBUG ? '?ts=' . time() : '';
		wp_enqueue_script( 'async', plugin_dir_url( __FILE__ ) . 'js/async.min.js', array( 'jquery' ), "2.6.0", false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fake-real-text-admin.js' . $with_timestamp, array( 'jquery', 'async' ), $this->version, false );

	}

}
