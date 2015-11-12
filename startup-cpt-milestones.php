<?php
/*
Plugin Name: StartUp CPT Milestones
Description: Le plugin pour activer le Custom Post Milestones
Author: Yann Caplain
Version: 1.2.0
Text Domain: startup-cpt-milestones
*/

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

add_action( 'init', 'startup_reloaded_milestones_updater' );

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
    wp_enqueue_script( 'startup-reloaded-counterup', plugins_url( '/js/jquery.counterup.js', __FILE__ ), array( ), false, 'all' );       
    wp_enqueue_script( 'startup-reloaded-waypoint', plugins_url( '/js/waypoint.js', __FILE__ ), array( ), false, 'all' );
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