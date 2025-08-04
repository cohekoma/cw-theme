<?php
if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
    $template_id = 47;
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id );
} else {
    // fallback
    ?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </footer>
    <?php
}
?>

    <?php wp_footer(); ?>
</body>
</html>
