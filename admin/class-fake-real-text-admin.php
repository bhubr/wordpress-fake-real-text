<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://developpeur-web-toulouse.fr/
 * @since      0.2.0
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
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.2.0
	 * @param    string    $plugin_name The name of this plugin.
	 * @param    string    $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->has_wpml = function_exists('icl_object_id');
	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  0.2.0
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
	 * @since  0.2.0
	 */
	public function display_tools_page() {
			include_once 'partials/fake-real-text-admin-display.php';
	}

	/**
	 * Generate a post (called via AJAX request)
	 * @since  0.2.0
	 */
	public function generate_posts() {

		// Must be admin
		if( ! current_user_can('manage_options') ) {
			header("HTTP/1.1 403 Forbidden" );
			exit;
		}

		// Call generator function with right options
		$post = $this->generate_fake_posts( [
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
	 * Call the relevant function according to WPML being enabled or not
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $options    Options for fake data and post generation.
	 */
	protected function generate_fake_posts( $options ) {

		return $this->has_wpml ?
			$this->generate_multilingual( $options ) :
			$this->generate_monolingual( $options );

	}

	/**
	 * Generate fake posts for only language
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $options    Options for fake data and post generation.
	 */
	protected function generate_monolingual( $options ) {
		$post = $this->generate_fake_post( $options );
		if( ! $post ) {
			return false;
		}
		return [
			[
				'post' => $post,
				'lang' => substr( get_locale(), 0, 2 )
			]
		];
	}

	/**
	 * Get active language from list
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $all_languages    WPML languages
	 */
	public function get_active_language( $all_languages ) {
		$active_languages = array_filter( $all_languages, function( $v, $k ) {
			return $v['active'];
		}, ARRAY_FILTER_USE_BOTH );
		$keys = array_keys( $active_languages );

		return $active_languages[ $keys[0] ];
	}

	/**
	 * Prepare an array to store multilingual generation results
	 *
	 * @since    0.3.0
	 */
	protected function setup_multi_results() {
		$this->generated_posts = [];
	}

	/**
	 * Add an entry to array
	 *
	 * @since    0.3.0
	 */
	protected function add_multi_results( $post, $lang ) {
		$this->generated_posts[] = [
			'post'   => $post,
			'lang'   => $lang
		];
	}

	/**
	 * Generate fake posts for all languages
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $options    Options for fake data and post generation.
	 */
	protected function generate_multilingual( $options ) {

		// Get all languages AND active language
		$languages = icl_get_languages();
		$active_lang = $this->get_active_language( $languages );
		$active_code = $active_lang['code'];

		// Setup empty result array;
		$this->setup_multi_results();

		// FIRST create a post for active language. This must be so in order to get a trid
		// (translation group id)
		list( $post, $trid ) = $this->generate_for_lang( $options, $active_lang, $active_code );
		if( ! $post ) {
			return false;
		}

		// Add it to results array
		$this->add_multi_results( $post, $active_code );

		// Iterate other languages
		foreach ( $languages as $code => $lang ) {

			// Skip the active lang
			if( $code === $active_code ) {
				continue;
			}

			// Generate for this lang, providing active lang code and trans. group id
			list( $post, $trid ) = $this->generate_for_lang( $options, $lang, $active_code, $trid );
			if( ! $post ) {
				return false;
			}

			$this->add_multi_results( $post, $lang['code'] );
		}

		return $this->generated_posts;

	}

	/**
	 * Generate for specified language
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $options     Options for fake data and post generation.
	 * @var      array    $lang        WPML language descriptor (code, active, flag...)
	 * @var      string   $active_code Active language's code (fr, en...)
	 * @var      string   $trid        WPML translation group id
	 */
	protected function generate_for_lang( $options, $lang, $active_code, $trid = 0 ) {

		// Add locale to options for post generation
		$locale = $lang['default_locale'];
		$options['locale'] = $locale;

		// Generate post
		$post = $this->generate_fake_post( $options );
		if( ! $post ) {
			return [false, false];
		}

		// Get icl_translations entry
		$icl_tr_entry = $this->get_icl_translations_entry( $post->ID );

		// If this is the active language, return immediately with trid
		if( $lang['code'] === $active_code ) {
			return [ $post, $icl_tr_entry->trid ];
		}

		// Otherwise we must update the translation entry, to bind this post to the original
		$updated = $this->update_icl_translation_entry( $icl_tr_entry->translation_id, $trid, $active_code, $lang['code'] );
		return [ $post, $trid ];
	}

	/**
	 * Get the wp_icl_translations entry created by WPML for a post
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      int    $post_id    ID of post for which to retrieve translation entry
	 */
	protected function get_icl_translations_entry( $post_id ) {
		global $wpdb;
		$entries = $wpdb->get_results( "SELECT * from {$wpdb->prefix}icl_translations WHERE element_id = $post_id", OBJECT );
		return $entries[0];
	}

	/**
	 * Update the wp_icl_translations entry for a post
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $options    Options for fake data and post generation.
	 */
	protected function update_icl_translation_entry( $translation_id, $trid, $source_code, $code ) {
		global $wpdb;
		$data = [
			'trid' => $trid,
			'source_language_code' => $source_code,
			'language_code' => $code
		];
		return $wpdb->update( "{$wpdb->prefix}icl_translations", $data, [ 'translation_id' => $translation_id ] );
	}

	/**
	 * Generate a fake post
	 *
	 * @since  0.2.0
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
	 * @since    0.2.0
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
	 * @since    0.2.0
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
