<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Manager Class
 * 
 * @method __construct()    Set Parent Variables
 * @method option()         Get Saved Option Data For Inputs/Display
 * @method wpPosttypes()    Get All Post Type Keys
 * @method templates()      Create Checkbox Listing for Templates
 * @method wpTemplates()    Build Array of Found Templates
 * @method roles()          Create Checkbox Listing for Templates
 * @method wpRoles()        List of User Roles
 * @method qString()        Get Query String Item
 * @method validate()       Form Validation
 */
if( ! class_exists( 'MyFontAwesome_Extended' ) )
{
    class MyFontAwesome_Extended
    {
        // Website URL
        public $base_url;

        // The plugin-slug-name
        public $plugin_name;
        
        // Plugin Page Title
        public $plugin_title;
        
        // Plugin filename.php
        public $plugin_file;
        
        // Current Plugin Version
        public $plugin_version;
        
        // Plugin Menu Name
        public $menu_name;
        
        // Path To Plugin Templates
        public $templates;

        // Base Option Name
        public $option_name;


        /**
         * @about Set Class Vars
         */
        function __construct()
        {
            // Set Vars
            $this->base_url         = MY_FONT_AWESOME_BASE_URL;
            $this->plugin_name      = MY_FONT_AWESOME_PLUGIN_NAME;
            $this->plugin_title     = MY_FONT_AWESOME_PAGE_NAME;
            $this->plugin_file      = MY_FONT_AWESOME_PLUGIN_FILE;
            $this->plugin_version   = MY_FONT_AWESOME_VERSION;
            $this->menu_name        = MY_FONT_AWESOME_MENU_NAME;
            $this->templates        = MY_FONT_AWESOME_TEMPLATES;
            $this->option_name      = MY_FONT_AWESOME_OPTION_NAME;
        }


        /**
         * @about Get Saved Settings
         * @call echo parent::option( 'frontend' );
         * @param mixed $option Option Setting
         */
        final public function option( $option = '' )
        {
            $data = get_option( $this->option_name . 'settings' );
            return ( isset( $data[$option] ) ) ? $data[$option] : '';
        }


        /**
         * @about Create Checkbox Listing of Post Types
         * @location templates/home.php
         * @call echo parent::posttypes();
         * @return string $html Checkboxes of Post Type Names
         */
        final public function posttypes()
        {
            // Get Saved Post Types For Selected Items
            $data = $this->option( 'pt' );
            $selected_posttypes = ( ! empty( $data ) && is_array( $data ) ) ? $data : '';

            // Ignore Items
            $skip = array(
                '1' => 'revision',
                '2' => 'nav_menu_item',
                '3' => 'custom_css',
                '4' => 'customize_changeset',
                '5' => 'deprecated_log',
                '6' => 'include'
            );

            // Clear HTML
            $html = '';

            // Build List of Post Types, Will Skip All If No Saved Post Types
            foreach ( (array) $this->wpPosttypes() as $posttype ) {
                if ( in_array( $posttype, $skip ) ){ continue; }

                // Set Checked
                $checked = ( isset( $selected_posttypes[$posttype] ) && $posttype == $selected_posttypes[$posttype] ) ? ' checked="checked"' : '';

                // Build Post Type Name
                if ( $posttype == "post" ) {
                    $name = __( 'Post (core)', 'my-font-awesome' );

                } elseif ( $posttype == "page" ) {
                    $name = __( 'Page (core)', 'my-font-awesome' );

                } elseif ( $posttype == "attachment" ) {
                    $name = __( 'Attachment (core)', 'my-font-awesome' );

                } else {
                    $name = esc_html( ucfirst( $posttype ) );
                }

                // Build List
                $html .= '<p><label for="' . esc_attr( $posttype ) . '"><input name="pt[' . esc_attr( $posttype ) . ']" type="checkbox" id="' . esc_attr( $posttype ) . '" value="' . esc_attr( $posttype ) . '" ' . $checked . '/> ' . $name . '</label></p>';
            }

            return $html;
        }


        /**
         * @about Get All Post Type Keys
         * @return array Post Type Keys
         */
        final public function wpPosttypes()
        {
            global $wp_post_types;
            return array_keys( $wp_post_types );
        }


        /**
         * @about Create Checkbox Listing for Templates
         * @location templates/home.php
         * @call echo parent::templates();
         * @return string $html Checkboxes of Template Names
         */
        final public function templates()
        {
            // Get Saved Post Types For Selected Items
            $data = $this->option( 'tpl' );
            $selected_templates = ( ! empty( $data ) && is_array( $data ) ) ? $data : '';

            // Clear HTML
            $html = '';

            // Build List of Found Templates
            foreach ( (array) $this->wpTemplates() as $template ) {
                // Set Checked
                $checked = ( isset( $selected_templates[$template] ) && $template == $selected_templates[$template] ) ? ' checked="checked"' : '';

                // Build List
                $html .= '<p><label for="' . esc_attr( $template ) . '"><input name="tpl[' . esc_attr( $template ) . ']" type="checkbox" id="' . esc_attr( $template ) . '" value="' . esc_attr( $template ) . '" ' . $checked . '/> ' . esc_html( ucfirst( $template ) ) . ' ' . __( 'Template', 'my-font-awesome' ) . '</label></p>';
            }

            return $html;
        }


        /**
         * @about Build Array of Found Templates
         * @return array Merged Found Templates
         */
        final public function wpTemplates()
        {
            // Get Templates If They Exist
            $error = ( file_exists( get_stylesheet_directory() . '/404.php' ) ) ? array( '404' ) : array();
            $archive = ( file_exists( get_stylesheet_directory() . '/archive.php' ) ) ? array( 'archive' ) : array();
            $attachment = ( file_exists( get_stylesheet_directory() . '/attachment.php' ) ) ? array( 'attachment' ) : array();
            $author = ( file_exists( get_stylesheet_directory() . '/author.php' ) ) ? array( 'author' ) : array();
            $category = ( file_exists( get_stylesheet_directory() . '/category.php' ) ) ? array( 'category' ) : array();
            $frontpage = ( file_exists( get_stylesheet_directory() . '/front-page.php' ) ) ? array( 'front-page' ) : array();
            $home = ( file_exists( get_stylesheet_directory() . '/home.php' ) ) ? array( 'home' ) : array();
            $index = ( file_exists( get_stylesheet_directory() . '/index.php' ) ) ? array( 'index' ) : array();
            $page = ( file_exists( get_stylesheet_directory() . '/page.php' ) ) ? array( 'page' ) : array();
            $search = ( file_exists( get_stylesheet_directory() . '/search.php' ) ) ? array( 'search' ) : array();
            $single = ( file_exists( get_stylesheet_directory() . '/single.php' ) ) ? array( 'single' ) : array();
            $tag = ( file_exists( get_stylesheet_directory() . '/tag.php' ) ) ? array( 'tag' ) : array();
            $taxonomy = ( file_exists( get_stylesheet_directory() . '/taxonomy.php' ) ) ? array( 'taxonomy' ) : array();

            // Merge Templates
            return array_merge( $error, $archive, $attachment, $author, $category, $home, $index, $page, $search, $single, $tag, $taxonomy );
        }


        /**
         * @about Create Checkbox Listing for Templates
         * @location templates/home.php
         * @call echo parent::roles();
         * @return string $html Checkboxes of Template Names
         */
        final public function roles()
        {
            // Get Saved User Roles For Selected Items
            $data = $this->option( 'role' );
            $selected_roles = ( ! empty( $data ) && is_array( $data ) ) ? $data : '';

            // Clear HTML
            $html = '';

            // Build List of User Roles
            foreach ( (array) $this->wpRoles() as $role => $value ) {
                // Set Checked
                $checked = ( isset( $selected_roles[$role] ) && $role == $selected_roles[$role] ) ? ' checked="checked"' : '';

                // Build List
                $html .= '<p><label for="' . esc_attr( $role ) . '"><input name="role[' . esc_attr( $role ) . ']" type="checkbox" id="' . esc_attr( $role ) . '" value="' . esc_attr( $role ) . '" ' . $checked . '/> ' . esc_html( ucwords( str_replace( '_', ' ',  $role ) ) ) . '</label></p>';
            }

            return $html;
        }


        /**
         * @about List of User Roles
         */
        final public function wpRoles() {
            global $wp_roles;

            $wproles = $wp_roles->roles;
            return apply_filters( 'editable_roles', $wproles );
        }


        /**
         * @about Get Query String Item
         * @param string $get Query String Get Item
         * @return string Query String Item Sanitized
         */
        final public function qString( $get )
        {
            // Lowercase & Sanitize String
            $filter = strtolower( filter_input( INPUT_GET, $get, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );

            // Return No Spaces/Tabs, Stripped/Cleaned String
            return sanitize_text_field( preg_replace( '/\s/', '', $filter ) );
        }


        /**
         * @about Form Validation
         */
        final public function validate()
        {
            // Plugin Admin Area Only
            if ( filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW ) != $this->plugin_name ) {
                wp_die( __( 'You are not authorized to perform this action.', 'my-font-awesome' ) );
            }

            // Validate Nonce Action
            if( ! check_admin_referer( $this->option_name . 'action', $this->option_name . 'nonce' ) ) {
                wp_die( __( 'You are not authorized to perform this action.', 'my-font-awesome' ) );
            }
        }
    }
}
