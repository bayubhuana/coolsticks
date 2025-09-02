<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php 
do_action( 'woocommerce_after_single_product' );
// testcanva();
// step1();
// testcanva();


layout_configurator();
// layout_configurator_minilapper();
// layout_configurator_navnlapper();
?>




<?php


function step1(){


    $html='';

    $html .= '<div class="containercustom">
                <img src="#" class="custimg"></img>
                <p class="custtext">Test</p>
              </div>';
              echo($html);
    $imagePath = get_stylesheet_directory_uri()."/assets/motive/";

    $fileList = scandir( 'F:\xampp\htdocs\stiker\wp-content\themes\storefront-child\assets\motive' );
    print_r($fileList);
    echo("<div style='display:flex; flex-direction:row; flex-wrap:wrap;'>");
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            echo '<img class="slctimg" style="width:100px; height:100px; object-fit:contain;" src="' . $imagePath . $file . '" alt="" data-fancybox="gallery" loading="lazy">';
        }
      
      }
    echo("</div>");

    $fileList = scandir( 'F:\xampp\htdocs\stiker\wp-content\themes\storefront-child\assets\background' );
    $imagePath = get_stylesheet_directory_uri()."/assets/background/";

    echo("<div style='display:flex; flex-direction:row; flex-wrap:wrap;'>");
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            echo '<img class="slctbackground" style="width:100px; height:100px; object-fit:contain;" src="' . $imagePath . $file . '" alt="" data-fancybox="gallery" loading="lazy">';
        }
      
      }
    echo("</div>"); 

    $html = "";

    $html .= "<div class='inputtext'>
                <input class ='customtype' type='text'></input>
                <button id='btn-Preview-Image'></button>
    </div>";
      echo($html);
          // if ($handle = opendir(get_stylesheet_directory_uri()."/assets/motive")) {

    //     while (false !== ($entry = readdir($handle))) {
    
    //         if ($entry != "." && $entry != "..") {
    
    //             echo "$entry\n";
    //         }
    //     }
    
    //     closedir($handle);
    // }
}

function testcanva(){
    $html ='

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  
    
    <body>
        <div id="html-content-holder" style="background-color: #F0F0F1; color: #00cc65; width: 500px;
            padding-left: 25px; padding-top: 10px;">
            <div>
                 <img  width="300" height="300" crossorigin style="height:200px; width:200px; background-size:contain;" src='."'https://static.wixstatic.com/media/e7fd3d_90de245f05864cc4961278a3a522949f~mv2.png/v1/fill/w_144,h_168,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/illustration1b.png'".'></img>
                 <p>Test image</p>
            </div>
        </div>
        <input download="download.png" id="btn-Preview-Image" type="button" value="Download" />
        <br/>
    
    
        <script>
            $(document).ready(function() {
    
    
                var element = $("#html-content-holder"); // global variable
                var getCanvas; // global variable
                var newData;
    
                $("#btn-Preview-Image").on("click", function() {
                    html2canvas(element, {
                        allowTaint :false,
                        useCORS: true, 
                        onrendered: function(canvas) {
                            getCanvas = canvas;
                            var imgageData = getCanvas.toDataURL("image/png");
                            var a = document.createElement("a");
                            a.href = imgageData; //Image Base64 Goes here
                            a.download = "Image.png"; //File name Here
                            a.click(); //Downloaded file

                            $.ajax({
                                type: "post",
                                dataType: "json",
                                url: "'.admin_url('admin-ajax.php').'",
                                data:  {action: "get_data",image : imgageData},
                                success: function(msg){
                                    console.log(msg);
                                }
                            });


                        },
                        logging:true
                    });
                });
    
    
            });
        </script>
    </body>
    
    ';

    echo($html);
}
?>


