<div class="fx-sitemap-general__sitemap"> 
    <?php if( isset( $list_pages ) ) { ?> 
        <h2 class="fx-sitemap-cpt-title">Pages</h2>
        <ul class="fx-sitemap__wp-list fx-sitemap__wp-list-page">
            <?php echo $list_pages; ?>
        </ul> 
    <?php } ?>
    <?php 
        if( isset( $cpts ) ) { 
            foreach( $cpts as $cpt ) {
                echo sprintf('<h2 class="fx-sitemap-cpt-title">%s</h2>', $cpt->label );
                echo sprintf('<ul class="fx-sitemap__cpt fx-sitemap__%s">', $cpt->name);
                    $query = self::get_query_posts( $cpt->name, [] );
                    foreach( $query as $post ) {
                        echo sprintf('<li><a href="%s">%s</a></li>', get_permalink( $post->ID ), get_the_title( $post->ID ) );
                    }
                echo sprintf('</ul>');
            }
        }
        if( isset( $taxs ) ) { 
            foreach( $taxs as $tax ) {
                echo sprintf('<h2 class="fx-sitemap-cpt-title">%s</h2>', $tax['label'] );
                echo sprintf('<ul class="fx-sitemap__cpt fx-sitemap__%s">', $tax['taxonomy'] );
                    $terms = $tax['term'];
                    foreach( $terms as $term ) {
                        echo sprintf('<li><a href="%s">%s</a></li>', get_term_link( $term ), $term->name );
                    }
                echo sprintf('</ul>');
            }
        }
    ?>
</div>