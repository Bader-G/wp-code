<?php
if(!function_exists('programs_post_type')){
    function programs_post_type() {
        register_post_type( 'programs',
          array(
            'labels' => array(
              'name' => __( 'Programs' ),
              'singular_name' => __( 'Program' )
            ),
            'public' => true,
            'has_archive' => false,
            'show_in_rest' => true,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
          )
        );
      }
}
add_action( 'init', 'programs_post_type' );