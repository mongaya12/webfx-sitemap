<?php 

/**
 * FX Sitemap Shortcodes
 * Contains logic for displaying the shortcodes
 * 
 */

defined( 'ABSPATH' ) || exit;

Class FX_Sitemap_Shortcode extends FX_Sitemap
{
    
    public static $shortcode_tag = 'fx_sitemap';

    /**
     * Initialize the hooks and actions for shortcode
     * 
     * @return void
     */
    public function initialize()
    {
        add_shortcode( self::$shortcode_tag, array( $this, 'shortcode_string' ) );
    }

    /**
     * Determine what condition, logical and methods to work on.
     * 
     * @param array $atts
     */
    public function shortcode_string( $atts ) 
    {
        $result = '';

        if( $atts ) {
            //check sitemaps available attributes
            if( isset( $atts['only'] ) ) {
                $attr = (string)$atts['only'];
                
                if( post_type_exists( $attr ) ) {
                    // determine attribute if excluded or included
                    if( isset( $atts['exclude'] ) ) {
                        $excluded = strtolower( $atts['exclude'] );
                        if( $excluded == 'yes' && count( $atts ) == 2  )
                            return self::exclude_query_posts( $attr ); 
                    }

                    if( isset( $atts['include'] ) && count( $atts ) == 2 ) {
                        $included = strtolower( $atts['include'] );
                        if( $included == 'yes' && count( $atts ) == 2  ) 
                            return self::include_query_posts( $attr ); 
                    }
                } 
                   
                if( get_taxonomy( $attr ) ) {
                    //determine taxonomy if excluded or included
                    if( isset( $atts['exclude'] ) ) {
                        $excluded = strtolower( $atts['exclude'] );
                        if( $excluded == 'yes' && count( $atts ) == 2  )
                            return self::exclude_query_term( $attr );
                    }

                    if( isset( $atts['include'] ) ) {
                        $included = strtolower( $atts['include'] );
                        if( $included == 'yes' && count( $atts ) == 2  )
                            return self::include_query_term( $attr );
                    }
                }
               
                // convert term object to array for easy array search
                $arr_menu = json_decode(json_encode(parent::get_menus()), true);
                if( array_search($attr, array_column($arr_menu, 'slug')) !== FALSE ) {
                    //determine menu if excluded a specific post/page
                    if( isset( $atts['exclude'] ) ) {
                        $excluded = strtolower( $atts['exclude'] );
                        if( $excluded == 'yes' && count( $atts ) == 2  )
                            return self::exclude_query_menu( $attr );
                    }
                }

            }

        } 
        
        if( ! $atts ) {
            // Traditional sitemap
            self::general_sitemap();
        }
    }

    /**
     * Traditional sitemap with excluded items
     * 
     * @return string
     */
    public function general_sitemap()
    {
        $post_types = parent::available_post_types(); 
        $taxonomies = parent::get_taxonomies();
        
        if( $post_types ) {
            foreach( $post_types as $post_type ) {
                if( $post_type->name === 'page' ) {
                    $exclude_general = get_option('fx_sitemap_' . self::convert_slug( $post_type->name ) ); 
                    if( ! $exclude_general ) {
                        $excluded_pages     = get_option('fx_sitemap_select_exclude_settings_' . self::convert_slug( $post_type->name ) );
                        $decode_json_page   = json_decode( $excluded_pages, true );
                        $atts               = [ 'exclude'   => '' ];

                        if( $decode_json_page ) {
                            $excluded_page      = implode( ',', $decode_json_page );    
                            $atts               = [ 'exclude'   => $excluded_page ];
                        }

                        $list_pages = self::is_post_type_page( $post_type->name, $atts );
                    }
                } else {
                    $exclude_general = get_option('fx_sitemap_' . self::convert_slug( $post_type->name ) );
                    if( ! $exclude_general ) { 
                        $cpts[] = $post_type;
                    }
                }
            }
        }

        if( $taxonomies ) {
            foreach( $taxonomies as $tax ) {
                $exclude_general = get_option('fx_sitemap_tax_' . self::convert_slug( $tax['taxonomy'] ) ); 
                if( ! $exclude_general ) {
                    $taxs[] = $tax;
                }
            }
        }

        include parent::$plugin_path_inc . '/shortcodes/general-sitemap.php';
    }

    /**
     * Query custom post based on post type and excluded specific posts
     * 
     * @param string $post_type
     * @return html
     */
    public function exclude_query_posts( string $post_type )
    {
        

        $excluded_posts_option  = get_option('fx_sitemap_select_exclude_settings_' . self::convert_slug( $post_type ) );
       
        if( ! $excluded_posts_option )
            return;

        $decode_json_posts      = json_decode( $excluded_posts_option, true );
        $excluded_posts         = implode( ',', $decode_json_posts );
        $post_type_label        = get_post_type_object( $post_type )->label;
        $atts                   = [ 'exclude'   => $excluded_posts ];
        $wp_list_pages          = self::is_post_type_page( $post_type, $atts );

        if( ! $wp_list_pages )
            $query_posts = self::get_query_posts( $post_type, $atts );

        ob_start();
        include parent::$plugin_path_inc . '/shortcodes/content-post.php';
        $result = ob_get_clean();
        
        return $result;
    }

    /**
     * Query custom post based on post type and included specific posts
     * 
     * @param string $post_type
     * @return html
     */
    public function include_query_posts( string $post_type )
    {
       
        $included_posts_option  = get_option('fx_sitemap_select_include_settings_' . self::convert_slug( $post_type ) );
        
        if( ! $included_posts_option ) 
            return '';

        $included_posts         = json_decode( $included_posts_option, true );
        $post_type_label        = get_post_type_object( $post_type )->label;
        $atts                   = [ 'include'   => $included_posts ];
        $wp_list_pages          = self::is_post_type_page( $post_type, $atts );

        if( ! $wp_list_pages )
            $query_posts = self::get_query_posts( $post_type, $atts );
        
        ob_start();
        include parent::$plugin_path_inc . '/shortcodes/content-post.php';
        $result = ob_get_clean();

        return $result;
    }

    /**
     * Check if post type is page and attributes
     * 
     * @param string $post_type
     * @param array $atts
     */
    private function is_post_type_page( string $post_type, array $atts )
    {

        $args['post_type']  = $post_type;
        $args['echo']       = false;
        $args['title_li']   = null;

        if( $atts ) {
            if( isset( $atts['exclude'] ) ) {
                $args['exclude'] = $atts['exclude'];    
            }
            if( isset( $atts['include'] ) ) {
                $args['include'] = $atts['include'];    
            }
        }

        return wp_list_pages( $args );
    }

    /**
     * Get posts from a custom post type
     * 
     * @param string $post_type
     * @param array $atts
     * @return object|array
     */
    private function get_query_posts( string $post_type, array $atts )
    {
        if( ! $post_type )
            return null;

        $args = [
            'numberposts'   => -1,
            'post_type'     => $post_type,
            'post_status'   => 'publish'
        ];

        if( $atts ) {
            if( isset( $atts['exclude'] ) ) {
                $args['exclude'] = $atts['exclude'];    
            }
            if( isset( $atts['include'] ) ) {
                $args['include'] = $atts['include'];    
            }
        }

        return get_posts( $args );
    }

    /**
     * Return excluded taxonomies
     * 
     * @param string $taxonomy
     * @return string
     */
    public function exclude_query_term( string $taxonomy )
    {
        $exclude_term       = get_option('fx_sitemap_select_exclude_settings_tax_' . self::convert_slug( $taxonomy ) );
        $decode_json_posts  = json_decode( $exclude_term, true );
        $obj_terms          = get_terms( $taxonomy, [ 'hide_empty' => false, 'exclude' => $decode_json_posts ] );
        $term_label         = get_taxonomy( $taxonomy )->label;

        if( ! $obj_terms )
            return;

        ob_start();
        include parent::$plugin_path_inc . '/shortcodes/content-post.php';
        $result = ob_get_clean();

        return $result;
    } 

    /**
     * Return included taxonomies
     * 
     * @param string $taxonomy
     * @return string
     */
    public function include_query_term( string $taxonomy )
    {
        $include_term       = get_option('fx_sitemap_select_include_settings_tax_' . self::convert_slug( $taxonomy ) );
        $decode_json_posts  = json_decode( $include_term, true );
        $obj_terms          = get_terms( $taxonomy, [ 'hide_empty' => false, 'include' => $decode_json_posts ] );
        $term_label         = get_taxonomy( $taxonomy )->label;

        if( ! $obj_terms )
            return;

        ob_start();
        include parent::$plugin_path_inc . '/shortcodes/content-post.php';
        $result = ob_get_clean();

        return $result;
    } 

    /**
     * Return menu and excluded page/post 
     * 
     * @param string $menu_slug
     * @return string
     */
    public function exclude_query_menu( string $menu_slug )
    {
        

        $exclude_menu_ids   = get_option('fx_sitemap_select_exclude_settings_menu_' . self::convert_slug( $menu_slug ) );
        $decode_menu_ids    = json_decode( $exclude_menu_ids, true );
        $menu_object        = wp_get_nav_menu_object( $menu_slug );
        $nav_items          = wp_get_nav_menu_items( $menu_object->term_id );
        $arr_nav_items      = json_decode(json_encode($nav_items), true); ##For safety convert object to array
        
        ob_start();
        include parent::$plugin_path_inc . '/shortcodes/content-post.php';
        $result = ob_get_clean();

        return $result;
    }

    /**
     * Convert post type slug to a post type with underscore not hypen
     * 
     * @param string $post_type
     * @return string
     */
    private function convert_slug( string $post_type )
    {
        return str_replace( '-', '_', $post_type );
    }
}

FX_Sitemap_Shortcode()->initialize();