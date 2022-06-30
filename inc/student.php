<?php
/**
 * Student specific functions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



//Form entry display for student page
function pp_practice_log(){
    global $post;
    $user_login = get_the_author_meta('user_login');
    $form_id = 1;//FORM ID

    $search_criteria['field_filters'][] = array( 'key' => '14', 'value' => $user_login);

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

function pp_comment_button($entry_id, $comment){
    if(current_user_can('Administrator')){
        $comment = htmlspecialchars($comment,ENT_QUOTES);
        return "<button type='button' data-bs-toggle='modal' data-bs-target='#comment' class='btn btn-primary comment-entry' data-entryid='{$entry_id}' data-comment='{$comment}'>Comment</button>"; 
    } else {
        return '';
    }
   
}

function pp_table_maker($entry, $key){
    //var_dump(get_the_author_meta('user_login'));
    $form_user = $entry[14];
    $entry_id = $entry['id'];
    $date = substr($entry['date_created'], 0,10);
    $lang_practice = htmlspecialchars($entry[1],ENT_QUOTES);
    $lang_focus = htmlspecialchars($entry[3],ENT_QUOTES);
    $lang_yea = htmlspecialchars($entry[4],ENT_QUOTES);
    $lang_hmm = htmlspecialchars($entry[5],ENT_QUOTES);
    $lang_strat = htmlspecialchars($entry[6],ENT_QUOTES);
    $alt_practice = htmlspecialchars($entry[7],ENT_QUOTES);
    $alt_focus = htmlspecialchars($entry[8],ENT_QUOTES);
    $alt_yea = htmlspecialchars($entry[9],ENT_QUOTES);
    $alt_hmm = htmlspecialchars($entry[10],ENT_QUOTES);
    $alt_strat = htmlspecialchars($entry[11],ENT_QUOTES);
    $comment = htmlspecialchars($entry[16],ENT_QUOTES);
    $state = pp_accordion_state($key);
    $aria = pp_aria_state($key);
    $collapse = pp_collapse_state($key);
    $comment_button = pp_comment_button($entry_id, $comment);
    if($form_user === get_the_author_meta('user_login')){
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
                <div class='feedback'>
                <h2>Feedback</h2>
                    {$comment}
                </div>
            </div>
            <button type='button' data-bs-toggle='modal' data-bs-target='#logData' class='btn btn-primary edit-entry' data-entryid='{$entry_id}' data-practice='{$lang_practice}' data-focus ='{$lang_focus}' data-yea='{$lang_yea}' data-hmm='{$lang_hmm}' data-strat='{$lang_strat}' data-altpractice='{$alt_practice}' data-altfocus ='{$alt_focus}' data-altyea='{$alt_yea}' data-althmm='{$alt_hmm}' data-altstrat='{$alt_strat}'>Edit</button>
            {$comment_button}
        </div>
    </div>
    ";

    }
    
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


//comment adder
add_action( 'gform_after_submission_4', 'pp_practice_comment', 10, 2 );

function pp_practice_comment($entry, $form){
    $entry_id = $entry[2];
    $comment = $entry[1];
    GFAPI::update_entry_field( $entry_id, 16, $comment );
    GFAPI::delete_entry($entry['id']);//auto delete so we don't end up with duplicates
}


//make sure the author is correct ... did this correctly in gravity forms instead
//add_action( 'save_post', 'pp_author_verify', 10, 3 );
//add_action( 'update_post', 'pp_author_verify', 10, 3 );
// function pp_author_verify($post_id, $post, $update){
//     $login = get_post_meta($post_id, 'author_login', true);
//     $author = get_the_author_meta('user_login', $post->post_author);
//     if($login && $author && $login != $author && username_exists($login)){
//         $user_id = get_user_by('user_login', $login)->ID;
//         $author_fix = array(
//           'ID'           => $post_id,
//           'post_author'  => $user_id
          
//       );
     
//     // Update the post into the database
//       wp_update_post( $author_fix );

//     }
// }