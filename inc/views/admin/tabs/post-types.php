<div class="fx-sitemap__cpts">
    <?php 
        if( $list_post_types ) { 
            foreach( $list_post_types as $post_type ) { 
                $post_type_name                 = $post_type->name; 
                $slug_post_type                 = str_replace('-','_', $post_type_name);
                $post_type_label                = $post_type->label;
                $field                          = get_option( 'fx_sitemap_' . $slug_post_type ); 
                $hidden_value_select_exclude    = get_option( 'fx_sitemap_select_exclude_settings_' . $slug_post_type );
                $hidden_value_select_include    = get_option( 'fx_sitemap_select_include_settings_' . $slug_post_type );   
                $obj_query_post                 = new FX_Sitemap();
                $obj_query_post                 = $obj_query_post->query_posts($post_type_name);    ?> 

                <div class="fx-sitemap__<?php echo $post_type_name; ?> fx-sitemap-item__cpt">
                    <div class="fx-sitemap__title"><?php echo $post_type_label; ?></div>
                    <div class="fx-sitemap-excluded__cpt">
                        <label>Exclude General Display Sitemap? </label>
                        <input 
                            id="sitemap-<?php echo $post_type_name; ?>" 
                            class="fx-sitemap-cpt-input"
                            name="fx_sitemap_<?php echo $slug_post_type; ?>"
                            type="checkbox" 
                            value="<?php echo ( $field ) ? '1' : ''; ?>"
                            <?php checked( $field, 1 ); ?>         
                        >
                    </div>
                    <div class="fx-sitemap-toggle__option">
                        <label class="js-accordion-toggle">Exclude Specific <?php echo $post_type_label; ?>? </label>
                        <span class="js-accordion-toggle dashicons dashicons-arrow-down-alt2"></span>
                        <?php if( $obj_query_post->have_posts() ) { ?>
                            <div class="accordion__wrapper">
                                
                                <span class="shortcode-label">Shortcode:</span>
                                <div class="shortcode-clipboard__wrapper">
                                    <input class="copy-shortcode" value='[fx_sitemap only="<?php echo $post_type_name; ?>" exclude="yes"]' type="text" readonly="readonly" />
                                    <button class="fx-sitemap-copy__clipboard">
                                        <span class="dashicons dashicons-clipboard"></span>
                                    </button>
                                </div>

                                <select class="js-excluded-select" name="fx_sitemap_select_settings_<?php echo $slug_post_type; ?>" multiple>
                                    <option value="">Select <?php echo $post_type_label; ?></option>
                                    <?php while( $obj_query_post->have_posts() ) { $obj_query_post->the_post(); ?>
                                            <option value="<?php echo get_the_ID(); ?>" post-id=""><?php the_title(); ?></option>
                                    <?php } ?>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="fx_sitemap_select_exclude_settings_<?php echo $slug_post_type; ?>" 
                                    id="fx_sitemap_select_settings_<?php echo $slug_post_type; ?>" 
                                    value='<?php echo ( $hidden_value_select_exclude ) ? $hidden_value_select_exclude : "" ?>' 
                                />
                                
                            </div>
                        <?php } ?>
                    </div>

                    <div class="fx-sitemap-toggle__option">
                        <label class="js-accordion-toggle">Include Specific <?php echo $post_type_label; ?>? </label>
                        <span class="js-accordion-toggle dashicons dashicons-arrow-down-alt2"></span>
                        <?php if( $obj_query_post->have_posts() ) { ?>
                            <div class="accordion__wrapper">

                                <span class="shortcode-label">Shortcode:</span>
                                <div class="shortcode-clipboard__wrapper">
                                    <input class="copy-shortcode" value='[fx_sitemap only="<?php echo $post_type_name; ?>" include="yes"]' type="text" readonly="readonly" />
                                    <button class="fx-sitemap-copy__clipboard">
                                        <span class="dashicons dashicons-clipboard"></span>
                                    </button>
                                </div>

                                <select class="js-included-select" name="fx_sitemap_select_inc_settings_<?php echo $slug_post_type; ?>" multiple>
                                    <option value="">Select <?php echo $post_type_label; ?></option>
                                    <?php while( $obj_query_post->have_posts() ) { $obj_query_post->the_post(); ?>
                                            <option value="<?php echo get_the_ID(); ?>" post-id=""><?php the_title(); ?></option>
                                    <?php } ?>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="fx_sitemap_select_include_settings_<?php echo $slug_post_type; ?>" 
                                    id="fx_sitemap_select_inc_settings_<?php echo $slug_post_type; ?>" 
                                    value='<?php echo ( $hidden_value_select_include ) ? $hidden_value_select_include : "" ?>' 
                                />
                                
                            </div>
                        <?php } ?>
                    </div>
                </div>
    <?php   }
        } 
    ?>
</div>