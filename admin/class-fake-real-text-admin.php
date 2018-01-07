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
