<?php

/**
 *   @integer $readTime
 * 
 */
function post_readtime($content){
    $words = str_word_count($content);
    $minutes = round($words / 200);
    return $minutes < 1 ? "1 minute" : $minutes. " minutes";

}

function restfox_posts()
{
    $args = [
        'numberposts' => 99999,
        'post_type' => 'post'
    ];

    $posts = get_posts($args);

    $data = [];
    $i = 0; // posts iterator
    $t = 0; // tags iterator

    foreach ($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['title'] = $post->post_title;
        $data[$i]['content'] = $post->post_content;
        $data[$i]['readtime'] = post_readtime($post->post_content);
        $data[$i]['slug'] = $post->post_name;
        $data[$i]['cover']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        $data[$i]['cover']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
        $data[$i]['cover']['large'] = get_the_post_thumbnail_url($post->ID, 'large');

        $tags = get_the_tags($post->ID);

        foreach ($tags as $tag) {
            $data[$i]['tags'][$t] = $tag->name;
            $t++;
        }

        $i++;
    }

    return $data;
}


add_action('rest_api_init', function () {
    register_rest_route('restfox/v2', 'posts', [
        'methods' => 'GET',
        'callback' => 'restfox_posts',
    ]);
});
