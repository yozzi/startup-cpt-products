<?php
/*
Plugin Name: StartUp CPT Products
Description: Le plugin pour activer le Custom Post Products
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-products
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Include this to check if a plugin is activated with is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//GitHub Plugin Updater
function startup_cpt_products_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-products',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-products',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-products/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-products',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-products/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_products_updater' );

//CPT
function startup_cpt_products() {
	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'startup-cpt-products' ),
		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'startup-cpt-products' ),
		'menu_name'           => __( 'Products', 'startup-cpt-products' ),
		'name_admin_bar'      => __( 'Products', 'startup-cpt-products' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-products' ),
		'all_items'           => __( 'All Items', 'startup-cpt-products' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-products' ),
		'add_new'             => __( 'Add New', 'startup-cpt-products' ),
		'new_item'            => __( 'New Item', 'startup-cpt-products' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-products' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-products' ),
		'view_item'           => __( 'View Item', 'startup-cpt-products' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-products' ),
		'not_found'           => __( 'Not found', 'startup-cpt-products' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-products' )
	);
	$args = array(
		'label'               => __( 'products', 'startup-cpt-products' ),
		'description'         => __( 'Post Type Description', 'startup-cpt-products' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions', ),
		//'taxonomies'          => array( 'product_types' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-products',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
        'capability_type'     => array('product','products'),
        'map_meta_cap'        => true
	);
	register_post_type( 'products', $args );

}

add_action( 'init', 'startup_cpt_products', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_products_rewrite_flush() {
    startup_cpt_products();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_products_rewrite_flush' );

// Capabilities
function startup_cpt_products_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_product' );
	$role_admin->add_cap( 'read_product' );
	$role_admin->add_cap( 'delete_product' );
	$role_admin->add_cap( 'edit_others_products' );
	$role_admin->add_cap( 'publish_products' );
	$role_admin->add_cap( 'edit_products' );
	$role_admin->add_cap( 'read_private_products' );
	$role_admin->add_cap( 'delete_products' );
	$role_admin->add_cap( 'delete_private_products' );
	$role_admin->add_cap( 'delete_published_products' );
	$role_admin->add_cap( 'delete_others_products' );
	$role_admin->add_cap( 'edit_private_products' );
	$role_admin->add_cap( 'edit_published_products' );
}

register_activation_hook( __FILE__, 'startup_cpt_products_caps' );

// Products taxonomy
function startup_reloaded_product_categories() {
	$labels = array(
		'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'startup-cpt-products' ),
		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'startup-cpt-products' ),
		'menu_name'                  => __( 'Product Categories', 'startup-cpt-products' ),
		'all_items'                  => __( 'All Items', 'startup-cpt-products' ),
		'parent_item'                => __( 'Parent Item', 'startup-cpt-products' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-cpt-products' ),
		'new_item_name'              => __( 'New Item Name', 'startup-cpt-products' ),
		'add_new_item'               => __( 'Add New Item', 'startup-cpt-products' ),
		'edit_item'                  => __( 'Edit Item', 'startup-cpt-products' ),
		'update_item'                => __( 'Update Item', 'startup-cpt-products' ),
		'view_item'                  => __( 'View Item', 'startup-cpt-products' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-cpt-products' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-cpt-products' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-products' ),
		'popular_items'              => __( 'Popular Items', 'startup-cpt-products' ),
		'search_items'               => __( 'Search Items', 'startup-cpt-products' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-products' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'product-category', array( 'products' ), $args );

}

add_action( 'init', 'startup_reloaded_product_categories', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_reloaded_product_categories_metabox_remove() {
	remove_meta_box( 'tagsdiv-product-category', 'products', 'side' );
    // tagsdiv-product_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_reloaded_product_categories_metabox_remove' );

// Metaboxes
/**
 * Detection de CMB2. Identique dans tous les plugins.
 */
