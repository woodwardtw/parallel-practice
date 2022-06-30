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


//redirections
add_action('template_redirect', 'pp_user_redirection');

function pp_user_redirection(){
    global $post;
    global $wp;
    $url = get_site_url();
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    //var_dump($current_url);
    if(!is_user_logged_in() && $current_url != $url . '/register'){
        wp_redirect($url . '/register'); 
        exit;
    }
    if(is_user_logged_in()){
        $user_id = get_current_user_id();
        if(pp_user_has_role($user_id, 'p_student')){
            $slug = wp_get_current_user()->user_login;
            if($post->post_name != $slug){
                wp_redirect($url . '/' . $slug); 
                exit;
            }
        }
    }
}


//user registration set slug
add_action( 'gform_advancedpostcreation_post_after_creation', 'pp_set_student_slug', 10, 4 );

function pp_set_student_slug($post_id, $feed, $entry, $form){
    wp_update_post([
      "post_name" => $entry[7],
      "ID" => $post_id,
    ]);
}


function pp_user_has_role($user_id, $role_name){
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        return in_array($role_name, $user_roles);
    }

//redirects from dashboard to edit post list 
function pp_remove_the_dashboard () {
      if( is_admin() && !defined('DOING_AJAX') && ( !current_user_can('administrator' ) ) ){
        wp_redirect(home_url());
        exit;
      }
}
add_action('admin_menu', 'pp_remove_the_dashboard');


//hide admin bar 

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}