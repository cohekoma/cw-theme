<?php
/**
 * Template for Single Product
 */

get_header();

// $product_id = get_query_var('product_id');
// if (!$product_id) {
//     echo '<p>Invalid Product ID</p>';
//     get_footer();
//     exit;
// }

// // fetch detail from API
// $response = wp_remote_get("https://dummyjson.com/products/{$product_id}");
// if (is_wp_error($response)) {
//     echo '<p>Failed to load product data.</p>';
//     get_footer();
//     exit;
// }

// $product = json_decode(wp_remote_retrieve_body($response), true);

// if (empty($product['id'])) {
//     echo '<p>Product not found.</p>';
//     get_footer();
//     exit;
// }

$template_id = 44;
if ( class_exists('\Elementor\Plugin') ) {
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id );
} else {
    echo '<p>Please activate Elementor.</p>';
}
?>



<?php get_footer(); ?>
