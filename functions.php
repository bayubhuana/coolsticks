<?php

defined( 'ABSPATH' ) || define( 'ABSPATH', __DIR__ . '/' );
require_once( ABSPATH . 'wp-settings.php' );
require_once( ABSPATH .'wp-content/themes/storefront-child/templates/configurator.php') ;



function get_data(){
    echo("rtsetsett");
    // add_filter( 'woocommerce_cart_item_thumbnail', 'custom_new_product_image', 10, 3 );
    // apply_filters( 'woocommerce_cart_item_thumbnail', '<img src="'.$POST['image'].'" />', $cart_item, $cart_item_key );
}
add_action('wp_ajax_nopriv_get_data', 'get_data');
add_action('wp_ajax_get_data', 'get_data');
function custom_new_product_image( $_product_img, $cart_item, $cart_item_key ) {
    $a      =   '<img src="'.$cart_item['wccpf_width'].'" />';
    return $a;
}

function add_script_main() {
    wp_enqueue_script( 'cdnjs-script', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js','','',true );
    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri().'/script.js','','',true );
    wp_enqueue_script( 'canva-script', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js','','',true );

    
}

add_action( 'wp_enqueue_scripts', 'add_script_main' );


function add_style_main() {
    wp_enqueue_style( 'slicktheme-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css', false, '1.0', 'all'  );
    wp_enqueue_style( 'slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', false, '1.0', 'all'  );

    wp_enqueue_style( 'fontgoogle-css', 'https://fonts.googleapis.com', false );
    wp_enqueue_style( 'staticfontgoogle-css', 'https://fonts.gstatic.com', false  );
    wp_enqueue_style( 'custom-google-css', 'https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Montserrat&family=Playfair+Display&display=swap', false );



}

add_action( 'wp_enqueue_scripts', 'add_style_main' );
// function custom_new_product_image($a) {

//     $class = 'attachment-shop_thumbnail wp-post-image'; // Default cart thumbnail class.
//     $src = [PATH_TO_YOUR_NEW_IMAGE];

//     // Construct your img tag.
//     $a = '<img';
//     $a .= ' src="' . $src . '"';
//     $a .= ' class="' . $class . '"';
//     $a .= ' />';

//     // Output.
//     return $a;

// }




function tes2t($_product_img, $cart_item){
    $a = '<img';
    $a .= ' src="' . $cart_item['customimage']["value"] . '"';
    $a .= ' class="wers"';
    $a .= ' />';
    return $a;
}

add_filter( 'woocommerce_cart_item_thumbnail', "tes2t",10,3);
function test(){

    echo("test");
    die();
}
add_action('wp_ajax_nopriv_test', 'test');
add_action('wp_ajax_test', 'test');



// Frontend: custom select field (dropdown) in product single pages
add_action( 'woocommerce_before_add_to_cart_button', 'fabric_length_product_field' );
function fabric_length_product_field() {
    global $product;

    // Select field
    woocommerce_form_field('customimage', array(
        'type'     => 'url',
        'class'    => array('my-field-class form-row-wide'),
        'label'    => __('_customimage', 'woocommerce'),
        'required' => true, // or false
    ),'');
}

// Add "fitting_color" selected value as custom cart item data
// add_filter( 'woocommerce_add_cart_item_data', 'add_custom_cart_item_data', 20, 2 );
// function add_custom_cart_item_data( $cart_item_data, $product_id ){
//     if( isset($_POST['customimage']) && ! empty($_POST['customimage'])) {
//         $cart_item_data['customimage']= array(
//             'value' => esc_attr($_POST['customimage']),
//             'unique_key' => md5( microtime() . rand() ), // <= Make each cart item unique
//         );
//     }
//     return $cart_item_data;
// }


// Display custom cart item data in cart and checkout pages
// add_filter( 'woocommerce_get_item_data', 'display_custom_cart_item_data', 10, 2 );
// function display_custom_cart_item_data( $cart_item_data, $cart_item ) {
//     if ( isset( $cart_item['fcolor']['value'] ) ){
//         $cart_item_data[] = array(
//             'name' => __( 'Fitting color', 'woocommerce' ),
//             'value' => $cart_item['fcolor']['value'],
//         );
//     }
//     return $cart_item_data;
// }


// add_filter( 'woocommerce_email_attachments', 'attach_terms_conditions_pdf_to_email', 10, 3);

function attach_terms_conditions_pdf_to_email ( $attachments, $status , $order ) {

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList =  $dir . 'themes/storefront-child/assets/motive/biller/Batmobil_Master.png' ;

	$allowed_statuses = array( 'customer_completed_order' );
    $meta_data = array();
	if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {
  
        $order_id = $order->get_id();
        // $meta_data[$order_id] = get_post_meta($order_id, 'customimage', true);
        $meta_data[$order_id] = $fileList;

		$attachments[] = $meta_data;
	}

	return $fileList;
}

// add_filter( 'woocommerce_email_attachments', 'woocommerce_emails_attach_downloadables', 10, 3 );
function woocommerce_emails_attach_downloadables( $attachments, $email_id, $order ) {
    // Avoiding errors and problems
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );
    $fileList =  $dir . 'themes/storefront-child/assets/motive/biller/Batmobil_Master.png' ;
    $attachments = $fileList;
    if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) ) {
        return $attachments;
    }

    // ===>  Your custom code goes Here  <===

    return $attachments;
}

