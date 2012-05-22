<?php

define('JDFS_TAX_NAME', 'Sliders');
define('JDFS_TAX_SINGLE', 'Slider');
define('JDFS_TAX_SLUG', 'slider');

function create_slider_taxonomy() {
  register_taxonomy(JDFS_TAX_SLUG, array(JDFS_CPT_TYPE), array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( JDFS_TAX_NAME, 'taxonomy general name' ),
      'singular_name' => _x( JDFS_TAX_SINGLE, 'taxonomy singular name' ),
      'search_items' =>  __( 'Search ' . JDFS_TAX_NAME ),
      'all_items' => __( 'All ' . JDFS_TAX_NAME ),
      'parent_item' => __( 'Parent ' . JDFS_TAX_SINGLE ),
      'parent_item_colon' => __( 'Parent ' . JDFS_TAX_SINGLE . ':' ),
      'edit_item' => __( 'Edit ' . JDFS_TAX_SINGLE ), 
      'update_item' => __( 'Update ' . JDFS_TAX_SINGLE ),
      'add_new_item' => __( 'Add New ' . JDFS_TAX_SINGLE ),
      'new_item_name' => __( 'New ' . JDFS_TAX_SINGLE . ' Name' ),
      'menu_name' => __( JDFS_TAX_SINGLE ),
    ),
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => JDFS_TAX_SLUG )
  ));
}

add_action('init', 'create_slider_taxonomy', 0 );

