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

//redirection
add_action('template_redirect', 'pp_user_redirection');

function pp_user_redirection(){
    global $post;
    global $wp;
    $url = get_site_url();
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    if(!is_user_logged_in() && $current_url != $url . '/register'){
        wp_redirect($url . '/register'); 
        exit;
    }
    else if(is_user_logged_in()){
        $user_id = get_current_user_id();
        $slug = wp_get_current_user()->user_login;
        if(pp_user_has_role($user_id, 'p_student')){
            if($post->post_name != $slug && $current_url != $url . '/student/' . $slug){
                wp_redirect($url . '/student/' . $slug); 
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


//MAKE STUDENTS AND MAKE STUDENT PAGES
$students = ['usera@foo.com','userb@bar.com'];

function pp_bulk_maker($students){
    foreach ($students as $key => $student) {
        // code...
        pp_student_maker($student);
    }
}
function pp_student_maker($email){
    $username = explode('@',$email)[0];
    $userdata = array(
        'user_pass'             => '***PleaseChangeMeQuick!***',  //(string) The plain-text user password.
        'user_login'            => $username,  //(string) The user's login username.
        'user_email'            => $email,  //(string) The user email address.
        'show_admin_bar_front'  => 'false',  //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
        'role'                  => 'p_student',  //(string) User's role.
    );
    $user_id = wp_insert_user($userdata);
    pp_student_page_maker($user_id, $email, $username);
}

function pp_student_page_maker($user_id, $email, $username){
    $args = array(
      'post_title'    => $username,
      'post_content'  => ' ',
      'post_status'   => 'publish',
      'post_author'   => $user_id,
      'post_type'     => 'student'  
    );

    // Insert the post into the database
    $post_id = wp_insert_post( $args );
    update_post_meta($post_id, 'author_email', $email);
    update_post_meta($post_id, 'author_login', $username);

}

pp_bulk_maker($students);