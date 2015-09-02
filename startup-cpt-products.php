<?php
/*
Plugin Name: StartUp Products
Description: Le plugin pour activer le Custom Post Products
Author: Yann Caplain
Version: 0.1.0
Text Domain: startup-reloaded
*/

//GitHub Plugin Updater
function startup_reloaded_products_updater() {
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

add_action( 'init', 'startup_reloaded_products_updater' );

//CPT
function startup_reloaded_products() {
	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'startup-reloaded' ),
		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'startup-reloaded' ),
		'menu_name'           => __( 'Products', 'startup-reloaded' ),
		'name_admin_bar'      => __( 'Products', 'startup-reloaded' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-reloaded' ),
		'all_items'           => __( 'All Items', 'startup-reloaded' ),
		'add_new_item'        => __( 'Add New Item', 'startup-reloaded' ),
		'add_new'             => __( 'Add New', 'startup-reloaded' ),
		'new_item'            => __( 'New Item', 'startup-reloaded' ),
		'edit_item'           => __( 'Edit Item', 'startup-reloaded' ),
		'update_item'         => __( 'Update Item', 'startup-reloaded' ),
		'view_item'           => __( 'View Item', 'startup-reloaded' ),
		'search_items'        => __( 'Search Item', 'startup-reloaded' ),
		'not_found'           => __( 'Not found', 'startup-reloaded' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-reloaded' )
	);
	$args = array(
		'label'               => __( 'products', 'startup-reloaded' ),
		'description'         => __( 'Post Type Description', 'startup-reloaded' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions', ),
		//'taxonomies'          => array( 'product_types' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-cart',
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

add_action( 'init', 'startup_reloaded_products', 0 );

// Capabilities
function startup_reloaded_products_caps() {
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

register_activation_hook( __FILE__, 'startup_reloaded_products_caps' );

// Product types taxonomy
function startup_reloaded_product_types() {
	$labels = array(
		'name'                       => _x( 'Product Types', 'Taxonomy General Name', 'startup-reloaded' ),
		'singular_name'              => _x( 'Product Type', 'Taxonomy Singular Name', 'startup-reloaded' ),
		'menu_name'                  => __( 'Product Types', 'startup-reloaded' ),
		'all_items'                  => __( 'All Items', 'startup-reloaded' ),
		'parent_item'                => __( 'Parent Item', 'startup-reloaded' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-reloaded' ),
		'new_item_name'              => __( 'New Item Name', 'startup-reloaded' ),
		'add_new_item'               => __( 'Add New Item', 'startup-reloaded' ),
		'edit_item'                  => __( 'Edit Item', 'startup-reloaded' ),
		'update_item'                => __( 'Update Item', 'startup-reloaded' ),
		'view_item'                  => __( 'View Item', 'startup-reloaded' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-reloaded' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-reloaded' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-reloaded' ),
		'popular_items'              => __( 'Popular Items', 'startup-reloaded' ),
		'search_items'               => __( 'Search Items', 'startup-reloaded' ),
		'not_found'                  => __( 'Not Found', 'startup-reloaded' )
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
	register_taxonomy( 'product-type', array( 'products' ), $args );

}

add_action( 'init', 'startup_reloaded_product_types', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_reloaded_product_types_metabox_remove() {
	remove_meta_box( 'tagsdiv-product-type', 'products', 'side' );
    // tagsdiv-product_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_reloaded_product_types_metabox_remove' );

// Product categories taxonomy
function startup_reloaded_product_categories() {
	$labels = array(
		'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'startup-reloaded' ),
		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'startup-reloaded' ),
		'menu_name'                  => __( 'Product Categories', 'startup-reloaded' ),
		'all_items'                  => __( 'All Items', 'startup-reloaded' ),
		'parent_item'                => __( 'Parent Item', 'startup-reloaded' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-reloaded' ),
		'new_item_name'              => __( 'New Item Name', 'startup-reloaded' ),
		'add_new_item'               => __( 'Add New Item', 'startup-reloaded' ),
		'edit_item'                  => __( 'Edit Item', 'startup-reloaded' ),
		'update_item'                => __( 'Update Item', 'startup-reloaded' ),
		'view_item'                  => __( 'View Item', 'startup-reloaded' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-reloaded' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-reloaded' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-reloaded' ),
		'popular_items'              => __( 'Popular Items', 'startup-reloaded' ),
		'search_items'               => __( 'Search Items', 'startup-reloaded' ),
		'not_found'                  => __( 'Not Found', 'startup-reloaded' )
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
function startup_reloaded_products_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_products_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Product details', 'startup-reloaded' ),
		'object_types'  => array( 'products' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'startup-reloaded' ),
		'desc' => __( 'Main image of the product, may be different from the thumbnail. i.e. 3D model', 'startup-reloaded' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Thumbnail', 'startup-reloaded' ),
		'desc' => __( 'The product picture on your website listings, if different from Main picture.', 'startup-reloaded' ),
		'id'   => $prefix . 'thumbnail',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'       => __( 'Short description', 'startup-reloaded' ),
		'desc'       => __( 'i.e. "New business building in Montreal"', 'startup-reloaded' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Type', 'startup-reloaded' ),
		'desc'     => __( 'Select the type(s) of the product', 'startup-reloaded' ),
		'id'       => $prefix . 'type',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'product-type', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Categoy', 'startup-reloaded' ),
		'desc'     => __( 'Select the category(ies) of the product', 'startup-reloaded' ),
		'id'       => $prefix . 'category',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'product-category', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Status', 'startup-reloaded' ),
		'desc'             => __( 'The product\'s current status', 'startup-reloaded' ),
		'id'               => $prefix . 'status',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Available' => __( 'Available', 'startup-reloaded' ),
			'Sold out soon'   => __( 'Sold out soon', 'startup-reloaded' ),
			'Sold out'     => __( 'Sold out', 'startup-reloaded' ),
            'Back order'     => __( 'Back order', 'startup-reloaded' ),
            'Sale closed'     => __( 'Sale closed', 'startup-reloaded' ),
            'Unavailable'     => __( 'Unavailable', 'startup-reloaded' )
		),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'startup-reloaded' ),
		'desc' => __( 'Full, main description', 'startup-reloaded' ),
		'id'   => $prefix . 'description',
		'type' => 'textarea'
	) );

	$cmb_box->add_field( array(
		'name' => __( 'Price', 'startup-reloaded' ),
		'desc' => __( 'The product price in Canadian Dollar', 'startup-reloaded' ),
		'id'   => $prefix . 'price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Special Price', 'startup-reloaded' ),
		'desc' => __( 'The product special price in Canadian Dollar', 'startup-reloaded' ),
		'id'   => $prefix . 'special_price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name'         => __( 'Gallery', 'startup-reloaded' ),
		'desc'         => __( 'Upload or add multiple images for product photo gallery.', 'startup-reloaded' ),
		'id'           => $prefix . 'gallery',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'startup-reloaded' ),
		'desc' => __( 'Link to te product on an extrenal website (i.e. real estate agency)', 'startup-reloaded' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );
}

add_action( 'cmb2_init', 'startup_reloaded_products_meta' );
?>