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
  	echo "<table>";
  	echo "<tr>
		    <th>Time</th>
		    <th>Focus</th>
		    <th>Yea</th>
		    <th>Hmm</th>
		    <th>Strategy</th>
		  </tr>";
	foreach ($entries as $key => $value) {  
		pp_table_maker($value);
	}
	echo "</table>";
}


function pp_table_maker($entry){
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
		<tr class='lang-row'>
			<td>{$lang_practice}</td>
			<td>{$lang_focus}</td>
			<td>{$lang_yea}</td>
			<td>{$lang_hmm}</td>
			<td>{$lang_strat}</td>
		</tr>
		<tr class='alt-row'>
			<td>{$alt_practice}</td>
			<td>{$alt_focus}</td>
			<td>{$alt_yea}</td>
			<td>{$alt_hmm}</td>
			<td>{$alt_strat}</td>
		</tr>
	";
}