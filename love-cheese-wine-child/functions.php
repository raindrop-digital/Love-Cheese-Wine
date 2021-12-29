<?php
/**
 * Love Cheese And Wine Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Love Cheese And Wine
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_LOVE_CHEESE_AND_WINE_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'love-cheese-and-wine-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_LOVE_CHEESE_AND_WINE_VERSION, 'all' );
	wp_register_style( 'bootstrap-theme-css', get_stylesheet_directory_uri() . '/inc/css/bootstrap-style.css', array() );
    wp_enqueue_style( 'bootstrap-theme-css' );

	// load custom js
    wp_enqueue_script('custom-accordion-js',get_stylesheet_directory_uri().'/inc/js/custom-accordion.js',array('jquery'),'1.0.0',true);

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// Greate a global Woo Extras Page
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Woo Global Extras',
		'menu_title'	=> 'Woo Global Extras',
		'menu_slug' 	=> 'woo-global-extras',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

}

// Astra Filters
add_filter( 'astra_get_option_improve-gb-editor-ui', '__return_true' );
add_filter( 'astra_schema_enabled', '__return_false' );

// Add revisions support to the Custom Layout.
function astra_add_revision_cl( $defaults ) {
	$defaults[] = 'revisions';
	return $defaults;
}
add_filter( 'astra_advanced_hooks_supports', 'astra_add_revision_cl' );

// Disable Featured image on all post types.
function your_prefix_featured_image() {
 $post_types = array('page');

 // bail early if the current post type if not the one we want to customize.
 if ( ! in_array( get_post_type(), $post_types ) ) {
 return;
 }
 
 // Disable featured image.
 add_filter( 'astra_featured_image_enabled', '__return_false' );
}

add_action( 'wp', 'your_prefix_featured_image' );

// Disable page title on product & post page
function disable_title( $return ) {
 
    if ( is_singular( array('product', 'post') ) ) {
        $return = false;
    }
 
    // Return
    return $return;
    
}
add_filter( 'ocean_display_page_header', 'disable_title' );

// Make footer sticky
add_action( 'wp_footer', 'astra_footer_align_bottom' );
function astra_footer_align_bottom () {
	?>
	<script type="text/javascript">
		document.addEventListener(
			"DOMContentLoaded",
			function() {
				fullHeight();
			},
			false
			);
		function fullHeight() {
			var headerHeight = document.querySelector("header").clientHeight;
			var footerHeight = document.querySelector("footer").clientHeight;
			var headerFooter = headerHeight + footerHeight;
			var content = document.querySelector("#content");
			content.style.minHeight = "calc( 100vh - " + headerFooter + "px )";
		}
	</script>
	<?php
}

// Woo Category Image sizes
function set_max_srcset_width( $max_width ) {
    if ( class_exists( 'WooCommerce' ) && ( is_product_category() || is_shop() ) ) {
        $max_width = 160;
    } else {
        $max_width = 260;
    }
    return $max_width;
}
add_filter( 'max_srcset_image_width', 'set_max_srcset_width' );

/* Add Google Tag Manager javascript code as close to 
the opening <head> tag as possible
=====================================================*/
function add_gtm_head(){
	?>
	 
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5KXQC28');</script>
<!-- End Google Tag Manager -->
	 
	<?php 
	}
	add_action( 'wp_head', 'add_gtm_head', 10 );
	 
	/* Add Google Tag Manager noscript codeimmediately after 
	the opening <body> tag
	========================================================*/
	function add_gtm_body(){
	?>
	 
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5KXQC28"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	 
	<?php 
	}
	add_action( 'astra_body_top', 'add_gtm_body' );


// Woocommerce overrides

/* Category Page Updates */

// Results count Wrapper
add_action('woocommerce_before_shop_loop', 'results_open_div', 7);
function results_open_div() {
    echo '
    <div class="bs-container">
        <div class="bs-row">
            <div class="bs-col-xs-12 mb-xs-2 py-1" id="shop-filters">
    ';
}

add_action('woocommerce_before_shop_loop', 'results_close_div', 33);
function results_close_div() {
    echo '</div></div></div>';
}

