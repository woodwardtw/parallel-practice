<?php
/**
 * Basic theme functions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//make student user role
function pp_make_students(){
    $rights = array(
        'upload_files' => true,
          'edit_posts' =>  true,
          'edit_published_posts' => false,
          'publish_posts' => false,
          'read' => true,
          'level_2' => true,
          'level_1' => true,
          'level_0' => true,
          'delete_posts' => false,
          'delete_published_posts' => false,
    );
   if ( get_option( 'custom_roles_version' ) < 1 ) {
        add_role( 'p_student', 'Parallel Practicer', $rights);
       update_option( 'custom_roles_version', 1 );
    }
}

