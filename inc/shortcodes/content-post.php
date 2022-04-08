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

    <?php if( isset( $arr_nav_items ) ) { ?>
        <ul class="fx-sitemap__wp-list fx-sitemap__wp-list-<?php echo $menu_slug; ?>">
            <?php 
                foreach( $arr_nav_items as $post ) {
                    if( ! in_array( $post['ID'], $decode_menu_ids ) ) {
                        echo sprintf(
                            '<li><a href="%s" id="%s" >%s</a></li>',
                            get_permalink( $post['ID'] ),
                            $post['ID'],
                            $post['title'],
                        );
                    }
                }
            ?>
        </ul>
    <?php } ?>
</div>