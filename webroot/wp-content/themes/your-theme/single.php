<?php get_header(); ?>

<main class="site-main container">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('entry-card'); ?>>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
