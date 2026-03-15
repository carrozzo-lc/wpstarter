<?php get_header(); ?>

<main class="site-main container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('entry-card'); ?>>
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <article class="entry-card">
            <h2 class="entry-title"><?php esc_html_e('No content found', 'your-theme'); ?></h2>
            <div class="entry-content">
                <p><?php esc_html_e('Create a page or publish a post to start working on the theme.', 'your-theme'); ?></p>
            </div>
        </article>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
