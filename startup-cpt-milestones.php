<?php
/*
Plugin Name: StartUp Milestones Custom Post
Description: Le plugin pour activer le Custom Post Milestones
Author: Yann Caplain
Version: 1.0.0
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
        'capability_type'     => array('milestone','milestones'),
        'map_meta_cap'        => true
	);
	register_post_type( 'milestones', $args );

}

add_action( 'init', 'startup_reloaded_milestones', 0 );

// Capabilities

function startup_reloaded_milestones_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_milestone' );
	$role_admin->add_cap( 'read_milestone' );
	$role_admin->add_cap( 'delete_milestone' );
	$role_admin->add_cap( 'edit_others_milestones' );
	$role_admin->add_cap( 'publish_milestones' );
	$role_admin->add_cap( 'edit_milestones' );
	$role_admin->add_cap( 'read_private_milestones' );
	$role_admin->add_cap( 'delete_milestones' );
	$role_admin->add_cap( 'delete_private_milestones' );
	$role_admin->add_cap( 'delete_published_milestones' );
	$role_admin->add_cap( 'delete_others_milestones' );
	$role_admin->add_cap( 'edit_private_milestones' );
	$role_admin->add_cap( 'edit_published_milestones' );
}

register_activation_hook( __FILE__, 'startup_reloaded_milestones_caps' );

// Metaboxes
function startup_reloaded_milestones_meta() {
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

add_action( 'cmb2_init', 'startup_reloaded_milestones_meta' );
?>