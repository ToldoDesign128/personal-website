<?php 

/**
 * Definisce post type per i Progetti
 */

 add_action( 'init', 'register_progetti_post_type' );
 function register_progetti_post_type() {
 
     /** progetti **/
     $labels = array(
         'name'          => _x( 'Progetti', 'Post Type General Name', 'federico_toldo' ),
         'singular_name' => _x( 'Progetto', 'Post Type Singular Name', 'federico_toldo' ),
         'add_new'       => _x( 'Aggiungi un File', 'Post Type Singular Name', 'federico_toldo' ),
         'add_new_item'  => _x( 'Aggiungi un File', 'Post Type Singular Name', 'federico_toldo' ),
         'edit_item'      => _x( 'Modifica il File', 'Post Type Singular Name', 'federico_toldo' ),
         'view_item'      => _x( 'Visualizza il File', 'Post Type Singular Name', 'federico_toldo' ),
     );
     $args   = array(
         'label'         => __( 'progetti', 'federico_toldo' ),
         'labels'        => $labels,
         'public'        => true,
         'menu_position' => 4,
         'menu_icon'     => 'dashicons-welcome-add-page',
         'supports'      => array('title', 'thumbnail', 'excerpt'),
         'register_meta_box_cb' => 'link_progetti_meta_box'
     );
     register_post_type( 'progetti', $args );

    function link_progetti_meta_box() {

        add_meta_box(
            'link-progetti',
            __( 'Link Progetti', 'sitepoint' ),
            'link_progetti_meta_box_callback'
        );
    
    }
    function link_progetti_meta_box_callback( $post ) {

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'link_progetti_nonce', 'link_progetti_nonce' );
    
        $value = get_post_meta( $post->ID, '_link_progetti', true );
    
        echo '<textarea style="width:100%" id="link_progetti" name="link_progetti">' . esc_attr( $value ) . '</textarea>';
    }
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */
function save_link_progetti_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['link_progetti_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['link_progetti_nonce'], 'link_progetti_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['link_progetti'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['link_progetti'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_link_progetti', $my_data );
}

add_action( 'save_post', 'save_link_progetti_meta_box_data' );

?>