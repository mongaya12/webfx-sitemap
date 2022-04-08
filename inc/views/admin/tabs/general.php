<div class="fx-sitemap__general">
    <div class="fx-sitemap-box__item">
        <div class="fx-sitemap__title">
            General Sitemap
            <div class="shortcode-clipboard__wrapper">
                <input class="copy-shortcode" value='[fx_sitemap]' type="text" readonly="readonly" />
                <button class="fx-sitemap-copy__clipboard">
                    <span class="dashicons dashicons-clipboard"></span>
                </button>
            </div>
        </div>
        <div class="fx-sitemap-general__list">
            <div class="list-excluded">
                <span class="excluded-note">List of all Custom Post Types and Taxonomies.</span>
                <?php if( $merge_data = self::merge_cpt_tax() ): ?>
                <ul>
                    <?php 
                        foreach( $merge_data as $data ) {
                            if( post_type_exists( $data ) ) {
                                $exclude_cpt    = get_option('fx_sitemap_' . str_replace('-','_', $data ) );
                                $cpt_label      = get_post_type_object( $data )->label;
                                echo sprintf(
                                    '<li><span class="%s"></span>%s</li>',
                                    ( $exclude_cpt == '1' ) ? 'dashicons dashicons-no-alt' : 'dashicons dashicons-yes',
                                    $cpt_label
                                );
                            }

                            if( get_taxonomy( $data ) ) {
                                $exclude_tax    = get_option('fx_sitemap_tax_' . str_replace('-','_', $data ) );
                                $tax_label      = get_taxonomy( $data )->label;
                                echo sprintf(
                                    '<li><span class="%s"></span>%s</li>',
                                    ( $exclude_tax == '1' ) ? 'dashicons dashicons-no-alt' : 'dashicons dashicons-yes',
                                    $tax_label
                                );
                            }
                        }
                    ?>
                </ul>
                <?php endif; ?>
                <div class="note-wrapper">
                    <span>Note:</span>
                    <ul>
                        <li><span class="dashicons dashicons-no-alt"></span> - Excluded from Sitemap</li>
                        <li><span class="dashicons dashicons-yes"></span> - Included from Sitemap</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>