if ( !function_exists( 'cmb2_detection' ) ) {
    function cmb2_detection() {
        if ( !defined( 'CMB2_LOADED' ) ) {
            add_action( 'admin_notices', 'cmb2_notice' );
        }
    }

    function cmb2_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>' . __( 'CMB2 plugin or StartUp Reloaded theme must be active to use custom metaboxes.', 'startup-cpt-products' ) . '</p></div>';
        }
    }

    add_action( 'init', 'cmb2_detection' );
}

function startup_cpt_products_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_products_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Product details', 'startup-cpt-products' ),
		'object_types'  => array( 'products' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'startup-cpt-products' ),
		'desc' => __( 'Main image of the product, may be different from the thumbnail. i.e. 3D model', 'startup-cpt-products' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'       => __( 'Short description', 'startup-cpt-products' ),
		'desc'       => __( 'i.e. "New business building in Montreal"', 'startup-cpt-products' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Categoy', 'startup-cpt-products' ),
		'desc'     => __( 'Select the category(ies) of the product', 'startup-cpt-products' ),
		'id'       => $prefix . 'category',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'product-category', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Status', 'startup-cpt-products' ),
		'desc'             => __( 'The product\'s current status', 'startup-cpt-products' ),
		'id'               => $prefix . 'status',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Available' => __( 'Available', 'startup-cpt-products' ),
			'Sold out soon'   => __( 'Sold out soon', 'startup-cpt-products' ),
			'Sold out'     => __( 'Sold out', 'startup-cpt-products' ),
            'Back order'     => __( 'Back order', 'startup-cpt-products' ),
            'Sale closed'     => __( 'Sale closed', 'startup-cpt-products' ),
            'Unavailable'     => __( 'Unavailable', 'startup-cpt-products' )
		),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'startup-cpt-products' ),
		'desc' => __( 'Full, main description', 'startup-cpt-products' ),
		'id'   => $prefix . 'description',
		'type' => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => false, // show insert/upload button(s)
            'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
    ),
	) );

	$cmb_box->add_field( array(
		'name' => __( 'Price', 'startup-cpt-products' ),
		'desc' => __( 'The product price in Canadian Dollar', 'startup-cpt-products' ),
		'id'   => $prefix . 'price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Special Price', 'startup-cpt-products' ),
		'desc' => __( 'The product special price in Canadian Dollar', 'startup-cpt-products' ),
		'id'   => $prefix . 'special_price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name'         => __( 'Gallery', 'startup-cpt-products' ),
		'desc'         => __( 'Upload or add multiple images for product photo gallery.', 'startup-cpt-products' ),
		'id'           => $prefix . 'gallery',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'startup-cpt-products' ),
		'desc' => __( 'Link to te product on an extrenal website (i.e. real estate agency)', 'startup-cpt-products' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );
}

add_action( 'cmb2_admin_init', 'startup_cpt_products_meta' );

// Shortcode
function startup_cpt_products_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => ''
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) ) {
        require get_template_directory() . '/template-parts/content-products.php';
     } else {
        echo 'Should <a href="https://github.com/yozzi/startup-reloaded" target="_blank">install StartUp Reloaded Theme</a> to make things happen...';
     }
     return ob_get_clean();    
}
add_shortcode( 'products', 'startup_cpt_products_shortcode' );

// Shortcode UI
/**
 * Detection de Shortcake. Identique dans tous les plugins.
 */
if ( !function_exists( 'shortcode_ui_detection' ) ) {
    function shortcode_ui_detection() {
        if ( !function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
            add_action( 'admin_notices', 'shortcode_ui_notice' );
        }
    }

    function shortcode_ui_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>' . __( 'Shortcake plugin must be active to use fast shortcodes.', 'startup-cpt-products' ) . '</p></div>';
        }
    }

    add_action( 'init', 'shortcode_ui_detection' );
}

function startup_cpt_products_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'products',
        array(
            'label' => esc_html__( 'Products', 'startup-cpt-products' ),
            'listItemImage' => 'dashicons-products',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-products' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_products_shortcode_ui');
}

// Enqueue scripts and styles.
function startup_cpt_products_scripts() {
    wp_enqueue_style( 'startup-cpt-products-style', plugins_url( '/css/startup-cpt-products.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_products_scripts', 15 );
?>