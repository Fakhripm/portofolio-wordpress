<?php
// Minimal child theme functions: enqueue styles and register `project` CPT

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'minimal-static-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'minimal-static-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'minimal-static-style' ) );
    // Always enqueue the front-page stylesheet so the intended dark theme
    // styling is available regardless of which template or body classes are used.
    wp_enqueue_style( 'minimal-static-frontpage-style', get_stylesheet_directory_uri() . '/front-page.css', array( 'minimal-static-child-style' ) );
} );

// Register a simple `project` custom post type
function minimal_static_register_project_cpt() {
    $labels = array(
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'menu_name'          => 'Projects',
        'name_admin_bar'     => 'Project',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'new_item'           => 'New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'projects' ),
        'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'minimal_static_register_project_cpt' );

// Exclude `project` from main blog loops
function minimal_static_exclude_projects_from_main_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_home() || $query->is_search() ) {
            $post_types = $query->get( 'post_type' );
            if ( empty( $post_types ) ) {
                $query->set( 'post_type', array( 'post' ) );
            } elseif ( is_array( $post_types ) ) {
                $filtered = array_diff( $post_types, array( 'project' ) );
                $query->set( 'post_type', $filtered ?: array( 'post' ) );
            }
        }
    }
}
add_action( 'pre_get_posts', 'minimal_static_exclude_projects_from_main_query' );

// Register a primary menu location so the front page can use a real menu
add_action( 'after_setup_theme', function() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'minimal-static-child' ),
    ) );
} );
