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
    if ( get_option( 'custom_roles_version' ) < 1 ) {
        add_role( 'pp_student', 'Parallel Practicer', get_role( 'author' )->capabilities);
        update_option( 'custom_roles_version', 1 );
    }
}

add_action( 'init', 'pp_make_students' );