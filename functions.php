<?php

/**
 * Creating shortcode for post list in pages
 *
 * @param $atts
 * @return false|string
 *
 * Place the short code anywhere on any page with:
 * ```
 * [post_list]
 * ```
 *
 * Or with optional parameters:
 * 1. count - number of posts to display
 * 2. category - category name to filter by
 * ```
 * [post_list count="5"]
 * [post_list count="20" category="news"]
 */

add_shortcode( 'post_list', 'create_post_list');


function create_post_list( $atts ) {
    $atts = shortcode_atts([
        'count' => 10,
        'category' => '',
    ], $atts );

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => intval( $atts['count'] ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $atts['category'] ) {
        $args['category_name'] = $atts['category'];
    }

    $query = new WP_Query( $args );
    ob_start();

    if ( $query->have_posts() ) :
        echo '<div class="custom-post-list">';
        while ( $query->have_posts() ) : $query->the_post(); ?>
            <article class="post-card">
                <div class="post-card__content">
                    <h2><span class="post-date"><?php echo get_the_date( 'd M Y' ); ?></span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile;
        echo '</div>';
        wp_reset_postdata();
    endif;

    return ob_get_clean();
}

/* kadence_before_content kadence_hero_header */
add_action( 'kadence_hero_header', function() {
    if ( ! is_single() ) return;

    $bgurl = '/wp-content/uploads/2018/09/child-new-csp-website-image-F2A8310.jpg';
    ?>
    <div class="post-hero" style="background-image: url(<?php echo esc_url( $bgurl ); ?>);">
        <div class="post-hero__overlay">
            <div class="overlay-text"><a href="/news">News</a></div>
        </div>
    </div>
    <?php
});
/* kadence_loop_entry_content */
add_action( 'kadence_loop_entry', function() {
    echo '<div class="post-thumbnail-inner">' .
				'<img width="768" height="462" src="https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-768x462.jpg" class="attachment-medium_large size-medium_large wp-post-image" alt="Alexis Harris makes a presentation on Flourishing for Educators to a classroom of educators" decoding="async" loading="lazy" srcset="https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-768x462.jpg 768w, https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-300x180.jpg 300w, https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-1024x615.jpg 1024w, https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-1536x923.jpg 1536w, https://cspdev.ddev.site/wp-content/uploads/2023/02/Flourishing-for-Educators-crop-Alexis_115410-2048x1231.jpg 2048w" sizes="auto, (max-width: 768px) 100vw, 768px">			</div>';
});