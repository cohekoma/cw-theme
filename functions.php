<?php
function cw_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('elementor');
    add_theme_support('menus');

    register_nav_menus([
        'primary_menu' => __( 'Primary Menu', 'cw-theme' ),
        'footer_menu'  => __( 'Footer Menu', 'cw-theme' ),
    ]);
}
add_action('after_setup_theme', 'cw_setup');

function cw_enqueue_scripts() {
    wp_enqueue_style('cw-style', get_stylesheet_uri());
    wp_enqueue_script('cw-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'cw_enqueue_scripts');

add_action('init', function() {
    add_rewrite_rule('^product/([0-9]+)/?', 'index.php?product_id=$matches[1]', 'top');
});

add_filter('query_vars', function($vars) {
    $vars[] = 'product_id';
    return $vars;
});

add_filter('template_include', function($template) {
    if (get_query_var('product_id')) {
        $custom_template = locate_template('single-product.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
});


add_shortcode('cwea_single_product_images', function() {
    $product_id = get_query_var('product_id');
    if (!$product_id) return '<p>No product found.</p>';

    $response = wp_remote_get("https://dummyjson.com/products/{$product_id}");
    if (is_wp_error($response)) return '<p>Error loading product.</p>';

    $product = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($product['id'])) return '<p>Product not found.</p>';

    ob_start(); ?>
    <div class="cwea-product-images">
        <img id="cwea-main-image" src="<?php echo esc_url($product['thumbnail']); ?>" alt="<?php echo esc_attr($product['title']); ?>">
        <?php if (!empty($product['images'])): ?>
            <div class="cwea-gallery-thumbs">
                <?php foreach ($product['images'] as $img): ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('cwea_single_product_info', function() {
    $product_id = get_query_var('product_id');
    if (!$product_id) return '<p>No product found.</p>';

    $response = wp_remote_get("https://dummyjson.com/products/{$product_id}");
    if (is_wp_error($response)) return '<p>Error loading product.</p>';

    $product = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($product['id'])) return '<p>Product not found.</p>';

    ob_start(); ?>
    <div class="cwea-product-info">
        <h1><?php echo esc_html($product['title']); ?></h1>
        <p class="price">$<?php echo esc_html($product['price']); ?></p>
        <p><?php echo esc_html($product['description']); ?></p>
        <p><strong>Brand:</strong> <?php echo esc_html($product['brand']); ?></p>
        <p><strong>Category:</strong> <?php echo esc_html($product['category']); ?></p>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('cwea_related_products', function() {
    $product_id = get_query_var('product_id');
    if (!$product_id) return '<p>No product found.</p>';

    $response = wp_remote_get("https://dummyjson.com/products/{$product_id}");
    if (is_wp_error($response)) return '<p>Error loading product.</p>';

    $product = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($product['id'])) return '<p>Product not found.</p>';

    $cat = $product['category'];
    $related_response = wp_remote_get("https://dummyjson.com/products/category/{$cat}");
    if (is_wp_error($related_response)) return '<p>Error loading related products.</p>';

    $related_data = json_decode(wp_remote_retrieve_body($related_response), true);

    ob_start();
    if (!empty($related_data['products'])): ?>
        <div class="cwea-related-products">
            <?php foreach ($related_data['products'] as $rel): ?>
                <?php if ($rel['id'] != $product['id']): ?>
                    <div class="cwea-related-item">
                        <img src="<?php echo esc_url($rel['thumbnail']); ?>" alt="<?php echo esc_attr($rel['title']); ?>">
                        <h4><?php echo esc_html($rel['title']); ?></h4>
                        <p>$<?php echo esc_html($rel['price']); ?></p>
                        <a href="/product/<?php echo esc_attr($rel['id']); ?>">View Details</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No related products found.</p>
    <?php endif;

    return ob_get_clean();
});