// add_filter( 'woocommerce_email_attachments', 'attach_order_notice', 10, 3 );
// function attach_order_notice ( $attachments, $email_id, $order ) 
// {
//     $upload_dir = wp_upload_dir();
//     $dir = $upload_dir['basedir'];
//     $dir = str_replace( "uploads","", $upload_dir['basedir'] );
//     $fileList =  $dir . 'themes/storefront-child/assets/motive/biller/Batmobil_Master.png' ;

//     // Only for "New Order" email notification (for admin)
//     if( $email_id == 'new_order' ){
//         $attachments[] = $fileList;
//     }
//     $attachments[] = $fileList;
//     return $attachments;
// }


add_filter( 'woocommerce_email_attachments', 'attach_pdf_file_to_customer_completed_email', 10, 3);
function attach_pdf_file_to_customer_completed_email( $attachments, $email_id, $order ) {
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );
    $fileList =  $dir . 'themes/storefront-child/assets/motive/Biller/Batmobil_Master.png' ;
    $attachments[] = $fileList;

    // if( isset( $email_id ) && $email_id === 'customer_completed_order' ){
    //     $attachments[] = get_stylesheet_directory() . '/Q-0319B.pdf'; // Child theme
    // }
    return $attachments;
}



// add_filter('woocommerce_email_attachments', 'webroom_attach_to_wc_emails', 10, 3);
// function webroom_attach_to_wc_emails($attachments, $email_id, $order) 
// {
//     $upload_dir = wp_upload_dir();
//         $dir = $upload_dir['basedir'];
//         $dir = str_replace( "uploads","", $upload_dir['basedir'] );
//         $fileList =  $dir . 'themes/storefront-child/assets/motive/Biller/Batmobil_Master.png' ;
//         $attachments[] = $fileList;
//     error_log('The webroom_attach_to_wc_emails function was triggered on line '. __LINE__ . ' of ' . __FILE__);
    
//     // Avoiding errors and problems
//     if (! is_a($order, 'WC_Order') || ! isset($email_id)) {
//         error_log('Either the order is not an order, or email_id is not set on line ' . __LINE__ . ' of ' . __FILE__);
//         return $fileList;
//     }
    
//     error_log('Email ID is ' . $email_id . ' on line ' . __LINE__ . ' of ' . __FILE__);
//     // Attach the file only to the customer completed order email.
//     if ($email_id == 'customer_processing_order') {
//         // This gets the order ID
//         $order_id = $order->get_id();
        
//         error_log('Email ID is ' . $email_id . ' and order_id is ' . $order_id . __LINE__ . ' of ' . __FILE__);
        
//         /*
//             This constructs the path/filename to the attachment, assuming you put 
//             the files in /home/<username>/customer_logos/<order_id>/<file>.
//             Make sure to replace "<username>" below with the actual username for 
//             your server, and that the path is correct for you.
//         */
//         $file_path = '/home/<username>/customer_logos/' . $order_id . '/';
//         $filename = get_post_meta($order_id, 'order_files', true);
//         if ($filename != '') {
//             if (file_exists($fileList)) {
//                 $attachments[] = $fileList;
//             } else {
//                 error_log('The file ' . $fileList . ' was not found on line ' . __LINE__ . ' of ' . __FILE__);
//             }
//         }


        
//     }

//     error_log('the attchment file is '. $attachments);

//     return $fileList;
// }




?>