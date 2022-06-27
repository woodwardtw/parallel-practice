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


//Form entry display for student page
function pp_practice_log(){
	$form_id = 1;//FORM ID
	$search_criteria = array();
  	$sorting         = array();
  	$paging          = array( 'offset' => 0, 'page_size' => 500);
  	$entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, $paging, $total_count );
  	echo "<div class='accordion' id='practice-data'>";
	foreach ($entries as $key => $value) {  
		pp_table_maker($value, $key);
	}
	echo "</div>";
}


function pp_accordion_state($key){
	if($key === 0){
		return 'show';
	} else {
		return '';
	}
}

function pp_aria_state($key){
	if($key === 0){
		return 'true';
	} else {
		return 'false';
	}
}

function pp_collapse_state($key){
	if($key === 0){
		return '';
	} else {
		return 'collapsed';
	}
}

function pp_table_maker($entry, $key){
	//var_dump($entry);
	$entry_id = $entry['id'];
	$date = substr($entry['date_created'], 0,10);
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
	$state = pp_accordion_state($key);
	$aria = pp_aria_state($key);
	$collapse = pp_collapse_state($key);
	echo "
		<div class='accordion-item' data-entryid='{$entry_id}' data-pdate='{$date}' data-practice='{$lang_practice}' data-alt='{$alt_practice}' >
		    <h2 class='accordion-header' id='heading-{$key}'>
		      <button class='accordion-button {$collapse}' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-{$key}' aria-expanded='{$aria}' aria-controls='collapse-{$key}'>
		        Entry {$date}
		      </button>
		    </h2>
        	<div id='collapse-{$key}' class='accordion-collapse collapse {$state}' aria-labelledby='heading-{$key}' data-bs-parent='#practice-data'>
  			<div class='accordion-body d-flex justify-content-between flex-wrap'>				
				<div class='time'>
					<h2>⏰ {$lang_practice} minutes</h2>							
				</div>

				<div class='time alt'>
					<h2>⏰ {$alt_practice} minutes</h2>							
				</div>

				<div class='focus'>
					<h2>Focus</h2>
					{$lang_focus}
				</div>

				<div class='focus alt'>
					<h2>Focus</h2>						
					{$alt_focus}
				</div>

				<div class='yea'>
					<h2>🥳 yeaaa</h2>						
					{$lang_yea}
				</div>

				<div class='yea alt'>
					<h2>🥳 yeaaa</h2>
					{$alt_yea}
				</div>

				<div class='hmm'>
					<h2>🤔 hmmmm</h2>
					{$lang_hmm}
				</div>

				<div class='hmm alt'>
					<h2>🤔 hmmmm</h2>						
					{$alt_hmm}
				</div>

				<div class='strat'>
					<h2>Strategy</h2>
					{$lang_strat}
				</div>					
				
				<div class='strat alt'>
					<h2>Strategy</h2>
					{$alt_strat}
				</div>
			</div>
			<button type='button' data-bs-toggle='modal' data-bs-target='#logData' class='btn btn-primary edit-entry' data-entryid='{$entry_id}' data-practice='{$lang_practice}' data-focus ='{$lang_focus}' data-yea='{$lang_yea}' data-hmm='{$lang_hmm}' data-strat='{$lang_strat}' data-altpractice='{$alt_practice}' data-altfocus ='{$alt_focus}' data-altyea='{$alt_yea}' data-althmm='{$alt_hmm}' data-altstrat='{$alt_strat}'>Edit</button>
		</div>
	</div>
	";
}


//updates entries via form #3
add_action( 'gform_after_submission_1', 'pp_update_gfentry', 10, 2 );

function pp_update_gfentry($entry, $form){
	if($entry[15] > 0){
		$entry_id = $entry[15];
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
		GFAPI::update_entry_field( $entry_id, 1, $lang_practice );
		GFAPI::update_entry_field( $entry_id, 3, $lang_focus );
		GFAPI::update_entry_field( $entry_id, 4, $lang_yea );
		GFAPI::update_entry_field( $entry_id, 5, $lang_hmm );
		GFAPI::update_entry_field( $entry_id, 6, $lang_strat );
		GFAPI::update_entry_field( $entry_id, 7, $alt_practice );
		GFAPI::update_entry_field( $entry_id, 8, $alt_focus );
		GFAPI::update_entry_field( $entry_id, 9, $alt_yea );
		GFAPI::update_entry_field( $entry_id, 10, $alt_hmm );
		GFAPI::update_entry_field( $entry_id, 11, $alt_strat );	
		GFAPI::delete_entry($entry['id']);//auto delete so we don't end up with duplicates
	} 	

}