<?php

/**
 * Plugin Name: Auto Featured Images SEO
 * Description: Automatically adds SEO-friendly featured images to posts without them using Pixabay or Pexels. Supports WP Cron automation.
 * Version: 1.1
 * Author: G Web Design
 */

if (!defined('ABSPATH')) exit;

class Auto_Featured_Images_SEO
{

    private $pixabay_api = '51846420-69983371bf7679ce694293d9d'; // Replace with your Pixabay API Key
    private $pexels_api  = 'qUpsJxHJKT0UvV4gIpv43DteHIhggQCorDkziju1YrqwdoeExQWmRVTT';  // Replace with your Pexels API Key
    private $cron_hook   = 'afi_seo_add_featured_images';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action($this->cron_hook, [$this, 'process_posts']);
        $this->schedule_cron();
    }

    /* Admin Page */
    public function add_admin_page()
    {
        add_menu_page(
            'Auto Featured Images',
            'Auto Featured Images',
            'manage_options',
            'auto-featured-images',
            [$this, 'admin_page_html'],
            'dashicons-format-image',
            80
        );
    }

    public function admin_page_html()
    {
        if (!current_user_can('manage_options')) return;

        echo '<div class="wrap"><h1>Auto Featured Images</h1>';

        if (isset($_POST['afi_run'])) {
            $count = $this->process_posts();
            echo "<p>Processed $count posts and added featured images where missing.</p>";
        }

        echo '<form method="post"><input type="hidden" name="afi_run" value="1"/><input type="submit" class="button button-primary" value="Run Auto Featured Images"/></form>';
        echo '<p>Posts without featured images will also be processed automatically daily via WP Cron.</p>';
        echo '</div>';
    }

    /* Schedule WP Cron */
    private function schedule_cron()
    {
        if (! wp_next_scheduled($this->cron_hook)) {
            wp_schedule_event(time(), 'daily', $this->cron_hook);
        }
    }

    /* Main processing function */
public function process_posts() {
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 5, // Limit to 5 posts at a time
        'post_status'    => ['draft', 'publish', 'future'], // Include scheduled posts
        'meta_query'     => [
            [
                'key'     => '_thumbnail_id',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ];

    $posts = get_posts($args);
    $count = 0;

    foreach ($posts as $post) {
        $keyword = $post->post_title;
        $image_url = $this->get_image_from_pixabay($keyword) ?: $this->get_image_from_pexels($keyword);

        if ($image_url) {
            $this->set_featured_image($post->ID, $image_url);
            $count++;
        }
    }

    return $count;
}

    /* Pixabay API */
    private function get_image_from_pixabay($keyword)
    {
        $query = urlencode($keyword);
        $url = "https://pixabay.com/api/?key={$this->pixabay_api}&q={$query}&image_type=photo&per_page=1";

        $response = wp_remote_get($url);
        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($data['hits'][0]['largeImageURL']) ? $data['hits'][0]['largeImageURL'] : false;
    }

    /* Pexels API */
    private function get_image_from_pexels($keyword)
    {
        $query = urlencode($keyword);
        $url = "https://api.pexels.com/v1/search?query={$query}&per_page=1";

        $response = wp_remote_get($url, [
            'headers' => ['Authorization' => $this->pexels_api]
        ]);

        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($data['photos'][0]['src']['large']) ? $data['photos'][0]['src']['large'] : false;
    }

    /* Set featured image with alt text */
   private function set_featured_image($post_id, $image_url) {
    if (! post_type_supports(get_post_type($post_id), 'thumbnail')) return;

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) return;

    $file_array = [
        'name'     => basename(parse_url($image_url, PHP_URL_PATH)),
        'tmp_name' => $tmp
    ];

    $id = media_handle_sideload($file_array, $post_id);

    if (!is_wp_error($id)) {
        set_post_thumbnail($post_id, $id);

        // Use post categories as alt text
        $categories = wp_get_post_terms($post_id, 'category', ['fields' => 'names']);
        $alt_text = !empty($categories) ? implode(', ', $categories) : get_the_title($post_id);

        update_post_meta($id, '_wp_attachment_image_alt', sanitize_text_field($alt_text));
    } else {
        @unlink($file_array['tmp_name']);
    }
}
}

new Auto_Featured_Images_SEO();
