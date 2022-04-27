<?php 

/**
 * Plugin Name: FX Sitemap
 * 
 * Version:     0.0.1
 * Description: Add a sitemap on any pages/posts using a simple shortcode
 * 
 * Author:      WEBFX
 * Author URI:  https://webfx.com
 * Plugin URI:  https://webfx.com
 */

defined( 'ABSPATH' ) || exit;

Class FX_Sitemap
{

    public static $plugin_path      = null;
    public static $plugin_path_inc  = null;
    public static $plugin_url       = null;

    public static $theme_base_path  = null;
    public static $theme_base_url   = null;


    /**
     * Initialize the assets and enable the FX Sitemap Settings
     * 
     * @return void
     */
    public function initialize() 
    {
        self::define();

        add_action( 'admin_enqueue_scripts', array( $this, 'scripts_styles' ) );
        add_action( 'admin_menu', array( $this, 'admin_options_page' ) );
        add_action( 'admin_init', array( $this, 'save_settings' ) );
        add_filter( 'plugin_action_links', array( $this, 'plugin_settings' ), 10, 2);
    }

    /**
     * Return list of all available Posts Type
     * 
     * @return array|null
     */
    public function available_post_types()
    {
        $raw_post_type = array(
            'page',
            'post',
        );

        $args = array (
            'public'    => true,
            '_builtin'  => false
        );

        $post_types = get_post_types( $args, 'names' );

        if( empty( $post_types ) )
            return null;

        $merge_post_types   = array_merge( $raw_post_type, $post_types );
        $obj_post_types     = [];

        foreach( $merge_post_types as $post_type ) {
            $cpt                = get_post_type_object( $post_type );
            $obj_post_types[]   = $cpt;
        }
        return $obj_post_types;
    }

    /**
     * Get available menus
     * 
     * @return array|object
     */
    public function get_menus() 
    {
        $registered_menus = get_nav_menu_locations();
        $list_menu = [];

        if( $registered_menus ) {
            foreach( $registered_menus as $menu ) {
                $menu_object = wp_get_nav_menu_object( $menu );
                $list_menu[] = $menu_object;
            }
        }
        return $list_menu;
    }

    /**
     * Manage the options when submit the form
     * 
     * @return void
     */
    public function save_settings() {
        if( $obj_cpts = self::available_post_types() ) {
            foreach( $obj_cpts as $post_type ) {
                $field_name = str_replace('-','_', $post_type->name); 
                
                register_setting( 
                    'fx_sitemap', 
                    'fx_sitemap_' . esc_html( $field_name ), 
                    'sanitize_text_field' 
                );
                
                register_setting(
                    'fx_sitemap',
                    'fx_sitemap_select_exclude_settings_' . esc_html( $field_name ),
                    'sanitize_text_field'
                );

                register_setting(
                    'fx_sitemap',
                    'fx_sitemap_select_include_settings_' . esc_html( $field_name ),
                    'sanitize_text_field'
                );
            }
        }

        if( $taxonomies = self::get_taxonomies() ) {
            foreach( $taxonomies as $tax ) {
                $field_name = str_replace('-','_', $tax['taxonomy'] );

                register_setting( 
                    'fx_sitemap_tax', 
                    'fx_sitemap_tax_' . esc_html( $field_name ), 
                    'sanitize_text_field' 
                );

                register_setting( 
                    'fx_sitemap_tax', 
                    'fx_sitemap_select_exclude_settings_tax_' . esc_html( $field_name ), 
                    'sanitize_text_field' 
                );

                register_setting( 
                    'fx_sitemap_tax', 
                    'fx_sitemap_select_include_settings_tax_' . esc_html( $field_name ), 
                    'sanitize_text_field' 
                );

            }
        }

        if( $menus = self::get_menus() ) {
            foreach( $menus as $menu ) {
                $field_name = str_replace('-','_', $menu->slug );

                register_setting( 
                    'fx_sitemap_menu', 
                    'fx_sitemap_select_exclude_settings_menu_' . esc_html( $field_name ), 
                    'sanitize_text_field' 
                );

            }
        }

    }

    /**
     * Enqueue Scripts and Styles on dashboard
     * 
     * @return void
     */
    public function scripts_styles() 
    {
        global $pagenow;

        if( ( $pagenow == 'options-general.php' ) && $_GET['page'] == 'fx_sitemap' ) {
            wp_enqueue_style( 'fx-plugin-choices' , self::$plugin_url . 'assets/css/plugins/choices.css',  array(), false );
            wp_enqueue_style( 'fx-component-choices' , self::$plugin_url . 'assets/css/components/choices.css',  array(), false );
            wp_enqueue_style( 'fx-sitemap-dashboard' , self::$plugin_url . 'assets/css/fx-admin-stylesheet.css',  array(), false );

            wp_enqueue_script( 'fx-plugin-choices-script' , self::$plugin_url . 'assets/js/plugins/choices.js',  array(), false, true );
            wp_enqueue_script( 'fx-sitemap-scripts' , self::$plugin_url . 'assets/js/fx-admin-scripts.js',  array(), false, true );
        }
    }

    /**
     * Create Admin page and Settings
     * 
     * @return void
     */
    public function admin_options_page() 
    {
        add_submenu_page(
            'options-general.php',
            'FX Sitemap', 
            'FX Sitemap', 
            'administrator',
            'fx_sitemap', 
            array( $this, 'fx_sitemap_dashboard' )
        );
    }

    /**
     * Query the posts from custom post type
     * 
     * @return object|null
     */
    public function query_posts( $post_type = '' ) 
    {
        if( ! $post_type ) 
            return null;
        
        $args = array(
            'post_type'         => $post_type,
            'post_status'       => 'publish',
            'posts_per_page'    => -1
        );

        $query_object = new WP_Query($args);

        if( ! $query_object )
            return null;
        
        return $query_object;
    }

    /**
     * Create view for FX Sitemap Dashbaord page
     * 
     * @return void
     */
    public function fx_sitemap_dashboard() 
    {
        $list_post_types    = self::available_post_types(); 
        $path_url           = self::$plugin_url;
        
        require_once( self::$plugin_path_inc . '/views/admin/dashboard.php' );
    }

    /**
     * Get current tab
     * 
     * @return string
     */
    public function get_current_tab()
    {
        if( isset( $_GET['tab'] ) ) {
            return esc_html( $_GET['tab'] );
        } else {
            return 'post_type';
        }
    }

    /**
     * Display the tabs
     * 
     * @return
     */
    public function show_tabs()
    {

        $current_tab = self::get_current_tab();

        $tabs = array(
            'post_type'     => 'Post Types',
            'taxonomy'      => 'Taxonomies',
            'menu'          => 'Menus',
            'general'       => 'General',
        );

        echo sprintf('<h2 class="nav-tab-wrapper">');

        foreach( $tabs as $tab => $tab_name ) {
            if( $tab === $current_tab ) {
                $active_tab = 'nav-tab-active';
            } else {
                $active_tab = '';
            }

            echo sprintf('<a class="nav-tab %s" href="?page=fx_sitemap&amp;tab=%s">%s</a>',
                $active_tab,
                $tab,
                $tab_name    
            );
        }

        echo sprintf('</h2>');

        return;
    }

    /**
     * Return list of all other taxonomies
     * 
     * @return array|null
     */
    public function get_taxonomies()
    {
        // Include raw post category
        $taxonomy[0]['label']       = 'Post Category';
        $taxonomy[0]['taxonomy']    = 'category';
        $taxonomy[0]['term']        = get_categories();
        
        $args = array (
            'public'    => true,
            '_builtin'  => false
        );
        
        $post_types = get_post_types( $args, 'names' );
        
        if( ! $post_types )
            return;
            
        $ctr = 1;
        foreach( $post_types as $post_type ) {
            $taxonomies = get_object_taxonomies( $post_type ); 
            if( ! empty( $taxonomies ) ) {
               
                foreach( $taxonomies as $tax ) {
                    $terms                          = get_terms( $tax, [ 'hide_empty' => false ] );
                    $term_label                     = get_taxonomy( $tax )->label;
                    $taxonomy[$ctr]['label']        = $term_label;
                    $taxonomy[$ctr]['taxonomy']     = $tax;
                    $taxonomy[$ctr]['term']         = $terms;
                    $ctr++;
                }
            }
        }

        return $taxonomy;
    }

    /**
     * Merge custom post types and taxonomies and simplify
     * 
     * @return array|object
     */
    public function merge_cpt_tax()
    {

        $get_cpt = wp_list_pluck( self::available_post_types(), 'name' );
        $get_tax = wp_list_pluck( self::get_taxonomies(), 'taxonomy' );
       
        return array_merge( $get_cpt , $get_tax );
    }

    /**
     * Define common variables
     * 
     * @return void
     */
    public function define() : void
    {
        self::$plugin_path 		= plugin_dir_path( __FILE__ );
        self::$plugin_url 		= plugin_dir_url( __FILE__ );
        self::$plugin_path_inc 	= sprintf( '%sinc/', self::$plugin_path );

        self::$theme_base_path  = get_stylesheet_directory();
        self::$theme_base_url   = get_stylesheet_directory_uri();

        require_once( self::$plugin_path . '/helper-functions.php' );
        require_once( self::$plugin_path . '/fx-nav-walker.php' );
        require_once( self::$plugin_path . '/fx-sitemap-shortcode.php' );
    }

    /**
     * Provide shortcut link to settings
     * 
     * @return array
     */
    public function plugin_settings( $links, $file ) {

        $fx_sitemap = 'fx-sitemap/fx-sitemap.php';

        if ($file == $fx_sitemap) {
            $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=fx_sitemap">Settings</a>';
            array_unshift($links, $settings_link);
        }
        
        return $links;
    }
}

function FX_Sitemap() {
    return new \FX_Sitemap();
}

FX_Sitemap()->initialize();