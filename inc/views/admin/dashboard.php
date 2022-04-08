<div class="fx-sitemap__wrapper">
    <div class="fx-sitemap__head">
        <img src="<?php echo $path_url; ?>/assets/img/webfx-light.png" alt="WEB FX"> <span>- Sitemap</span>
    </div>
    <form method="post" action="options.php">
        <?php //settings_fields('fx_sitemap'); ?>
        <div class="fx-sitemap-content__wrapper">
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

                    case 'instruction':
                        require_once( self::$plugin_path_inc . '/views/admin/tabs/instructions.php' );
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
