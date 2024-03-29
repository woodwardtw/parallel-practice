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
    //var_dump($user_login);
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
    if(current_user_can('administrator')){
        $comment = htmlspecialchars($comment,ENT_QUOTES);
        return "<button type='button' data-bs-toggle='modal' data-bs-target='#comment' class='btn btn-primary comment-entry' data-entryid='{$entry_id}' data-comment='{$comment}'>Comment</button>"; 
    } else {
        return '';
    }
   
}

function pp_learning_reflection_selections($entry){
    $selected = array();
    $selections = array(1,2,3,4);
    foreach ($selections as $selection) {
        $id = '17.' . $selection;
        if($entry[$id] != ''){
            array_push($selected, $selection);
        }
    }  
    return $selected;
}


function pp_help_flag($data){
    if(strpos($data, 'assistance')){
        return 'help';
    } else {
        return '';
    }
}

function pp_multichoice($entry, $base, $repeat){
    $responses = array();
    for ($x = 1; $x <= $repeat; $x++) {
      $key = $base .'.'. $x;
      if($entry[$key]){
        array_push($responses, $entry[$key]);
      }
    }
    return implode(', ', $responses);
}

function pp_table_maker($entry, $key){
    //var_dump(get_the_author_meta('user_login'));
    $form_user = $entry[14];
    $entry_id = $entry['id'];
    $date = substr($entry['date_created'], 0,10);
    $course = $entry['22'];
    $lang_practice = htmlspecialchars($entry[1],ENT_QUOTES);
    $lang_emotion = $entry['23'];
    //var_dump($entry);
    $alt_practice = htmlspecialchars($entry[7],ENT_QUOTES);
    $alt_emotion = $entry['24'];

    $comment = htmlspecialchars($entry[16],ENT_QUOTES);

    $reflection_selection = implode(', ', pp_learning_reflection_selections($entry));
    $reflection_type = htmlspecialchars($entry[22],ENT_QUOTES);
    $reflection_listening = htmlspecialchars($entry[18],ENT_QUOTES);
    $reflection_deverb = htmlspecialchars($entry[21],ENT_QUOTES);
    $reflection_notes = htmlspecialchars($entry[26],ENT_QUOTES);
    $reflection_reexpress = htmlspecialchars($entry[27],ENT_QUOTES);
    $reflection_delivery = htmlspecialchars($entry[28],ENT_QUOTES);
    $reflection_other = htmlspecialchars($entry[30],ENT_QUOTES);
    $reflection_evs = htmlspecialchars($entry[29],ENT_QUOTES);
    $reflection_multitask = htmlspecialchars($entry[19],ENT_QUOTES);
    $alt_parallel = htmlspecialchars($entry[32],ENT_QUOTES);
    $reflection_detail = htmlspecialchars($entry[35],ENT_QUOTES);
    $consecutive = pp_multichoice($entry, '17', 6);
    //var_dump($consecutive);
    $simaltaneous = pp_multichoice($entry, '25', 5);

    $share = pp_multichoice($entry, '34', 6);

    $help_request = pp_help_flag($share);

    $reflection_listening_html = pp_reflection_blocks($reflection_listening, 'Listening Reflection');
    $reflection_deverb_html = pp_reflection_blocks($reflection_deverb, 'Deverbalization Reflection');
    $reflection_notes_html = pp_reflection_blocks($reflection_notes, 'Note-Taking Reflection');
    $reflection_reexpress_html = pp_reflection_blocks($reflection_reexpress, 'Reexpression Reflection');
    $reflection_delivery_html = pp_reflection_blocks($reflection_delivery, 'Delivery Reflection');
    $reflection_other_html = pp_reflection_blocks($reflection_other, 'Other Reflection');
    $reflection_evs_html = pp_reflection_blocks($reflection_evs, 'EVS Reflection');
    $reflection_multitask_html = pp_reflection_blocks($reflection_multitask, 'Multitasking Reflection');
    $reflection_detail_html = pp_reflection_blocks($reflection_detail, 'Additional Details');
    $comment_html = pp_reflection_blocks($comment, 'Feedback');

    $state = pp_accordion_state($key);
    $aria = pp_aria_state($key);
    $collapse = pp_collapse_state($key);
    $comment_button = pp_comment_button($entry_id, $comment);
    if($form_user === get_the_author_meta('user_login')){
        echo "
        <div class='accordion-item' data-entryid='{$entry_id}' data-pdate='{$date}' data-practice='{$lang_practice}' data-alt='{$alt_practice}' >
            <h2 class='accordion-header' id='heading-{$key}'>
              <button class='accordion-button {$collapse} {$help_request}' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-{$key}' aria-expanded='{$aria}' aria-controls='collapse-{$key}'>
                {$reflection_type} Entry - {$date}
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
                    <h2>Satisfaction Level</h2>
                    {$lang_emotion} of 5
                </div>

                <div class='focus alt'>
                    <h2>Satisfaction Level</h2>
                    {$alt_emotion} of 5                    
                </div>

                 <div class='parallel alt'>
                    <h2>Parallel Level</h2>
                    {$alt_parallel} of 5                    
                </div>
                 <div class='parallel alt'>
                                  
                </div>
                    {$reflection_listening_html} 
                    {$reflection_deverb_html}
                    {$reflection_notes_html}
                    {$reflection_reexpress_html}
                    {$reflection_delivery_html }
                    {$reflection_other_html}
                    {$reflection_evs_html}
                    {$reflection_multitask_html}
                    {$reflection_detail_html}
                <div class='feedback full'>
                    {$comment_html}
                </div>
            </div>
            <button id='btn-{$entry_id}' type='button' data-bs-toggle='modal' data-bs-target='#logData' class='btn btn-primary edit-entry' data-entryid='{$entry_id}' 
                data-type='{$reflection_type}'
                
                data-practice='{$lang_practice}' 
                data-altpractice='{$alt_practice}' 

                data-satisfaction='{$lang_emotion}' 
                data-altsatisfaction='{$alt_emotion}'
                
                data-parallel='{$alt_parallel}' 
                data-consecutive='{$consecutive}' 
                data-simal='{$simaltaneous}' 
                data-listening='{$reflection_listening}'
                data-deverb='{$reflection_deverb}'
                data-notes='{$reflection_notes}'
                data-delivery='{$reflection_delivery}'
                data-other='{$reflection_other}'
                data-evs='{$reflection_evs}'
                data-reexpress='{$reflection_reexpress}'
                data-multitask='{$reflection_multitask}'
                data-share='{$share}'
                data-detail='{$reflection_detail}'
               
                >
                Edit</button>
            {$comment_button}
        </div>
    </div>
    ";

    }
    
}


