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
	 * Generate a fake post
	 *
	 * @since  1.0.0
	 */
	public function generate_fake_post( $post_type = 'post', $locale = '' ) {

		// Set a default locale if none provided
		if( empty( $locale ) ) {
			$locale = get_locale();
		}

		// Check that provided post type is valid
		$wp_types = get_post_types();
		if( ! array_key_exists( $post_type, $wp_types ) ) {
			throw new Exception( "Invalid post type $post_type" );
		}

		// Initialize faker with locale
		$faker = Faker\Factory::create( $locale );

		// Get WordPress registered users
		$users = get_users();
		$user_index = rand(0, count( $users ) - 1);

		// Generate fake post data
		$fake_post_data = [
			'post_author'  => $users[ $user_index ]->ID,
			'post_title'   => $faker->realText($faker->numberBetween(20, 40)),
			'post_content' => $faker->realText($faker->numberBetween(300, 1000)),
			'post_status'  => 'publish',
			'post_type'    => $post_type,
			'post_date'    => $faker->dateTimeInInterval('-1 year', '+1 year')->format('Y-m-d')
		];

		// var_dump($fake_post_data);
		return wp_insert_post( $fake_post_data );
		// var_dump($faker->dateTimeInInterval('-1 month', '+10 days'));

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fake-real-text-admin.js', array( 'jquery' ), $this->version, false );

	}

}
