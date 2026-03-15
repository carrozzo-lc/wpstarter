<?php get_header(); ?>

<main class="site-main container">
    <section class="hero">
        <p class="hero__eyebrow"><?php esc_html_e('WordPress starter', 'your-theme'); ?></p>
        <h1 class="hero__title"><?php bloginfo('name'); ?></h1>
        <p class="hero__text"><?php bloginfo('description'); ?></p>
    </section>

    <section class="entry-card">
        <h2 class="entry-title"><?php esc_html_e('Environment ready', 'your-theme'); ?></h2>
        <div class="entry-content">
            <p><?php esc_html_e('You can now edit templates in the theme and assets in source/theme.', 'your-theme'); ?></p>
        </div>
    </section>
</main>

<?php get_footer(); ?>
