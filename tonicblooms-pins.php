<?php
/*
Plugin Name: tonicblooms-pins
Plugin URI: tonicblooms.com
Description: handle the delivery zones for tonicblooms
Version: 1.0
Author: Safecaches
Author URI: Safecaches.com
Author Email: Safecaches@gmail.com
License:

  Copyright 2016 tonicblooms (email@domain.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * TODO:
 *
 * Rename this class to a proper name for your plugin. Give a proper description of
 * the plugin, it's purpose, and any dependencies it has.
 *
 * Use PHPDoc directives if you wish to be able to document the code using a documentation
 * generator.
 *
 * @version	1.0
 */
if (!defined('TONICBLOOMSPINS')) {
	
	define("TONICBLOOMSPINS", "TONICBLOOMSPINS");
	
	class TonicbloomsPins {

		/*--------------------------------------------*
		 * Static variables
		 *--------------------------------------------*/

		 static $yellow = [
		 'M5R', 'M5S', 'M4V', 'M7A',
		 'M4Y', 'M5G', 'M5T', 'M6G',
		 'M4T', 'M4W', 'M4X', 'M5B',
		 'M5C', 'M5H', 'M5K', 'M5L',
		 'M5V', 'M5X', 'M6H', 'M6J',
		 'M5E', 'M5W', 'M6K', 'M6R',
		 'M5A', 'M5J'
	 	];

		static $pink = [
		 'M5P', 'M6C', 'M4K', 'M4R', 'M4S', 'M5N', 
		 'M6E', 'M4J', 'M4M', 'M4P', 'M6B', 'M4H', 
		 'M4L', 'M5M', 'M6S', 'M4C', 'M4G', 'M4N', 
		 'M6A', 'M6N', 'M3C', 'M3K', 'M4B', 'M6M', 
		 'M8X', 'M2P', 'M6L', 'M9A', 'M1N', 'M2L', 
		 'M2N', 'M3B', 'M3H', 'M9N', 'M2R', 'M4A', 
		 'M4E', 'M8V', 'M9P', 'M3L', 'M3M', 'M9B', 
		 'M2K', 'M2M', 'M3A', 'M3J', 'M8Y', 'M9R', 
		 'M1L', 'M1K', 'M1R', 'M3N', 'M8Z', 'M9M', 
		 'L4J', 'M2J', 'M9V', 'M9W', 'L3T', 'M9L', 
		 'M1J', 'M1M', 'M1T', 'M8W', 'M6P'
	 	];

		static $white = [
		 'L4Y', 'L5E', 'M2H', 'L4C', 'L4X', 'M1P', 
		 'M1W', 'L4V', 'L4W', 'M9C', 'L5A', 'M1E', 
		 'L4K', 'L4T', 'L5G', 'M1H', 'M1S', 'L5B', 
		 'L5P', 'L5S', 'L5T', 'L4Z', 'L6G', 'M1G', 
		 'M1V', 'L3R', 'M1C', 'L4B', 'L4L', 'L5C', 
		 'L5H', 'L5W', 'M1B', 'L5K', 'L5R', 'L6A', 
		 'L3S', 'L4H', 'L5J', 'L5V', 'L6W', 'L4S', 
		 'L5L', 'L6T', 'M1X', 'L6Y', 'L5N', 'L6C', 
		 'L6V', 'L6E', 'L6P', 'L6S', 'L5M', 'L6X', 
		 'L6Z', 'L6R', 'L3P', 'L6B', 'L7A'
	 	];

		static $yellowPinsCutOffTime = array(
				"hours" => 17,
				"minutes"=> 00
		);
		static $whiteAndPinkPinsCutOffTime = array(
				"hours" => 12,
				"minutes"=> 00	
		);

		static $cutOffTimeSafety = 10; // time in minutes that will be substracted to the cutofftime for safety

		static $shippingRateByPin = [
			"yellow" => [
				"id" => "free_shipping",
				"cost" => 0.0,
				"taxes" => [],
				"label" => "Free",
				"method_id" => "free_shipping"
			],
			"pink" => [
				"id" => "flat_rate:2",
				"cost" => 7.99,
				"taxes" => [1.0387],
				"label" => "Flat Rate",
				"method_id" => "flat_rate"
			],
			"white" => [
				"id" => "flat_rate:3",
				"cost" => 13.99,
				"taxes" => [1.8187],
				"label" => "Flat Rate",
				"method_id" => "flat_rate"
			]
		];

		/*--------------------------------------------*
		 * Attributes
		 *--------------------------------------------*/

		/** Refers to a single instance of this class. */
		private static $instance = null;

		/** Refers to the slug of the plugin screen. */
		private $plugin_screen_slug = null;

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @return	PluginName	A single instance of this class.
		 */
		public function get_instance() {
			return null == self::$instance ? new self : self::$instance;
		} // end get_instance;

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		private function __construct() {

			// Load plugin text domain
			add_action( 'init', array( $this, 'plugin_textdomain' ) );

		    /*
		     * Add the options page and menu item.
		     * Uncomment the following line to enable the Settings Page for the plugin:
		     */
		     //add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ) );

		    /*
			 * Register admin styles and scripts
			 * If the Settings page has been activated using the above hook, the scripts and styles
			 * will only be loaded on the settings page. If not, they will be loaded for all
			 * admin pages.
			 *
			 * add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
			 * add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
			 */

			// Register site stylesheets and JavaScript
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		    /*
		     * TODO:
		     *
		     * Define the custom functionality for your plugin. The first parameter of the
		     * add_action/add_filter calls are the hooks into which your code should fire.
		     *
		     * The second parameter is the function name located within this class. See the stubs
		     * later in the file.
		     *
		     * For more information:
		     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		     */
		    add_action( 'TODO', array( $this, 'action_method_name' ) );
		    add_filter( 'TODO', array( $this, 'filter_method_name' ) );

		} // end constructor

		/**
		 * Fired when the plugin is activated.
		 *
		 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
		 */
		public function activate( $network_wide ) {
			// TODO:	Define activation functionality here
		} // end activate

		/**
		 * Fired when the plugin is deactivated.
		 *
		 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
		 */
		public function deactivate( $network_wide ) {
			// TODO:	Define deactivation functionality here
		} // end deactivate

		/**
		 * Loads the plugin text domain for translation
		 */
		public function plugin_textdomain() {

			// TODO: replace "plugin-name-locale" with a unique value for your plugin
			$domain = 'plugin-name-locale';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		} // end plugin_textdomain

		/**
		 * Registers and enqueues admin-specific styles.
		 */
		public function register_admin_styles() {

			/*
			 * Check if the plugin has registered a settings page
			 * and if it has, make sure only to enqueue the scripts on the relevant screens
			 */

		    if ( isset( $this->plugin_screen_slug ) ){

		    	/*
				 * Check if current screen is the admin page for this plugin
				 * Don't enqueue stylesheet or JavaScript if it's not
				 */

				 $screen = get_current_screen();
				 if ( $screen->id == $this->plugin_screen_slug ) {
				 	wp_enqueue_style( 'plugin-name-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
				 } // end if

		    } // end if

		} // end register_admin_styles

		/**
		 * Registers and enqueues admin-specific JavaScript.
		 */
		public function register_admin_scripts() {

			/*
			 * Check if the plugin has registered a settings page
			 * and if it has, make sure only to enqueue the scripts on the relevant screens
			 */

		    if ( isset( $this->plugin_screen_slug ) ){

		    	/*
				 * Check if current screen is the admin page for this plugin
				 * Don't enqueue stylesheet or JavaScript if it's not
				 */

				 $screen = get_current_screen();
				 if ( $screen->id == $this->plugin_screen_slug ) {
				 	wp_enqueue_script( 'plugin-name-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
				 } // end if

		    } // end if

		} // end register_admin_scripts

		/**
		 * Registers and enqueues plugin-specific styles.
		 */
		public function register_plugin_styles() {
			wp_enqueue_style( 'plugin-name-plugin-styles', plugins_url( 'css/display.css', __FILE__ ) );
		} // end register_plugin_styles

		/**
		 * Registers and enqueues plugin-specific scripts.
		 */
		public function register_plugin_scripts() {
			//wp_enqueue_script( 'plugin-name-plugin-script', plugins_url( 'js/display.js', __FILE__ ), array( 'jquery' ) );
			//https://code.tutsplus.com/tutorials/how-to-pass-php-data-and-strings-to-javascript-in-wordpress--wp-34699
			wp_enqueue_script( 'tonicblooms-pins-script', plugins_url( 'js/tonicblooms-pins.js', __FILE__ ), array( 'jquery' ) );
			$dataToInject = array(
					'yellow' => TonicbloomsPins::$yellow,
			    'pink'  => TonicbloomsPins::$pink,
			    'white' => TonicbloomsPins::$white,
					'yellowPinsCutOffTime' => json_encode(TonicbloomsPins::$yellowPinsCutOffTime),
					'whiteAndPinkPinsCutOffTime' => json_encode(TonicbloomsPins::$whiteAndPinkPinsCutOffTime),
					'cutOffTimeSafety' => TonicbloomsPins::$cutOffTimeSafety
			);
			wp_localize_script( 'tonicblooms-pins-script', 'injectedData', $dataToInject );
		} // end register_plugin_scripts

		/**
		 * Registers the administration menu for this plugin into the WordPress Dashboard menu.
		 */
		public function plugin_admin_menu() {

			/*
	    	 * TODO:
	    	 *
	    	 * Change 'Page Title' to the title of your plugin admin page
	    	 * Change 'Menu Text' to the text for menu item for the plugin settings page
	    	 * Change 'plugin-name' to the name of your plugin
	    	 */

	    	$this->plugin_screen_slug = add_plugins_page(
	    		__( 'Tonicblooms Pin', 'plugin-name-locale' ),
	    		__( 'tonicblooms pins setting', 'plugin-name-locale' ),
	    		__( 'read', 'plugin-name-locale' ),
	    		__( 'tonicblooms-pins', 'plugin-name-locale' ),
	    		array( $this, 'plugin_admin_page' )
	    	);

		} // end plugin_admin_menu

		/**
		 * Renders the options page for this plugin.
		 */
		public function plugin_admin_page() {
			include_once( 'views/admin.php' );
		} // end plugin_admin_page

		/*--------------------------------------------*
		 * Core Functions
		 *---------------------------------------------*/

		/*
	 	 * NOTE:  Actions are points in the execution of a page or process
		 *        lifecycle that WordPress fires.
		 *
		 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
		 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
		 *
		 */
		public function action_method_name() {
	    	// TODO:	Define your action method here
		} // end action_method_name

		/*
		 * NOTE:  Filters are points of execution in which WordPress modifies data
		 *        before saving it or sending it to the browser.
		 *
		 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
		 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
		 *
		 */
		public function filter_method_name() {
		    // TODO:	Define your filter method here
		} // end filter_method_name

		/*--------------------------------------------*
		 * Class functions
		 *---------------------------------------------*/

		public function isAYellowPin( $aPostalCode ) { 
			return ( $this->isAColorPin( $aPostalCode, TonicbloomsPins::$yellow ) );
		}

		public function isAPinkPin( $aPostalCode ) { 
			return ( $this->isAColorPin( $aPostalCode, TonicbloomsPins::$pink ) );
		}

		public function isAWhitePin( $aPostalCode ) { 
			return ( $this->isAColorPin( $aPostalCode, TonicbloomsPins::$white ) );
		}

		public function isAColorPin( $aPostalCode, $coloredPins ) {
			$aPostalCode = str_replace( " ", "", $aPostalCode );
			$forwardSortationArea = substr( $aPostalCode, 0, 3 ); 
			$forwardSortationArea = strtoupper( $forwardSortationArea );

			return ( in_array( $forwardSortationArea, $coloredPins ) );
		}

		public function getShippingRate( $aPostalCode ) {
			if ( $this->isAYellowPin( $aPostalCode ) ) {
				return TonicbloomsPins::$shippingRateByPin["yellow"];
			} else if ( $this->isAPinkPin( $aPostalCode ) ) {
				return TonicbloomsPins::$shippingRateByPin["pink"];
			} else {
				return TonicbloomsPins::$shippingRateByPin["white"];
			}
		}

	} // end class

	// TODO:	Update the instantiation call of your plugin to the name given at the class definition
	TonicbloomsPins::get_instance();

} // END DEFINE TONICBLOOMSPINS

