<?php
/*
Plugin Name: StartUp CPT Milestones
Description: Le plugin pour activer le Custom Post Milestones
Author: Yann Caplain
Version: 1.2.0
Text Domain: startup-cpt-milestones
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//GitHub Plugin Updater
function startup_reloaded_milestones_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-milestones',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-milestones',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-milestones/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-milestones',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-milestones/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_reloaded_milestones_updater' );

//CPT
function startup_reloaded_milestones() {
	$labels = array(
        'name'                => _x( 'Milestones', 'Post Type General Name', 'startup-cpt-milestones' ),
		'singular_name'       => _x( 'Milestone', 'Post Type Singular Name', 'startup-cpt-milestones' ),
		'menu_name'           => __( 'Milestones', 'startup-cpt-milestones' ),
		'name_admin_bar'      => __( 'Milestones', 'startup-cpt-milestones' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-milestones' ),
		'all_items'           => __( 'All Items', 'startup-cpt-milestones' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-milestones' ),
		'add_new'             => __( 'Add New', 'startup-cpt-milestones' ),
		'new_item'            => __( 'New Item', 'startup-cpt-milestones' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-milestones' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-milestones' ),
		'view_item'           => __( 'View Item', 'startup-cpt-milestones' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-milestones' ),
		'not_found'           => __( 'Not found', 'startup-cpt-milestones' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-milestones' )
	);
	$args = array(
        'label'               => __( 'milestones', 'startup-cpt-milestones' ),
        'description'         => __( '', 'startup-cpt-milestones' ),
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

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_milestones_rewrite_flush() {
    startup_reloaded_milestones();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_milestones_rewrite_flush' );

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
		'title'         => __( 'Milestone details', 'startup-cpt-milestones' ),
		'object_types'  => array( 'milestones' )
	) );
    
    $cmb_box->add_field( array(
        'name'             => __( 'Icon', 'startup-cpt-milestones' ),
        'id'               => $prefix . 'icon',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => $font_awesome
    ) );
    
	$cmb_box->add_field( array(
		'name'       => __( 'Value', 'startup-cpt-milestones' ),
		'id'         => $prefix . 'value',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Unit', 'startup-cpt-milestones' ),
		'id'         => $prefix . 'unit',
		'type'       => 'text'
	) );
}

add_action( 'cmb2_admin_init', 'startup_reloaded_milestones_meta' );

// Shortcode
function startup_reloaded_milestones_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => ''
        ), $atts);
    
	// Code
        ob_start();
        require get_template_directory() . '/template-parts/content-milestones.php';
        return ob_get_clean();    
}

add_shortcode( 'milestones', 'startup_reloaded_milestones_shortcode' );

// Enqueue scripts and styles.
function startup_cpt_milestones_scripts() {
    wp_enqueue_script( 'startup-reloaded-counterup', plugins_url( '/js/jquery.counterup.js', __FILE__ ), array( ), '', false );       
    wp_enqueue_script( 'startup-reloaded-waypoint', plugins_url( '/js/waypoint.js', __FILE__ ), array( ), '', false );
    wp_enqueue_style( 'startup-cpt-milestones-style', plugins_url( '/css/startup-cpt-milestones.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_milestones_scripts' );

// Add code to head
function startup_cpt_milestones_head() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ) {
            jQuery('.milestone-count').counterUp({
                delay: 50, // the delay time in ms
                time: 3500 // the speed time in ms
            });
        });
    </script>
<?php }

add_action( 'wp_head', 'startup_cpt_milestones_head' );
?>