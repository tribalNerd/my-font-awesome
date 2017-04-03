<?php
/**
 * Plugin Name: My Font Awesome
 * Plugin URI: https://github.com/tribalNerd/my-font-awesome
 * Description: An easy and clean way to add Font Awesome to your theme.
 * Tags: technerdia, tribalnerd
 * Version: 0.1.0
 * License: GNU GPLv3
 * Copyright (c) 2017 Chris Winters
 * Author: tribalNerd, Chris Winters
 * Author URI: http://techNerdia.com/
 * Text Domain: my-font-awesome
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Define Constants
 */
if( function_exists( 'MyFontAwesomeConstants' ) )
{
    MyFontAwesomeConstants( Array(
        'MY_FONT_AWESOME_BASE_URL'          => get_bloginfo( 'url' ),
        'MY_FONT_AWESOME_VERSION'           => '0.1.0',
        'MY_FONT_AWESOME_WP_MIN_VERSION'    => '3.8',

        'MY_FONT_AWESOME_PLUGIN_FILE'       => __FILE__,
        'MY_FONT_AWESOME_PLUGIN_DIR'        => dirname( __FILE__ ),
        'MY_FONT_AWESOME_PLUGIN_BASE'       => plugin_basename( __FILE__ ),

        'MY_FONT_AWESOME_MENU_NAME'         => __( 'My Font Awesome', 'my-font-awesome' ),
        'MY_FONT_AWESOME_PAGE_NAME'         => __( 'My Font Awesome', 'my-font-awesome' ),
        'MY_FONT_AWESOME_PAGE_DESC'         => __( 'An easy and clean way to add Font Awesome to your WordPress theme.', 'my-font-awesome' ),
        'MY_FONT_AWESOME_OPTION_NAME'       => 'myfontawesome_',
        'MY_FONT_AWESOME_PLUGIN_NAME'       => 'my-font-awesome',

        'MY_FONT_AWESOME_CLASSES'           => dirname( __FILE__ ) .'/classes',
        'MY_FONT_AWESOME_TEMPLATES'         => dirname( __FILE__ ) .'/templates'
    ) );
}


/**
 * @about Loop Through Constants
 */
function MyFontAwesomeConstants( $constants_array )
{
    foreach( $constants_array as $name => $value ) {
        define( $name, $value, true );
    }
}


/**
 * @about Register Classes & Include
 */
spl_autoload_register( function ( $class )
{
    if( strpos( $class, 'MyFontAwesome_' ) !== false ) {
        $class_name = str_replace( 'MyFontAwesome_', "", $class );

        // If the Class Exists, Include the Class
        if( file_exists( MY_FONT_AWESOME_CLASSES .'/class-'. strtolower( $class_name ) .'.php' ) ) {
            include_once( MY_FONT_AWESOME_CLASSES .'/class-'. strtolower( $class_name ) .'.php' );
        }
    }
} );


/**
 * @about Run Plugin
 */
if( ! class_exists( 'MY_FONT_AWESOME' ) )
{
    class MY_FONT_AWESOME
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Initiate Plugin
         */
        final public function init()
        {
            // Activate Plugin
            register_activation_hook( __FILE__, array( $this, 'activate' ) );

            // Inject Plugin Links
            add_filter( 'plugin_row_meta', array( $this, 'links' ), 10, 2 );

            // Load Admin Area
            MyFontAwesome_AdminArea::instance();

            // Manage Settings
            MyFontAwesome_Process::instance();

            // Display Font Awesome CSS
            MyFontAwesome_Core::instance();
        }


        /**
         * @about Activate Plugin
         */
        final public function activate()
        {
            // Wordpress Version Check
            global $wp_version;

            // Version Check
            if( version_compare( $wp_version, MY_FONT_AWESOME_WP_MIN_VERSION, "<" ) ) {
                wp_die( __( '<b>Activation Failed</b>: The ' . MY_FONT_AWESOME_PAGE_NAME . ' plugin requires WordPress version ' . MY_FONT_AWESOME_WP_MIN_VERSION . ' or higher. Please Upgrade Wordpress, then try activating this plugin again.', 'my-font-awesome' ) );
            }
        }


        /**
         * @about Inject Links Into Plugin Admin
         * @param array $links Default links for this plugin
         * @param string $file The name of the plugin being displayed
         * @return array $links The links to inject
         */
        final public function links( $links, $file )
        {
            // Get Current URL
            $request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

            // Links To Inject
            if ( $file == MY_FONT_AWESOME_PLUGIN_BASE && strpos( $request_uri, "plugins.php" ) !== false ) {
                $links[] = '<a href="options-general.php?page=' . MY_FONT_AWESOME_PLUGIN_NAME . '">'. __( 'Website Settings', 'my-font-awesome' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/efa/#faq" target="_blank">'. __( 'F.A.Q.', 'my-font-awesome' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/help/" target="_blank">'. __( 'Support', 'my-font-awesome' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/feedback/" target="_blank">'. __( 'Feedback', 'my-font-awesome' ) .'</a>';
            }

            return $links;
        }


        /**
        * @about Create Instance
        */
        final public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }
    }
}

add_action( 'after_setup_theme', array( 'MY_FONT_AWESOME', 'instance' ), 0 );
