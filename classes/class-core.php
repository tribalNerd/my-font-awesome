<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Core - When/Where To load Font Awesome
 * @location classes/my-font-awesome.php
 * @call MyFontAwesome_Core::instance();
 * 
 * @method init()       After WP Object Is Ready
 * @method action()     My Font Awesome Shortcode
 * @method qtag()       Add Shortcode To Text Editor
 * @method jquery()     Enqueue For Tinymce
 * @method register()   Register Tinymce Button
 * @method action()     Action To Take For Enqueue
 * @method enqueue()    Enqueue Font Awesome
 * @method instance()   Create Instance
 */
if( ! class_exists( 'MyFontAwesome_Core' ) )
{
    class MyFontAwesome_Core extends MyFontAwesome_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about After WP Object Is Ready
         */
        final public function init()
        {
            add_action( 'wp', array( $this, 'action' ) );

            // Add Shortcode [myfa]
            add_shortcode( 'myfa', array( $this, 'shortcode' ) );

            // Extend Shortcode
            add_filter( 'widget_text', 'do_shortcode' );
            add_filter( 'widget_title', 'do_shortcode' );
            add_filter( 'wp_nav_menu_items', 'do_shortcode' );
            add_filter( 'the_title', array( $this, 'shortcodeTitles' ) );

            // Shortcode on Editor
            if ( is_admin() ) {
                add_action( 'admin_print_footer_scripts', array( $this, 'qtag' ) );
                add_filter( 'mce_external_plugins', array( $this, 'jquery' ) );
                add_filter( 'mce_buttons', array( $this, 'register' ) );
            }
        }


        /**
         * @about Easy Font Awesome Shortcode
         */
        final public function shortcode( $atts )
        {
            extract( shortcode_atts( array( 'icon' => '', 'class' => '' ), $atts ) );

            // Icon Variables
            $icon  = ( ! empty( $icon ) ) ? $icon : 'fa-home';
            $class = ( ! empty( $class ) ) ? ' ' . $class : '';

            return '<i class="fa ' . $icon . $class . '" aria-hidden="true"></i>';
        }


        /**
         * @about Shortcodes Within Page Titles
         */
        final public function shortcodeTitles( $title )
        {
            return do_shortcode( $title );
        }


        /**
         * @about Add Shortcode To Text Editor
         */
        final public function qtag()
        {
            if( wp_script_is( "quicktags" ) ) {?>
                <script type="text/javascript">
                    function getSel() {
                        var txtarea = document.getElementById("content");
                        var start = txtarea.selectionStart;
                        var finish = txtarea.selectionEnd;
                        return txtarea.value.substring(start, finish);
                    }

                    QTags.addButton( 
                        "myfa_shortcode",
                        "[myfa]",
                        callback
                    );

                    function callback() {
                        var selected_text = getSel();
                        QTags.insertContent('[myfa name="" size="" class=""]');
                    }
                </script>
            <?php }
        }


        /**
         * @about Enqueue For Tinymce
         */
        final public function jquery()
        {
            $plugin_array["myfa_button"] = plugin_dir_url( $this->plugin_file ) . 'assets/admin.js';
            return $plugin_array;
        }


        /**
         * @about Register Tinymce Button
         */
        function register($buttons)
        {
            array_push( $buttons, "myfa" );
            return $buttons;
        }


        /**
         * @about Action To Take For Enqueue
         */
        final public function action()
        {

            // WP Admin Area and/or Website Frontend Only
            if ( parent::option( 'admin' ) || parent::option( 'website' ) ) {
                // Website Frontend
                if ( parent::option( 'website' ) && ! is_admin() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                }

                // WP Admin Area
                if ( parent::option( 'admin' ) && is_admin() ) {
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
                }

            } else {
                // Login Status: User Is Logged In
                if ( parent::option( 'access' ) == 'logged_in' && is_user_logged_in() ) {
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                // Login Status: User Is Logged In - Frontend Only
                } elseif ( parent::option( 'access' ) == 'logged_in_front' && is_user_logged_in() && ! is_admin() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                // Login Status: User Is Logged In - Backend Only
                } elseif ( parent::option( 'access' ) == 'logged_in_back' && is_user_logged_in() && is_admin() ) {
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

                // Login Status:  User Is Logged Out
                } elseif ( parent::option( 'access' ) == 'logged_out' && ! is_user_logged_in() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                }

                // Post Types
                if ( parent::option( 'pt' ) && is_array( parent::option( 'pt' ) ) && ! is_admin() ) {
                    foreach ( (array) parent::option( 'pt' ) as $posttype ) {
                        // Posts
                        if ( $posttype == "post" && is_singular( 'post' ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Pages
                        } elseif ( $posttype == "page" && is_singular( 'page' ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Attachments
                        } elseif ( $posttype == "attachment" && is_attachment() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Archive
                        } elseif( isset( $posttype ) && is_single_type_archive( $posttype ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Other Singular
                        } elseif( isset( $posttype ) && is_singular( $posttype ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }

                // Load On Selected Template
                if ( parent::option( 'tpl' ) && is_array( parent::option( 'tpl' ) ) && ! is_admin() ) {
                    foreach ( (array) parent::option( 'pt' ) as $template ) {
                        // 404 Template
                        if ( $posttype == "404" && is_404() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Archive Template
                        } elseif ( $template == "archive" && is_archive() && ! is_singular() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Attachment Template(s)
                        } elseif ( $template == "attachment" && is_attachment()  ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Author Template(s)
                        } elseif ( $template == "author" && is_author() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Category Template
                        } elseif ( $template == "category" && is_category() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Front Page Template
                        } elseif ( $template == "front-page" && is_front_page() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Home Template
                        } elseif ( $template == "home" && is_front_page() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Index Template
                        } elseif ( $template == "index" && is_front_page() && is_home() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Page Template
                        } elseif ( $template == "page" && is_page() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Single Template
                        } elseif ( $template == "single" && is_single() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Search Template
                        } elseif ( $template == "search" && is_search() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Tag Template
                        } elseif ( $template == "tag" && is_tag() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                        // Taxonomy Template
                        } elseif ( $template == "taxonomy" && is_tax() ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }

                // Real Homepage
                if ( parent::option( 'homepage' ) == "home" && is_front_page() && is_home() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                // Static Homepage
                } elseif ( parent::option( 'homepage' ) == "static" && is_front_page() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

                // Blog Homepage
                } elseif ( parent::option( 'homepage' ) == "blog" && is_home() ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                }

                // Based on Post IDs
                if ( parent::option( 'post_ids' ) && is_single() ) {
                    $no_spaces = str_replace( ' ', '', parent::option( 'post_ids' ) );
                    $post_ids = explode( ',', $no_spaces );

                    foreach ( (array) $post_ids as $post_id ) {
                        if ( is_single( $post_id ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }

                // Based on Page IDs
                if ( parent::option( 'page_ids' ) && is_page() ) {
                    $no_spaces = str_replace( ' ', '', parent::option( 'page_ids' ) );
                    $page_ids = explode( ',', $no_spaces );

                    foreach ( (array) $page_ids as $page_id ) {
                        if ( is_page( $page_id ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }

                // Based on Post Type Ids
                if ( parent::option( 'pt_ids' ) && ! is_single() && ! is_page() ) {
                    $no_spaces = str_replace( ' ', '', parent::option( 'pt_ids' ) );
                    $pt_ids = explode( ',', $no_spaces );

                    foreach ( (array) $pt_ids as $pt_id ) {
                        if ( get_post_type( $pt_id ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }

                // Based on User Role
                if ( parent::option( 'role' ) ) {
                    foreach ( (array) parent::option( 'role' ) as $role ) {
                        if ( current_user_can( $role ) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        }
                    }
                }
            }
        }


        /**
         * @about Enqueue Font Awesome
         */
        final public function enqueue()
        {
            wp_enqueue_style( $this->plugin_name . '-fa', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', '', date( 'YmdHis', time() ), 'all' );
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
