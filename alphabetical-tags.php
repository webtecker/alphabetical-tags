<?php
/*
Plugin Name: Alphabetical Tags
Version: 1.0
Plugin URI: http://www.webtecker.com/alphabetical-tags
Author: Brett Bittke
Author URI: http://www.webtecker.com
Description: Puts Tags in Alphabetical Order.
*/
define('ALPHABETICAL_TAGS', "1.0");
define('ALPHABETICAL_TAGS_FOLDER', dirname(plugin_basename(__FILE__)));
define('ALPHABETICAL_TAGS_URL', plugins_url('',__FILE__));

add_action('wp_enqueue_scripts', 'alphabetical_scripts_handler');
add_shortcode('alphabetical_tags','alphabetical_tags_handler');


function alphabetical_scripts_handler(){
		//Javascript
		wp_register_script('alphabetical_tags_js', ALPHABETICAL_TAGS_URL.'/alphabetical_tags.js', array('jquery','jquery-ui-core'), '1.0');
    	wp_enqueue_script( 'alphabetical_tags_js' );
		//CSS
		wp_register_style('alphabetical_tags_style', ALPHABETICAL_TAGS_URL.'/alphabetical_tags.css');
        wp_enqueue_style( 'alphabetical_tags_style');
}//end alphabetical_scripts_handler

function alphabetical_tags_handler($atts,$content=NULL){
	extract(shortcode_atts(array(
		'exclude'	=>'',
		'order'     => 'ASC',
		'hide_empty'=> false,
		'tag_description'=> false,
		'tag_count'=> false,
	), $atts));
	$output = "";
	$tabs = "";
	$tabs_content = "";
	if($order == "ASC"){
		$characters = range('a','z'); 
	} else {
		$characters = range('z','a'); 
	}
	if( $characters && is_array( $characters ) ) { 
	 	$before = '<div id="alphabetical-tags">';
		$tabs .=  '<ul class="tabnav">';
        foreach( $characters as $character ) { 
            // Get the tag information for each characters in the array. 
            $tags = get_tags( array('name__like' => $character, 'order' => 'ASC', 'exclude'=>$exlude) ); 
			
			if(!$hide_empty || ($tags->count > 0)){
				$tabs .= '<li><a href="#tag-'.$character.'">'.$character.'</a></li>';
			}
			//------------------------------------------Loop through Tags---------------------------------------------//
			if ($tags) { 
			 	$tabs_content .= '<div id="tag-'.$character.'" class="tabdiv"><h4>'.$character.'</h4><ul>';
			 	foreach ( $tags as $tag ) { 
					$tag_link = get_tag_link($tag->term_id);
					$tabs_content .= '<li>';
						$tabs_content .= '<a href="'.$tag_link.'" title="View the articles tagged '.$tag->name.'">'.$tag->name.'</a>';
						if($tag_count){
							$tabs_content .= '<span>('.$tag->count.')</span>';
						}
						if($tag_description){	
							$tabs_content .= '<div class="tag-description">'.$tag->description.'</div>';
						}
					$tabs_content .= '</li>';
				}//end $tags Loop
				$tabs_content .= '</ul></div>';
			}//end $tags check
			
			
		}//end foreach $characters loop
		$tabs .=  '</ul>';
		$after = '</div>';
	}//end $characters Check
	$output .= $before.$tabs.$tabs_content.$after;
	return $output;
}

?>