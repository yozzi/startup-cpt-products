<?php
/*
Plugin Name: StartUp Products
Description: Le plugin pour activer le Custom Post Products
Author: Yann Caplain
Version: 0.1.0
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
		'name'                => _x( 'Products', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Products', 'text_domain' ),
		'name_admin_bar'      => __( 'Products', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'products', 'text_domain' ),
		'description'         => __( 'Post Type Description', 'text_domain' ),
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
		'name'                       => _x( 'Product Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Product Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Product Types', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' )
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
		'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Product Categories', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' )
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
		'title'         => __( 'Product details', 'cmb2' ),
		'object_types'  => array( 'products' )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Main picture', 'cmb2' ),
		'desc' => __( 'Main image of the product, may be different from the thumbnail. i.e. 3D model', 'cmb2' ),
		'id'   => $prefix . 'main_pic',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Thumbnail', 'cmb2' ),
		'desc' => __( 'The product picture on your website listings, if different from Main picture.', 'cmb2' ),
		'id'   => $prefix . 'thumbnail',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );

	$cmb_box->add_field( array(
		'name'       => __( 'Short description', 'cmb2' ),
		'desc'       => __( 'i.e. "New business building in Montreal"', 'cmb2' ),
		'id'         => $prefix . 'short',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Type', 'cmb2' ),
		'desc'     => __( 'Select the type(s) of the product', 'cmb2' ),
		'id'       => $prefix . 'type',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'product-type', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'     => __( 'Categoy', 'cmb2' ),
		'desc'     => __( 'Select the category(ies) of the product', 'cmb2' ),
		'id'       => $prefix . 'category',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'product-category', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Status', 'cmb2' ),
		'desc'             => __( 'The product\'s current status', 'cmb2' ),
		'id'               => $prefix . 'status',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Available' => __( 'Available', 'cmb2' ),
			'Sold out soon'   => __( 'Sold out soon', 'cmb2' ),
			'Sold out'     => __( 'Sold out', 'cmb2' ),
            'Back Order'     => __( 'Back Order', 'cmb2' ),
            'Sale closed'     => __( 'Sale closed', 'cmb2' ),
            'Unavailable'     => __( 'Unavailable', 'cmb2' )
		),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Description', 'cmb2' ),
		'desc' => __( 'Full, main description', 'cmb2' ),
		'id'   => $prefix . 'description',
		'type' => 'textarea'
	) );

	$cmb_box->add_field( array(
		'name' => __( 'Price', 'cmb2' ),
		'desc' => __( 'The product price in Canadian Dollar', 'cmb2' ),
		'id'   => $prefix . 'price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Special Price', 'cmb2' ),
		'desc' => __( 'The product special price in Canadian Dollar', 'cmb2' ),
		'id'   => $prefix . 'special_price',
		'type' => 'text_money'
	) );
    
    $cmb_box->add_field( array(
		'name'         => __( 'Gallery', 'cmb2' ),
		'desc'         => __( 'Upload or add multiple images for product photo gallery.', 'cmb2' ),
		'id'           => $prefix . 'gallery',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ) // Default: array( 50, 50 )
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'cmb2' ),
		'desc' => __( 'Link to te product on an extrenal website (i.e. real estate agency)', 'cmb2' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );
}

add_action( 'cmb2_init', 'startup_reloaded_products_meta' );
?>