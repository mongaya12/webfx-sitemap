<div class="fx-sitemap__taxonomies">
    <?php 
        if( $taxonomies = self::get_taxonomies() ) { 
            foreach( $taxonomies as $taxonomy ) { 
                $taxonomy_slug                  = str_replace('-','_', $taxonomy['taxonomy']);
                $taxonomy_label                 = $taxonomy['label']; 
                $field                          = get_option( 'fx_sitemap_tax_' . $taxonomy_slug ); 
                $terms                          = $taxonomy['term']; 
                $hidden_value_select_exclude    = get_option( 'fx_sitemap_select_exclude_settings_tax_' . $taxonomy_slug ); 
                $hidden_value_select_include    = get_option( 'fx_sitemap_select_include_settings_tax_' . $taxonomy_slug ); ?>

                <div class="fx-sitemap-taxonomy__item fx-sitemap-box__item fx-sitemap-taxonomy__<?php echo $taxonomy_slug;?>">
                    <div class="fx-sitemap__title"><?php echo $taxonomy_label; ?></div>
                    <div class="fx-sitemap-dropdown__item">
                        <label>Exclude General Display Sitemap? </label>
                        <input 
                            id="sitemap-tax-<?php echo $taxonomy_slug; ?>" 
                            class="fx-sitemap-cpt-input"
                            name="fx_sitemap_tax_<?php echo $taxonomy_slug; ?>"
                            type="checkbox" 
                            value="<?php echo ( $field ) ? '1' : ''; ?>"
                            <?php checked( $field, 1 ); ?>         
                        >
                    </div>

                    <div class="fx-sitemap-toggle__option">
                        <label class="js-accordion-toggle">Exclude Specific <?php echo $taxonomy_label; ?>? </label>
                        <span class="js-accordion-toggle dashicons dashicons-arrow-down-alt2"></span>
                        <?php if( $terms ) { ?>
                            <div class="accordion__wrapper">
                                
                                <span class="shortcode-label">Shortcode:</span>
                                <div class="shortcode-clipboard__wrapper">
                                    <input class="copy-shortcode" value='[fx_sitemap only="<?php echo $taxonomy['taxonomy']; ?>" exclude="yes"]' type="text" readonly="readonly" />
                                    <button class="fx-sitemap-copy__clipboard">
                                        <span class="dashicons dashicons-clipboard"></span>
                                    </button>
                                </div>

                                <select class="js-excluded-select" name="fx_sitemap_select_settings_tax_<?php echo $taxonomy_slug; ?>" multiple>
                                    <option value="">Select <?php echo $taxonomy_label; ?></option>
                                    <?php foreach( $terms as $term ) : ?>
                                            <option value="<?php echo $term->term_id; ?>" post-id=""><?php echo $term->name; ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="fx_sitemap_select_exclude_settings_tax_<?php echo $taxonomy_slug; ?>" 
                                    id="fx_sitemap_select_settings_tax_<?php echo $taxonomy_slug; ?>" 
                                    value='<?php echo ( $hidden_value_select_exclude ) ? $hidden_value_select_exclude : "" ?>' 
                                />
                                
                            </div>
                        <?php } ?>
                    </div>

                    <div class="fx-sitemap-toggle__option">
                        <label class="js-accordion-toggle">Include Specific <?php echo $taxonomy_label; ?>? </label>
                        <span class="js-accordion-toggle dashicons dashicons-arrow-down-alt2"></span>
                        <?php if( $terms ) { ?>
                            <div class="accordion__wrapper">

                                <span class="shortcode-label">Shortcode:</span>
                                <div class="shortcode-clipboard__wrapper">
                                    <input class="copy-shortcode" value='[fx_sitemap only="<?php echo $taxonomy['taxonomy']; ?>" include="yes"]' type="text" readonly="readonly" />
                                    <button class="fx-sitemap-copy__clipboard">
                                        <span class="dashicons dashicons-clipboard"></span>
                                    </button>
                                </div>

                                <select class="js-included-select" name="fx_sitemap_select_inc_settings_tax_<?php echo $taxonomy_slug; ?>" multiple>
                                    <option value="">Select <?php echo $taxonomy_label; ?></option>
                                    <?php foreach( $terms as $term ) : ?>
                                            <option value="<?php echo $term->term_id; ?>" post-id=""><?php echo $term->name; ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="fx_sitemap_select_include_settings_tax_<?php echo $taxonomy_slug; ?>" 
                                    id="fx_sitemap_select_inc_settings_tax_<?php echo $taxonomy_slug; ?>" 
                                    value='<?php echo ( $hidden_value_select_include ) ? $hidden_value_select_include : "" ?>' 
                                />
                                
                            </div>
                        <?php } ?>
                    </div>

                </div>
    <?php   }
        } else {
            echo "No Taxonomies where found!";
        }
    ?>
</div>