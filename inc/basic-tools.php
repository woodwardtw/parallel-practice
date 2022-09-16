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


//MAKE STUDENTS AND MAKE STUDENT PAGES ***************************************************************
//make this into something real in the future with CSV upload option
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

//pp_bulk_maker($students);

//login shortcode

function pp_login_shortcode(){
    global $post;
    global $wp;
    $url = get_site_url();
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    if(!is_user_logged_in()){
        $args = array(
            'echo'  => true,
        );
        echo wp_login_form();
    } 
    if(is_user_logged_in()){
        $user_id = get_current_user_id();
        if (!pp_user_has_role($user_id, 'p_student') && current_user_can('administrator')){
            $current_user = wp_get_current_user();
            $name = $current_user->user_nicename;
            echo "<h2>Hi {$name}!</h2>";
            pp_list_students();
        }
    }
}

add_shortcode( 'login', 'pp_login_shortcode' );

function pp_list_students(){
    $args = array(
          'post_type' => 'student',
          'post_status' => 'publish',
          'posts_per_page' => 50,
          'orderby'     => 'title',
          'order' => 'ASC'
    );

    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) :
        echo "<ol>";
    while ( $the_query->have_posts() ) : $the_query->the_post();
      // Do Stuff
        $title = get_the_title();
        $link = get_permalink();
        $user_login = get_the_author_meta('user_login');
        $count = pp_preview_counter($user_login);
        $chart = pp_bar_chart($count);
        echo "<li class='student-link'><a href='{$link}'>{$title}</a> - {$count} <div class='chart-it'>{$chart}</div></li>";
    endwhile;
    echo "</ol>";
    endif;

    // Reset Post Data
    wp_reset_postdata();
}

function pp_preview_counter($user_login){     
    //var_dump($user_login);
    $form_id = 1;//FORM ID

    $search_criteria['field_filters'][] = array( 'key' => '14', 'value' => $user_login);

    $sorting         = array();
    $paging          = array( 'offset' => 0, 'page_size' => 500);
    $entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, $paging, $total_count );
    return sizeof($entries);
}

function pp_bar_chart($count){
    $bars = '';
    for ($x = 0; $x <= $count; $x++) {
      $bars = $bars . "|";
    }
    return $bars;
}

//rename student posts if user has set first last names

add_filter('the_title', 'pp_student_title_filter');
function pp_student_title_filter($title) {
   global $post;
   $author_id = $post->post_author;
   if(get_the_author_meta('last_name', $author_id) && get_the_author_meta('first_name', $author_id) && $post->post_type === 'student'){
    $last = get_the_author_meta('last_name', $author_id);
    $first = get_the_author_meta('first_name', $author_id);
        return "{$last}, {$first}";
   } else {
    return $title;
   }
}
