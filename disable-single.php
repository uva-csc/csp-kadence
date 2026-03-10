<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-hero" style="background-image: url('<?php the_post_thumbnail_url('full'); ?>');">
            <div class="post-hero__overlay">
                <h1><?php the_title(); ?></h1>
                <time><?php echo get_the_date('d M'); ?></time>
            </div>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>
