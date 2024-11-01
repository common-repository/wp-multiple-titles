<?php
/*
Plugin Name: WP Multiple Titles
Plugin URI: http://www.diije.fr/wp-multiple-titles/
Description: This plugin enables you to have 2 titles for 1 post : one will be displayed on the post page, while the other one will be used on the homepage, the categories, tags and archives. This helps improve your internal linking, as you can see it on many news websites over the interwebs.
Author: Julien Deneuville
Version: 0.1
Author URI: http://www.diije.fr/
*/

/* first, let's add a metabox to set our second title */
//hook to display the box
add_action('add_meta_boxes','dfr_title_metabox_init',1);
function dfr_title_metabox_init() {
	add_meta_box('dfr_title_metabox', 'Homepage Title', 'dfr_title_metabox_display', 'post', 'normal', 'high');
}

//the real box stuff
function dfr_title_metabox_display($post) {
	//check if there's already a title to display
	$title = get_post_meta($post->ID,'_dfr_title',true);
	echo '<input id="dfr_title" type="text" name="dfr_title" value="' . $title . '" style="width:100%"/>';
}

//save the second title when the post is saved
add_action('save_post','dfr_title_metabox_save');
function dfr_title_metabox_save($post_ID) {
	//check if there is a value to save
	if(isset($_POST["dfr_title"])) {
		//save the value, stripping the html tags
		update_post_meta($post_ID,'_dfr_title',esc_html($_POST["dfr_title"]));
	}
}


/* then let's display the stuff */
//hook to the_title, with 2 args in order to get the post ID
add_filter('the_title','dfr_title_display',10,2);
function dfr_title_display($title,$post_ID) {
	//if is_single is false, we are on the homepage, or in an archive-like page
	if(!is_single()) {
		$dfr_title = get_post_meta($post_ID,'_dfr_title',true);
		//if there is a second title, return it
		if ($dfr_title != '') {
			return $dfr_title;
		}
	}
	//else return the actual title
	return $title;
}

?>