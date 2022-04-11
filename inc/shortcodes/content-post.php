<div class="fx-sitemap__wrapper">
    <?php 
        if( isset( $post_type_label ) ) { 
            echo sprintf('<h2 class="fx-sitemap-cpt-title">%s</h2>', $post_type_label);
        } 
        if( isset( $term_label ) ) {
            echo sprintf('<h2 class="fx-sitemap-cpt-title">%s</h2>', $term_label);
        }
    ?> 
    <?php if( isset( $wp_list_pages ) ) { ?> 
        <ul class="fx-sitemap__wp-list fx-sitemap__wp-list-page">
            <?php echo $wp_list_pages; ?>
        </ul> 
    <?php } ?>

    <?php if( isset( $query_posts ) ) { ?> 
        <ul class="fx-sitemap__wp-list fx-sitemap__wp-list-<?php echo $post_type; ?>">
            <?php 
                foreach( $query_posts as $post ) {
                    echo sprintf(
                            '<li><a href="%s">%s</a></li>',
                            get_permalink( $post->ID ),
                            $post->post_title,
                        );
                }
            ?>
        </ul>
    <?php } ?>

    <?php if( isset( $obj_terms ) ) { ?> 
        <ul class="fx-sitemap__wp-list fx-sitemap__wp-list-<?php echo $taxonomy; ?>">
            <?php 
                foreach( $obj_terms as $term ) {
                    echo sprintf(
                            '<li><a href="%s">%s</a></li>',
                            get_term_link( $term ),
                            $term->name,
                        );
                }
            ?>
        </ul>
    <?php } ?>

    <?php if( isset( $menu_object ) ) { ?>
        <div class="fx-sitemap__wp-list fx-sitemap__wp-list-<?php echo $menu_slug; ?>">
            <?php 
            if( $nav_ids ) {
                $args = array( 'menu' => $menu_object->slug, 'walker' => new Fx_Nav_Walker( $nav_ids )  );
            } else {
                $args = array( 'menu' => $menu_object->slug );
            }
                wp_nav_menu( $args ); 
            ?>
        </div>
    <?php } ?>
</div>