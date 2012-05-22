<?php
/* 
Plugin Name: JDesrosiers FlexSlider 
Plugin URI: 
Description: A simple plugin that integrates FlexSlider (http://www.woothemes.com/flexslider/) with WordPress using custom post types.
Author: Julien Desrosiers
Version: 1.0 
Author URI: http://www.juliendesrosiers.com
*/  

define('JDFS_VERSION', '1.0');  
define('JDFS_NAME', "JDesrosiers FlexSlider");  
define('JDFS_PATH', dirname(__FILE__));  
define('JDFS_URL', WP_PLUGIN_URL . '/' . plugin_basename(JDFS_PATH) . '/');  

// Functions: 
// -----------------------------------------------------------------------

function jdfs_enqueue_assets() {
  wp_enqueue_script('flexslider', JDFS_URL.'js/flexslider.js', array('jquery'));  
  wp_enqueue_style('flexslider_css', JDFS_URL.'css/flexslider.css');  
}

function jdfs_script(){  
  print '<script type="text/javascript" charset="utf-8"> 
    jQuery(window).load(function() { 
      jQuery(".flexslider").flexslider(); 
    }); 
  </script>';  
}

function jdfs_get_slider($slider_slug='') {
  global $post;
  $slider = '<div class="flexslider"><ul class="slides">';
  $query = "post_type=" . JDFS_CPT_TYPE;
  if ($slider_slug !== '') {
    $query .= "&slider=" . $slider_slug;
  }
  query_posts($query);
  if (have_posts()) : while (have_posts()) : the_post();  
    $img = get_the_post_thumbnail($post->ID, 'large');  
    $url = get_post_meta($post->ID, '_url', true);
    if (!empty($url)) {
      $slider .= '<li><a href="' . $url . '">' . $img . '</a></li>';  
    } else {
      $slider .= '<li>' . $img . '</li>';  
    }
  endwhile; endif; wp_reset_query();  
  $slider .= '</ul></div>';

  return $slider; 
}

// the template tag equivalent:
function jdfs_slider($slider_slug = '') {
  print jdfs_get_slider($slider_slug);
}

// Includes:
require_once(JDFS_PATH . '/slider-img-type.php');
require_once(JDFS_PATH . '/slider-taxonomy.php');
  
// Actions:
// -----------------------------------------------------------------------
add_action('wp_head', 'jdfs_script');  
add_action('wp_enqueue_scripts', 'jdfs_enqueue_assets');

// Shortcodes:
// -----------------------------------------------------------------------
add_shortcode('jdflexslider', 'jdfs_get_slider');