// Pagination Wrapper
add_action('woocommerce_after_shop_loop', 'pag_open_div', 1);
function pag_open_div() {
    echo '<div class="bs-container">
            <div class="bs-row">
                <div class="bs-col-xs-12">
    ';
}

add_action('woocommerce_after_shop_loop', 'pag_close_div', 11);
function pag_close_div() {
    echo '</div></div></div>';
}

// Make all products sold individually
add_filter( 'woocommerce_is_sold_individually', '__return_true' );

// Move product tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_output_product_data_tabs', 15 );

// Display attributes in a list on product page
function woocommerce_all_pa(){
  
    global $product;
    $attributes = $product->get_attributes();
  
    if ( ! $attributes ) {
        return;
    }
  
    $out = '<ul class="custom-attributes list-reset bs-flex bs-flex-direction-row">';
  
    foreach ( $attributes as $attribute ) {
  
  
        // skip variations
        if ( $attribute->get_variation() ) {
        continue;
        }
        $name = $attribute->get_name();
        if ( $attribute->is_taxonomy() ) {
  
            $terms = wp_get_post_terms( $product->get_id(), $name, 'all' );
            // get the taxonomy
            $tax = $terms[0]->taxonomy;
            // get the tax object
            $tax_object = get_taxonomy($tax);

            $out .= '<li class="' . esc_attr( $name ) . ' attribute-wrap p-04">';
            $out .= '<span class="attribute-value">';
            $tax_terms = array();
            foreach ( $terms as $term ) {
                $single_term = esc_html( $term->name );
                // Insert extra code here if you want to show terms as links.
                array_push( $tax_terms, $single_term );
            }
            $out .= implode(', ', $tax_terms);
            $out .= '</span></li>';
 
        } else {
            $value_string = implode( ', ', $attribute->get_options() );
            $out .= '<li class="' . sanitize_title($name) . ' ' . sanitize_title( $value_string ) . ' attribute-wrap p-04">';
            $out .= '<span class="attribute-value">' . esc_html( $value_string ) . '</span></li>';
        }
    }
  
    $out .= '</ul>';
  
    echo $out;
}
add_action('woocommerce_before_add_to_cart_form', 'woocommerce_all_pa', 5 );

// Remove additional information tab
 add_filter( 'woocommerce_product_tabs', 'remove_additional_information_tab', 100, 1 );
 function remove_additional_information_tab( $tabs ) {
	 unset($tabs['additional_information']);
 
	 return $tabs;
 }

 
// Add In The Box Tab
add_filter( 'woocommerce_product_tabs', 'new_the_box_tab' );
function new_the_box_tab( $tabs ) {
	
	// Adds the new tab
	
	$tabs['the_box'] = array(
		'title' 	=> __( 'The Box', 'woocommerce' ),
		'priority' 	=> 50,
		'callback' 	=> 'the_box_content'
	);

	return $tabs;

}
function the_box_content() {
    
    if( get_field('in_the_box_information') ): ?>
    <div><?php wpautop(the_field('in_the_box_information')); ?></div>
    <?php endif;
	
}

// Add In The Box Tab
add_filter( 'woocommerce_product_tabs', 'new_delivery_tab' );
function new_delivery_tab( $tabs ) {
	
	// Adds the new tab
	
	$tabs['delivery'] = array(
		'title' 	=> __( 'Delivery', 'woocommerce' ),
		'priority' 	=> 50,
		'callback' 	=> 'delivery_content'
	);

	return $tabs;

}
function delivery_content() {
    
    if( get_field('delivery_information') ): ?>
    <div><?php wpautop(the_field('delivery_information')); ?></div>
    <?php endif;
	
}

// Hook In USPs onto Single Product 
function add_usps_module() { 
    wc_get_template( 'woocommerce/single-product/product-usps.php' );

}
add_action( 'woocommerce_after_single_product_summary', 'add_usps_module', 4 );	

// Hook in subscriber perks
function add_perks_module() { 
    wc_get_template( 'woocommerce/single-product/product-perks.php' );

}
add_action( 'woocommerce_after_single_product_summary', 'add_perks_module', 5 );

