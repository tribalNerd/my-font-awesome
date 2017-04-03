<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Admin Area Display
 * @location my-font-awesome.php
 * @call MyfoNtAwesome_AdminArea::instance();
 * 
 * @method init()       Init Admin Actions
 * @method redirect()   Font Awesome Icons
 * @method menu()       Load Admin Area Menu
 * @method enqueue()    Enqueue Stylesheet and jQuery
 * @method display()    Display Website Admin Templates
 * @method tabs()       Load Admin Area Tabs
 * @method instance()   Class Instance
 */
if ( ! class_exists( 'MyfoNtAwesome_AdminArea' ) )
{
    class MyfoNtAwesome_AdminArea extends MyfoNtAwesome_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;

        // Tab Names
        private $tabs;


        /**
         * @about Init Admin Actions
         */
        final public function init()
        {
            // Website Menu Link
            add_action( 'admin_menu', array( $this, 'menu' ) );

            // Unqueue Scripts Within Plugin Admin Area
            if ( parent::qString( 'page' ) == $this->plugin_name ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
            }

            // Tabs Names: &tab=home
            $this->tabs = array(
                'home'      => __( 'Settings', 'my-font-awesome' ),
                'efaicons'  => __( 'Icons', 'my-font-awesome' )
            );

            // Redirect if Network Tab is Opened
            if ( $this->qString( 'tab' ) == 'efaicons' ) {
                $this->redirect();
            }
        }


        /**
         * @about Font Awesome Icons
         */
        final private function redirect()
        {
            wp_redirect( 'http://fontawesome.io/icons/' );
            exit;
        }


        /**
         * @about Admin Menus
         */
        final public function menu()
        {
            add_submenu_page(
                'options-general.php',
                $this->plugin_title,
                $this->menu_name,
                'manage_options',
                $this->plugin_name,
                array( $this, 'display' )
            );
        }


        /**
         * @about Enqueue Stylesheet & Javascript
         */
        final public function enqueue()
        {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( $this->plugin_file ) . 'assets/style.css', '', date( 'YmdHis', time() ), 'all' );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( $this->plugin_file ) . 'assets/jquery.js', false, date( 'YmdHis', time() ), false );
        }


        /**
         * @about Display Website Admin Templates
         */
        final public function display()
        {
            // Admin Header
            require_once( $this->templates .'/header.php' );

            // Switch Between Tabs
            switch ( parent::qString( 'tab' ) ) {
                case 'home':
                default:
                    require_once( $this->templates .'/home.php' );
                break;
            }

            // Admin Footer
            require_once( $this->templates .'/footer.php' );
        }


        /**
         * @about Admin Area Tabs
         * @return string $html Tab Display
         */
        final public function tabs()
        {
            $html = '<h2 class="nav-tab-wrapper">';

            // Set Current Tab
            $current = ( parent::qString( 'tab' ) ) ? parent::qString( 'tab' ) : key( $this->tabs );

            foreach( $this->tabs as $tab => $name ) {
                // Current Tab Class
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';

                // Open Icon Tab In New Window
                $target = ( $tab == 'faicons' ) ? 'target="_blank"' : '';

                // Tab Links
                $html .= '<a href="?page='. parent::qString( 'page' ) .'&tab='. $tab .'" class="nav-tab'. $class .'" ' . $target . '>'. $name .'</a>';
            }

            $html .= '</h2><br />';

            return $html;
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
