<?php

if (! defined('ABSPATH')) {
    exit;
}

function your_theme_config()
{
    static $config = null;

    if (null !== $config) {
        return $config;
    }

    $config_path = get_theme_file_path('../../../../starter.config.json');

    if (! file_exists($config_path)) {
        $config = array();
        return $config;
    }

    $decoded = json_decode(file_get_contents($config_path), true);
    $config = is_array($decoded) ? $decoded : array();

    return $config;
}

function your_theme_vite_server_url()
{
    $config = your_theme_config();
    $host = $config['vite']['publicHost'] ?? '127.0.0.1';
    $port = $config['vite']['port'] ?? 5173;

    return sprintf('http://%s:%s', $host, $port);
}

function your_theme_vite_server_running()
{
    $config = your_theme_config();
    $host = $config['vite']['containerHost'] ?? 'host.docker.internal';
    $port = (int) ($config['vite']['port'] ?? 5173);
    $socket = @fsockopen($host, $port, $errno, $errstr, 0.2);

    if (! $socket) {
        return false;
    }

    fclose($socket);
    return true;
}

function your_theme_vite_manifest()
{
    static $manifest = null;

    if (null !== $manifest) {
        return $manifest;
    }

    $manifest_path = get_theme_file_path('assets/.vite/manifest.json');

    if (! file_exists($manifest_path)) {
        $manifest = array();
        return $manifest;
    }

    $decoded = json_decode(file_get_contents($manifest_path), true);
    $manifest = is_array($decoded) ? $decoded : array();

    return $manifest;
}

function your_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'your-theme'),
        )
    );
}
add_action('after_setup_theme', 'your_theme_setup');

function your_theme_print_vite_client()
{
    if (is_admin() || ! your_theme_vite_server_running()) {
        return;
    }

    $vite_url = esc_url(your_theme_vite_server_url());
    $entry_url = esc_url($vite_url . '/source/theme/main.js');

    echo '<script type="module" src="' . $vite_url . '/@vite/client"></script>' . "\n";
    echo '<script type="module" src="' . $entry_url . '"></script>' . "\n";
}
add_action('wp_head', 'your_theme_print_vite_client', 1);

function your_theme_mark_as_module($tag, $handle, $src)
{
    if ('your-theme-app' !== $handle) {
        return $tag;
    }

    return '<script type="module" src="' . esc_url($src) . '"></script>';
}
add_filter('script_loader_tag', 'your_theme_mark_as_module', 10, 3);

function your_theme_assets()
{
    $theme = wp_get_theme();
    $version = $theme->get('Version') ?: null;
    $entry = 'source/theme/main.js';

    if (your_theme_vite_server_running()) {
        return;
    }

    $manifest = your_theme_vite_manifest();

    if (! isset($manifest[$entry]['file'])) {
        return;
    }

    foreach ($manifest[$entry]['css'] ?? array() as $index => $css_file) {
        wp_enqueue_style(
            'your-theme-main-' . $index,
            get_template_directory_uri() . '/assets/' . ltrim($css_file, '/'),
            array(),
            $version
        );
    }

    wp_enqueue_script(
        'your-theme-app',
        get_template_directory_uri() . '/assets/' . ltrim($manifest[$entry]['file'], '/'),
        array(),
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'your_theme_assets');