// Hook In Guide Box onto Single Product 
function add_box_guide_module() { 
    wc_get_template( 'woocommerce/single-product/product-guide-box.php' );

}
add_action( 'woocommerce_after_single_product_summary', 'add_box_guide_module', 6 );

// Hook In Guide Box onto Single Product 
function add_product_cta_module() { 
    wc_get_template( 'woocommerce/single-product/product-cta.php' );

}
add_action( 'woocommerce_after_single_product', 'add_product_cta_module', 10 );

// Checkout Fields for billing email and phone

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    $fields['shipping']['shipping_email'] = array(
    'label'     => __('Email Address', 'woocommerce'),
    'placeholder'   => _x('Enter your email to receive delivery updates', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );
     
     $fields['shipping']['shipping_phone'] = array(
    'label'     => __('Phone', 'woocommerce'),
    'placeholder'   => _x('Enter your phone to recieve delivery updates', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );
    return $fields;
}

/* Custom Field Shipping phone */

// Editable field on admin order edit pages inside edit shipping section
add_filter( 'woocommerce_admin_shipping_fields' , 'add_order_admin_edit_shipping_phone' );
function add_order_admin_edit_shipping_phone( $fields ) {
    // Include shipping phone as editable field
    $fields['phone'] = array( 'label' => __("Shipping phone"), 'show' => '0' );

    return $fields;
}

// Adding custom placeholder to woocommerce formatted address only on Backend
add_filter( 'woocommerce_localisation_address_formats', 'admin_localisation_address_formats', 49, 1 );
function admin_localisation_address_formats( $address_formats ){
    // Only in backend (Admin)
    if( is_admin() || ! is_wc_endpoint_url() ) {
        foreach( $address_formats as $country_code => $address_format ) {
            $address_formats[$country_code] .= "\n{phone}";
        }
    }
    return $address_formats;
}

// Custom placeholder replacement to woocommerce formatted address
add_filter( 'woocommerce_formatted_address_replacements', 'custom_formatted_address_replacements', 9, 2 );
function custom_formatted_address_replacements( $replacements, $args  ) {
    $replacements['{phone}'] = ! empty($args['phone']) ? $args['phone'] : '';

    return $replacements;
}

// Add the shipping phone value to be displayed on email notifications under shipping address
add_filter( 'woocommerce_order_formatted_shipping_address', 'add_shipping_phone_to_formatted_shipping_address', 99, 2 );
function add_shipping_phone_to_formatted_shipping_address( $shipping_address, $order ) {
    global $pagenow, $post_type;

    // Not on admin order edit pages (as it's already displayed).
    if( ! ( $pagenow === 'post.php' && $post_type === 'shop_order' && isset($_GET['action']) && $_GET['action'] === 'edit' ) ) {
        // Include shipping phone on formatted shipping address
        $shipping_address['phone'] = $order->get_meta('_shipping_phone');
    }
    return $shipping_address;
}

// Remove double billing phone from email notifications (and admin) under billing address
add_filter( 'woocommerce_order_formatted_billing_address', 'remove_billing_phone_from_formatted_billing_address', 99, 2 );
function remove_billing_phone_from_formatted_billing_address( $billing_address, $order ) {
    unset($billing_address['phone']);
  
    return $billing_address;
}

/* Custom Field Shipping email */

// Editable field on admin order edit pages inside edit shipping section
add_filter( 'woocommerce_admin_shipping_fields' , 'add_order_admin_edit_shipping_email' );
function add_order_admin_edit_shipping_email( $fields ) {
    // Include shipping phone as editable field
    $fields['email'] = array( 'label' => __("Shipping Email"), 'show' => '0' );

    return $fields;
}

// Adding custom placeholder to woocommerce formatted address only on Backend
add_filter( 'woocommerce_localisation_address_formats', 'admin_localisation_address_formats_email', 50, 1 );
function admin_localisation_address_formats_email( $address_formats ){
    // Only in backend (Admin)
    if( is_admin() || ! is_wc_endpoint_url() ) {
        foreach( $address_formats as $country_code => $address_format ) {
            $address_formats[$country_code] .= "\n{email}";
        }
    }
    return $address_formats;
}

// Custom placeholder replacement to woocommerce formatted address
add_filter( 'woocommerce_formatted_address_replacements', 'custom_formatted_address_replacements_email', 10, 2 );
function custom_formatted_address_replacements_email( $replacements, $args  ) {
    $replacements['{email}'] = ! empty($args['email']) ? $args['email'] : '';

    return $replacements;
}

// Add the shipping phone value to be displayed on email notifications under shipping address
add_filter( 'woocommerce_order_formatted_shipping_address', 'add_shipping_email_to_formatted_shipping_address', 100, 2 );
function add_shipping_email_to_formatted_shipping_address( $shipping_address, $order ) {
    global $pagenow, $post_type;

    // Not on admin order edit pages (as it's already displayed).
    if( ! ( $pagenow === 'post.php' && $post_type === 'shop_order' && isset($_GET['action']) && $_GET['action'] === 'edit' ) ) {
        // Include shipping phone on formatted shipping address
        $shipping_address['email'] = $order->get_meta('_shipping_email');
    }
    return $shipping_address;
}

// Remove double billing phone from email notifications (and admin) under billing address
add_filter( 'woocommerce_order_formatted_billing_address', 'remove_billing_email_from_formatted_billing_address', 100, 2 );
function remove_billing_email_from_formatted_billing_address( $billing_address, $order ) {
    unset($billing_address['email']);
  
    return $billing_address;
}


/* Merge Shipping names in CSV Format */
/**
 * Step 1. Add `example` column header and remove the `billing_company` column
 *
 * @param array $column_headers the original column headers
 * @param \CSV_Export_Generator $csv_generator the generator instance
 * @return array the updated column headers
 */
function sv_wc_csv_export_modify_column_headers_example( $column_headers, $csv_generator ) {

	// add the new `example` column header
	$column_headers['billing_name'] = 'Billing name';
	$column_headers['shipping_name'] = 'Delivery Contact First Name';

	return $column_headers;
}
add_filter( 'wc_customer_order_export_csv_order_headers', 'sv_wc_csv_export_modify_column_headers_example', 10, 2 );


/**
* Step 2. Add `example` column data
*
* @param array $order_data the original column data
* @param \WC_Order $order the order object
* @param \CSV_Export_Generator $csv_generator the generator instance
* @return array the updated column data
*/
function sv_wc_csv_export_modify_row_data_example( $order_data, $order, $csv_generator ) {

	// Example showing how to extract order metadata into it's own column
	$meta_key_example = is_callable( array( $order, 'get_meta' ) ) ? $order->get_meta( 'meta_key_example' ) : $order->meta_key_example;

// Custom Fields for CSV Export
$custom_data = array(
// combine first name and last name to 1 field
	'billing_name' => get_post_meta( $order->id, '_billing_first_name', true )." ".get_post_meta( $order->id, '_billing_last_name', true ),
'shipping_name' => get_post_meta( $order->id, '_shipping_first_name', true )." ".get_post_meta( $order->id, '_shipping_last_name', true ),
);


	return sv_wc_csv_export_add_custom_order_data( $order_data, $custom_data, $csv_generator );
}
add_filter( 'wc_customer_order_export_csv_order_row', 'sv_wc_csv_export_modify_row_data_example', 10, 3 );


if ( ! function_exists( 'sv_wc_csv_export_add_custom_order_data' ) ) :

/**
* Helper function to add custom order data to CSV Export order data
*
* @param array $order_data the original column data that may be in One Row per Item format
* @param array $custom_data the custom column data being merged into the column data
* @param \CSV_Export_Generator $csv_generator the generator instance
* @return array the updated column data
*/
function sv_wc_csv_export_add_custom_order_data( $order_data, $custom_data, $csv_generator ) {

	$new_order_data   = array();

	if ( sv_wc_csv_export_is_one_row( $csv_generator ) ) {

			foreach ( $order_data as $data ) {
					$new_order_data[] = array_merge( (array) $data, $custom_data );
			}

	} else {
			$new_order_data = array_merge( $order_data, $custom_data );
	}

	return $new_order_data;
}

endif;


if ( ! function_exists( 'sv_wc_csv_export_is_one_row' ) ) :

/**
* Helper function to check the export format
*
* @param \CSV_Export_Generator $csv_generator the generator instance
* @return bool - true if this is a one row per item format
*/
function sv_wc_csv_export_is_one_row( $csv_generator ) {

	$one_row_per_item = false;

	if ( version_compare( wc_customer_order_csv_export()->get_version(), '4.0.0', '<' ) ) {

			// pre 4.0 compatibility
			$one_row_per_item = ( 'default_one_row_per_item' === $csv_generator->order_format || 'legacy_one_row_per_item' === $csv_generator->order_format );

	} elseif ( isset( $csv_generator->format_definition ) ) {

			// post 4.0 (requires 4.0.3+)
			$one_row_per_item = 'item' === $csv_generator->format_definition['row_type'];
	}

	return $one_row_per_item;
}

endif;

/**
* Re-order Order CSV Export columns
* Example: Remove billing_first_name and add in full billing name
* unset the column, then reset it in the desired location
*
* @param array $column_headers the original headers of the order export
* @return array the updated column headers
*/
function export_reorder_columns_billing_name( $column_headers ) {

// remove order total from the original set of column headers, otherwise it will be duplicated
unset( $column_headers['billing_name'] );

$new_column_headers = array();

foreach ( $column_headers as $column_key => $column_name ) {

	$new_column_headers[ $column_key ] = $column_name;

	if ( 'shipping_method' == $column_key ) {

		// add Billing Name immediately after billing first name
		$new_column_headers['billing_name'] = 'Billing Name';
	}
}

return $new_column_headers;
}
add_filter( 'wc_customer_order_export_csv_order_headers', 'export_reorder_columns_billing_name' );

/**
* Re-order Order CSV Export columns
* Example: Remove billing_first_name and add in full billing name
* unset the column, then reset it in the desired location
*
* @param array $column_headers the original headers of the order export
* @return array the updated column headers
*/
function export_reorder_columns_shipping_name( $column_headers ) {

// remove order total from the original set of column headers, otherwise it will be duplicated
unset( $column_headers['shipping_name'] );

$new_column_headers = array();

foreach ( $column_headers as $column_key => $column_name ) {

	$new_column_headers[ $column_key ] = $column_name;

	if ( 'billing_country' == $column_key ) {

		// add Billing Name immediately after billing first name
		$new_column_headers['shipping_name'] = 'Delivery Contact First Name';
	}
}

return $new_column_headers;
}

add_filter( 'wc_customer_order_export_csv_order_headers', 'export_reorder_columns_shipping_name' );

// Remove variation pricing 
add_filter( 'woocommerce_variable_price_html', 'variation_price_format_min', 9999, 2 );
  
       function variation_price_format_min( $price, $product ) {
          $prices = $product->get_variation_prices( true );
          $min_price = current( $prices['price'] );
          $price = sprintf( __( 'From: %1$s', 'woocommerce' ), wc_price( $min_price ) );
          return $price;
       }

// Change Related products Text
add_filter('woocommerce_product_related_products_heading',function(){

    return 'You may also like';
 
 });

 // Change upsell text
 add_filter('woocommerce_product_upsells_products_heading',function(){

    return 'Our experts recommend';
 
 });

// change cross sell text
 add_filter('woocommerce_product_cross_sells_products_heading',function(){

    return 'Have you forgotten anything?';
 
 });

// Cross Sell Position in Cart
// Remove Cross Sells From Default Position 
if ( ! function_exists ( 'woocommerce_cross_sell_display' ) ) {
    function remove_cross_sell() {
        remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    }
}

add_action( 'wp_loaded' , 'remove_cross_sell' );

// ---------------------------------------------
// Add them back UNDER the Cart Table
 
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 5 );

// ---------------------------------------------
// Display Cross Sells on 3 columns instead of default 4
 
add_filter( 'woocommerce_cross_sells_columns', 'bbloomer_change_cross_sells_columns' );
 
function bbloomer_change_cross_sells_columns( $columns ) {
return 4;
}
 