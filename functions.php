<?php
/** adding font awesome **/
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' );
} );

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

    $paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => intval( $atts['count'] ),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
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
                    <h2><span class="post-date"><?php echo get_the_date( 'd M Y' ); ?></span>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_content(); ?>
                </div>
            </article>
        <?php
        endwhile;
        echo '</div>';

        $pagination = paginate_links([
                'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'prev_text' => '&lsaquo;',
                'next_text' => '&rsaquo;',
                'type'      => 'list',
        ]);

        if ( $pagination ) {
            echo '<nav class="post-list-pagination" aria-label="Posts navigation">';
            echo $pagination;
            echo '</nav>';
        }
    endif;

    wp_reset_postdata();
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

/** Default post thumbnail  **/
add_action( 'kadence_loop_entry_thumbnail', 'csp_carousel_fallback_thumbnail' );
function csp_carousel_fallback_thumbnail() {
    if ( ! has_post_thumbnail() ) {
        $default_img = get_stylesheet_directory_uri() . '/assets/images/csp-flourish-lndscp.png';
        echo '<a class="post-thumbnail kadence-thumbnail-ratio-2-3 default-img" aria-label="' . esc_attr( get_the_title() ) . '" href="' . get_permalink() . '">';
        echo '<div class="post-thumbnail-inner"><img src="' . esc_url( $default_img ) . '"  alt="" /></div>';
        echo '</a>';
    }
}

/** Getting target=_blank to persist on links **/
add_filter( 'wp_kses_allowed_html', function( $allowed, $context ) {
    if ( isset( $allowed['a'] ) ) {
        $allowed['a']['target'] = true;
        $allowed['a']['rel']    = true;
    }
    if ( $context === 'post' ) {
        $allowed['i'] = array(
                'class'       => true,
                'aria-hidden' => true,
        );
        $allowed['span']['class'] = true;
    }
    return $allowed;
}, 10, 2 );
