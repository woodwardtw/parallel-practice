<?php
/**
 * Custom data
 *
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//student custom post type

// Register Custom Post Type student
// Post Type Key: student

function create_student_cpt() {

  $labels = array(
    'name' => __( 'Students', 'Post Type General Name', 'textdomain' ),
    'singular_name' => __( 'Student', 'Post Type Singular Name', 'textdomain' ),
    'menu_name' => __( 'Student', 'textdomain' ),
    'name_admin_bar' => __( 'Student', 'textdomain' ),
    'archives' => __( 'Student Archives', 'textdomain' ),
    'attributes' => __( 'Student Attributes', 'textdomain' ),
    'parent_item_colon' => __( 'Student:', 'textdomain' ),
    'all_items' => __( 'All Students', 'textdomain' ),
    'add_new_item' => __( 'Add New Student', 'textdomain' ),
    'add_new' => __( 'Add New', 'textdomain' ),
    'new_item' => __( 'New Student', 'textdomain' ),
    'edit_item' => __( 'Edit Student', 'textdomain' ),
    'update_item' => __( 'Update Student', 'textdomain' ),
    'view_item' => __( 'View Student', 'textdomain' ),
    'view_items' => __( 'View Students', 'textdomain' ),
    'search_items' => __( 'Search Students', 'textdomain' ),
    'not_found' => __( 'Not found', 'textdomain' ),
    'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
    'featured_image' => __( 'Featured Image', 'textdomain' ),
    'set_featured_image' => __( 'Set featured image', 'textdomain' ),
    'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
    'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
    'insert_into_item' => __( 'Insert into student', 'textdomain' ),
    'uploaded_to_this_item' => __( 'Uploaded to this student', 'textdomain' ),
    'items_list' => __( 'Student list', 'textdomain' ),
    'items_list_navigation' => __( 'Student list navigation', 'textdomain' ),
    'filter_items_list' => __( 'Filter Student list', 'textdomain' ),
  );
  $args = array(
    'label' => __( 'student', 'textdomain' ),
    'description' => __( '', 'textdomain' ),
    'labels' => $labels,
    'menu_icon' => '',
    'supports' => array('title', 'editor', 'revisions', 'author', 'trackbacks', 'custom-fields', 'thumbnail',),
    'taxonomies' => array('category', 'post_tag'),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-universal-access-alt',
  );
  register_post_type( 'student', $args );
  
  // flush rewrite rules because we changed the permalink structure
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
add_action( 'init', 'create_student_cpt', 0 );