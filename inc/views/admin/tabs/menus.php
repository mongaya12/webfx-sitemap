<div class="fx-sitemap__menus">
    <?php if( $available_menus = self::get_menus() ) { 
            foreach( $available_menus as $menu ) { 
                $menu_slug                      = str_replace( '-','_', $menu->slug );
                $menu_label                     = $menu->name; 
                $hidden_value_select_exclude    = get_option( 'fx_sitemap_select_exclude_settings_menu_' . $menu_slug ); ?>

                <div class="fx-sitemap-menu__item fx-sitemap-box__item fx-sitemap-menu__<?php echo $menu_slug;?>">
                    <div class="fx-sitemap__title"><?php echo $menu_label; ?></div>

                    <div class="fx-sitemap-toggle__option">
                        <label class="js-accordion-toggle">Exclude Specific Menu Item? </label>
                        <span class="js-accordion-toggle dashicons dashicons-arrow-down-alt2"></span>
                        <div class="accordion__wrapper">
                            
                            <span class="shortcode-label">Shortcode:</span>
                            <div class="shortcode-clipboard__wrapper">
                                <input class="copy-shortcode" value='[fx_sitemap only="<?php echo $menu->slug; ?>" exclude="yes"]' type="text" readonly="readonly" />
                                <button class="fx-sitemap-copy__clipboard">
                                    <span class="dashicons dashicons-clipboard"></span>
                                </button>
                            </div>

                            <select class="js-excluded-select" name="fx_sitemap_select_settings_menu_<?php echo $menu_slug; ?>" multiple>
                                <option value="">Select <?php echo $menu_label; ?></option>
                                <?php 
                                    $nav_items = wp_get_nav_menu_items( $menu->term_id );
                                    foreach( $nav_items as $item ) {
                                        echo sprintf('<option value="%s">%s</option>', $item->ID, $item->title );
                                    }
                                ?>
                            </select>

                            <input 
                                type="hidden" 
                                name="fx_sitemap_select_exclude_settings_menu_<?php echo $menu_slug; ?>" 
                                id="fx_sitemap_select_settings_menu_<?php echo $menu_slug; ?>" 
                                value='<?php echo ( $hidden_value_select_exclude ) ? $hidden_value_select_exclude : "" ?>' 
                            />
                            
                        </div>
                    </div>
                </div>
    <?php 
            } 
        }   
    ?>
</div>