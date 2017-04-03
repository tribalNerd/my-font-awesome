<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Process Form Updates
 * @location classes/my-font-awesome.php
 * @call MyFontAwesome_Process::instance();
 * 
 * @method init()           Start Admin Bar Manager
 * @method message()        Display Messages To User
 * @method update()         Update Option
 * @method instance()       Create Instance
 */
if( ! class_exists( 'MyFontAwesome_Process' ) )
{
    class MyFontAwesome_Process extends MyFontAwesome_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Start Admin Bar Manager
         */
        final public function init()
        {
            // Process Plugin Disable/Enable, Delete Settings
            if ( filter_input( INPUT_POST, 'type' ) && parent::qString( 'page' ) == $this->plugin_name ) {
                // Form Security Check
                parent::validate();

                add_action( 'admin_init', array( $this, 'update') );
            }
        }

        /**
         * @about Display Messages To User
         * @param string $slug Which switch to load
         * @param string $notice_type Either updated/error
         */
        final public function message( $slug, $notice_type = false )
        {
            // Clear Message
            $message = '';

            // Message Switch
            switch ( $slug ) {
                case 'websiteupdate':
                    $message = __( '<u>Success</u>: Settings have been updated.', 'my-font-awesome' );
                break;

                case 'websiteclear':
                    $message = __( '<u>Notice</u>: All settings have been cleared.', 'my-font-awesome' );
                break;

                case 'websitefail':
                    $message = __( '<u>Notice</u>: No settings selected or updated.', 'my-font-awesome' );
                break;

                case 'websitedelete':
                    $message = __( '<u>Notice</u>: All My Font Awesome settings have been deleted.', 'my-font-awesome' );
                break;
            }

            // Throw Message
            if ( ! empty( $message ) ) {
                // Set Message Type, Default Error
                $type = ( $notice_type == "updated" ) ? "updated" : "error";

                // Return Message
                add_settings_error( $slug, $slug, $message, $type );
            }
        }


        /**
         * @about Update Option
         */
        final public function update()
        {
            // Update Settings
            if ( filter_input( INPUT_POST, 'type' ) == "update" ) {
                // Get Post Data
                $settings = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Remove Items
                unset( $settings['myfontawesome_nonce'] );
                unset( $settings['_wp_http_referer'] );
                unset( $settings['submit'] );
                unset( $settings['type'] );

                // Clear Inputs If Not Set
                if ( empty( $settings['page_ids'] ) ) { unset( $settings['page_ids'] ); }
                if ( empty( $settings['post_ids'] ) ) { unset( $settings['post_ids'] ); }
                if ( empty( $settings['pt_ids'] ) ) { unset( $settings['pt_ids'] ); }

                if ( ! empty( $settings ) ) {

                    // No Id's Passed In
                    if ( empty( $settings['ids'] ) ) { unset( $settings['ids'] ); }

                    // Update Settings
                    update_option( $this->option_name . 'settings', $settings, true );
     
                    // Display Message
                    $this->message( 'websiteupdate', 'updated' );

                } else {
                    if ( get_option( $this->option_name . 'settings' ) ) {
                        // Remove Option
                        delete_option( $this->option_name . 'settings' );

                        // Display Message
                        $this->message( 'websiteclear', 'updated' );

                    } else {
                        // Display Message
                        $this->message( 'websitefail', 'error' );
                    }

                }
            }

            // Delete Settings
            if ( filter_input( INPUT_POST, 'type' ) == "delete" ) {
                delete_option( $this->option_name . 'settings' );

                // Display Message
                $this->message( 'websitedelete', 'updated' );
            }
        }


        /**
         * @about Create Instance
         */
        public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }
    }
}
