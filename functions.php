<?php 

function Personal_setup() {
    // Enable title in header
    add_theme_support( "title-tag" );
    // Enable featured image
    add_theme_support( "post-thumbnails" );
    // Custom menu areas
    register_nav_menus( array(
        'header' => esc_html__('Header', 'slug-theme')
    ));

    // image size
    if ( function_exists( 'add_image_size' ) ) {
        add_image_size( 'xsmall', 100, 100 , true);
        add_image_size( 'small', 150, 150 , true);
        add_image_size( 'medium', 600, 300 , true);
        add_image_size( 'large', 1200, 600 , true);
    }
}
add_action( 'after_setup_theme','Personal_setup');

// Il mio stile e i miei scripts

function add_personal_scripts() {    
    wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/bootstrap-grid.min.css');
    wp_enqueue_style( 'hamburgers-style', get_template_directory_uri() . '/hamburgers.min.css');
    wp_enqueue_style( 'splide-style', get_template_directory_uri() . '/splide.min.css');
	wp_enqueue_style( 'personal-style', get_template_directory_uri() . '/style.min.css');

    wp_enqueue_script("splide-script", get_template_directory_uri(). '/splide.min.js', array("jquery"), null, false);
    wp_enqueue_script("gsap-script", 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js', array("jquery"), null, true);
    wp_enqueue_script("gsap-scroll-script", 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js', array("jquery"), null, true);
    wp_enqueue_script("gsap-smooth-script", 'https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js', array("jquery"), null, true);


    wp_enqueue_script("personal-script", get_template_directory_uri(). '/script.min.js', array("jquery"), null, true);
}
add_action( 'wp_enqueue_scripts', 'add_personal_scripts' );

/*REMOVE
----------------------------------------------*/

// Remove comments
add_action('admin_init', function () {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

//Remove emoji
function Personal_disable_emoji() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'Personal_disable_emoji' );

    function disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
        return array();
        }
    }
    function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' == $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        return $urls;
    }


    // Functions Part

    //CPT
    require dirname(__FILE__).'/functions-parts/cpt-progetti.php'; 
    require dirname(__FILE__).'/functions-parts/cpt-lavori.php'; 
    require dirname(__FILE__).'/functions-parts/cpt-partner.php'; 
?>