<?php

function sltk_get_logo_ticker_shortcodes(): array {
    $shortcodes = [];

    $args = [
        'post_type'      => 'simple_logo_ticker',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $shortcodes[$post->ID] = $post->post_title;
        }
    }

    return $shortcodes;
}
?>