<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/custom-data.php',                          // Load custom data
	'/block-editor.php',                    // Load Block Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$understrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $understrap_includes as $file ) {
	require_once get_theme_file_path( $understrap_inc_dir . $file );
}


function pp_make_students(){
	if ( get_option( 'custom_roles_version' ) < 1 ) {
        add_role( 'pp_student', 'Parallel Practicer', get_role( 'author' )->capabilities);
        update_option( 'custom_roles_version', 1 );
    }
}

add_action( 'init', 'pp_make_students' );

function pp_practice_log(){
	$form_id = 1;//FORM ID
	$search_criteria = array();
  	$sorting         = array();
  	$paging          = array( 'offset' => 0, 'page_size' => 500);
  	$entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, $paging, $total_count );
	foreach ($entries as $key => $value) {  
		pp_table_maker($value, $key);
	}
}


function pp_table_maker($entry, $key){
	//var_dump($entry);
	$date = $entry['date_created'];
	$lang_practice = $entry[1];
	$lang_focus = $entry[3];
	$lang_yea = $entry[4];
	$lang_hmm = $entry[5];
	$lang_strat = $entry[6];
	$alt_practice = $entry[7];
	$alt_focus = $entry[8];
	$alt_yea = $entry[9];
	$alt_hmm = $entry[10];
	$alt_strat = $entry[11];
	echo "
	<div class='accordion' id='practice-data'>
	 <div class='accordion-item'>
	    <h2 class='accordion-header' id='heading-{$key}'>
	      <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-{$key}' aria-expanded='false' aria-controls='collapse-{$key}'>
	        Entry {$date}
	      </button>
	    </h2>
        <div id='collapse-{$key}' class='accordion-collapse collapse' aria-labelledby='heading-{$key}' data-bs-parent='#practice-data'>
  			<div class='accordion-body'>
				<div class='row'>
					<div class='col-md-6 reg-log'>
						<div class='time'>{$lang_practice}</div>
						<div class='focus'>{$lang_focus}</div>
						<div class='yea'>{$lang_yea}</div>
						<div class='hmm'>{$lang_hmm}</div>
						<div class='strat'>{$lang_strat}</div>
					</div>
					<div class='col-md-6 alt-log'>
						<div class='time'>{$alt_practice}</div>
						<div class='focus'>{$alt_focus}</div>
						<div class='yea'>{$alt_yea}</div>
						<div class='hmm'>{$alt_hmm}</div>
						<div class='strat'>{$alt_strat}</div>
					</div>
				</div>
			</div>
	</div>
	";
}