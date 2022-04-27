<div class="fx-sitemap__wrapper">
    <div class="fx-sitemap__head">
        <img src="<?php echo $path_url; ?>/assets/img/webfx-light.png" alt="WEB FX"> <span>- Sitemap</span>
    </div>
    <form id="fx-sitemap-form" method="post" action="options.php">
        <?php //settings_fields('fx_sitemap'); ?>
        <div class="fx-sitemap-content__wrapper">
            <div class="fx-sitemap__instruction">
                <div class="fx-sitemap-box__item">
                    <div class="fx-sitemap__title">
                        How to use
                    </div>
                    <div class="fx-instructions">
                        <div class="fx-sub__title">
                            Shortcodes
                        </div>
                        <div class="fx-manual__instruction">
                            The generated shortcodes can be found on Post Types, Taxonomies, Menus and General.
                        </div>

                        <div class="fx-sub__title">
                            Two types of functionality
                        </div>
                        <div class="fx-manual__instruction">
                            <p><strong>Include</strong> - This functionality will include a specific pages/post assign to it. When assign it will only query listed page/post. <br/> Example: <strong>[fx_sitemap only="page" include="yes"] </strong><i> - if no data assigned to each specific items added then no results will show. </i></p>

                            <p><strong>Exclude</strong> - This functionality will excluded a specfic page/post assign to it. It will query all the pages/post and excluded assigned listed page/post before rendering. <br/> Example: <strong>[fx_sitemap only="page" exclude="yes"] </strong><i> - if no data assigned to each specific post type/taxonomy then it will show all the items. </i></p>

                            <p><strong>[fx_sitemap]</strong> To display a traditionnal sitemap.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php 
                self::show_tabs(); 
                
                $current_tab = self::get_current_tab();

                switch ($current_tab) {
                    case 'post_type':
                        settings_fields('fx_sitemap');
                        require_once( self::$plugin_path_inc . '/views/admin/tabs/post-types.php' );
                        break;
                    
                    case 'taxonomy':
                        settings_fields('fx_sitemap_tax');
                        require_once( self::$plugin_path_inc . '/views/admin/tabs/taxonomies.php' );
                        break;

                    case 'menu':
                        settings_fields('fx_sitemap_menu');
                        require_once( self::$plugin_path_inc . '/views/admin/tabs/menus.php' );
                        break;

                    case 'general':
                        require_once( self::$plugin_path_inc . '/views/admin/tabs/general.php' );
                        break;
                }
            ?>
        </div>
        <div class="fx-sitemap-form__submit">
            <?php
                if( $current_tab !== 'general' && $current_tab !== 'instruction' ) 
                    submit_button(); 
            ?>
        </div>
    </form>
</div>
