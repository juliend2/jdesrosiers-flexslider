<?php

define('JDFS_CPT_NAME', 'Slider Images');
define('JDFS_CPT_SINGLE', 'Slider Image');
define('JDFS_CPT_TYPE', 'slider-image');

add_theme_support('post-thumbnails', array(JDFS_CPT_TYPE));

function add_sliderimages_metaboxes() {
  add_meta_box('jdes_sliderimage_url', 'Link URL', 'jdes_sliderimage_url', JDFS_CPT_TYPE, 'normal', 'high');
}

// The SliderImage URL Metabox
function jdes_sliderimage_url() {
  global $post;
  echo '<input type="hidden" name="sliderimagemeta_noncename" '
   . 'id="sliderimagemeta_noncename" value="'
   . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
  // Get the url data if its already been entered
  $url = get_post_meta($post->ID, '_url', true);
  // Echo out the field
  echo '<input type="text" name="_url" value="' . $url  . '" class="widefat" />';
}

// Save the metabox data
function jdes_save_sliderimage_meta($post_id, $post) {
  if ($post->post_type !== JDFS_CPT_TYPE) return $post;
  if (empty($_POST)) return $post;
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce($_POST['sliderimagemeta_noncename'], plugin_basename(__FILE__)) ) {
    return $post->ID;
  }
  // Is the user allowed to edit the post or page?
  if (!current_user_can('edit_post', $post->ID)) {
    return $post->ID;
  }
  // OK, we're authenticated: we need to find and save the data
  // We'll put it into an array to make it easier to loop though.
  $sliderimage_meta['_url'] = $_POST['_url'];
  // Add values of $sliderimage_meta as custom fields
  foreach ($sliderimage_meta as $key => $value) { // Cycle through the $sliderimage_meta array!
    if ($post->post_type == 'revision') return; // Don't store custom data twice
    $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
    update_post_meta($post->ID, $key, $value); // (will add it if not already present)
    if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
  }
}

function jdfs_register_cpt() {
  register_post_type(JDFS_CPT_TYPE, array(
    'label' => __(JDFS_CPT_NAME),  
    'singular_label' => __(JDFS_CPT_SINGLE),  
    'public' => true,  
    'show_ui' => true,  
    'capability_type' => 'post',  
    'hierarchical' => false,  
    'rewrite' => true,  
    'supports' => array('title', 'editor', 'thumbnail'),
    'taxonomies' => array('slider'),
    'register_meta_box_cb' => 'add_sliderimages_metaboxes'
  ));
}

add_action('init', 'jdfs_register_cpt');
add_action('save_post', 'jdes_save_sliderimage_meta', 1, 2);