function pp_reflection_blocks($entry, $title){
    if($entry != ''){
        $id = sanitize_title($title);
      $html =  "<div class='reflection full'>
                <h2>{$title}</h2>
                    <div class='full' id='{$id}'>
                        {$entry}
                    </div>
                </div>";
    return $html;
    } else {
        return '';
    }
}

//updates entries via form #3
add_action( 'gform_after_submission_1', 'pp_update_gfentry', 10, 2 );

function pp_update_gfentry($entry, $form){
    if($entry[15] > 0){
        // $keys = array_keys($entry);
       //var_dump($entry);
        $entry_id = $entry[15];
        $lang_practice = $entry[1];//time
        $alt_practice = $entry[7];//alt time

        $lang_emotion = $entry[23];//satisfaction
        $alt_emotion = $entry[24];//alt satsifaction

        $parallel = $entry[32];

//updates
        GFAPI::update_entry_field( $entry_id, 1, $lang_practice );//times
        GFAPI::update_entry_field( $entry_id, 7, $alt_practice );

        GFAPI::update_entry_field( $entry_id, 23, $lang_emotion );//satisfaction
        GFAPI::update_entry_field( $entry_id, 24, $alt_emotion );
        
        GFAPI::update_entry_field( $entry_id, 32, $parallel );//parallel nature

        GFAPI::update_entry_field( $entry_id, 18, $entry[18] );//listening
        GFAPI::update_entry_field( $entry_id, 21, $entry[21] );//deverb
        GFAPI::update_entry_field( $entry_id, 26, $entry[26] );//notes
        GFAPI::update_entry_field( $entry_id, 27, $entry[27] );//reexpression
        GFAPI::update_entry_field( $entry_id, 28, $entry[28] );//delivery
        GFAPI::update_entry_field( $entry_id, 30, $entry[30] );//other

        GFAPI::update_entry_field( $entry_id, 29, $entry[29] );//evs
        GFAPI::update_entry_field( $entry_id, 19, $entry[19] );//multitasking   

        GFAPI::update_entry_field( $entry_id, 35, $entry[35] );//detail        

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

