<?php
/*
Plugin Name: StartUp Milestones Custom Post
Description: Le plugin pour lactiver le Custom Post Milestones
Author: Yann Caplain
Version: 1
*/

//CPT
function startup_reloaded_milestones() {

	$labels = array(
		'name'                => 'Milestones',
		'singular_name'       => 'Milestone',
		'menu_name'           => 'Milestones',
		'name_admin_bar'      => 'Milestones',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All Items',
		'add_new_item'        => 'Add New Item',
		'add_new'             => 'Add New',
		'new_item'            => 'New Item',
		'edit_item'           => 'Edit Item',
		'update_item'         => 'Update Item',
		'view_item'           => 'View Item',
		'search_items'        => 'Search Item',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash'
	);
	$args = array(
		'label'               => 'milestones',
		'description'         => 'Compteurs Milestones',
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-awards',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
	);
	register_post_type( 'milestones', $args );

}
add_action( 'init', 'startup_reloaded_milestones', 0 );


// Metaboxes
add_action( 'cmb2_init', 'startup_reloaded_metabox_milestones' );

function startup_reloaded_metabox_milestones() {
    require get_template_directory() . '/inc/font-awesome.php';
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_milestones_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Milestone details', 'cmb2' ),
		'object_types'  => array( 'milestones' )
	) );
    
    $cmb_box->add_field( array(
        'name'             => __( 'Icon', 'cmb2' ),
        'id'               => $prefix . 'icon',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => $font_awesome
    ) );
    
	$cmb_box->add_field( array(
		'name'       => __( 'Value', 'cmb2' ),
		'id'         => $prefix . 'value',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Unit', 'cmb2' ),
		'id'         => $prefix . 'unit',
		'type'       => 'text'
	) );
}
?>