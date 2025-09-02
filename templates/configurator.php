<?php

putenv('LANG=en_US.UTF-8');


add_action('wp_ajax_nopriv_add_navnlapper_tocart', 'add_navnlapper_tocart');
add_action('wp_ajax_add_navnlapper_tocart', 'add_navnlapper_tocart');
function add_navnlapper_tocart() 
{
    $cart = wc()->cart;
    // $cart->add_to_cart( 53 );
    // wc()->cart->add_to_cart( 53,1 );
    // $woocommerce->cart->add_to_cart(53,1 );
    $custom_data = array( 'customimage'=> array( 
        'value' => $_POST['customimage'],
        'unique_key' => md5( microtime() . rand() ),
    ) );
    
    $cart->add_to_cart( 53 , 1, 0, array(), $custom_data );

    // print_r($_POST["customimage"]);
    // add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data ) {
    //     if( isset($_POST["customimage"]) && ! empty($_POST["customimage"])) {
    //         $cart_item_data['customimage'] = array(
    //             'value' => esc_attr($_POST['customimage']),
    //             'unique_key' => md5( microtime() . rand() ), // <= Make each cart item unique
    //         );
    //     }
    //     return $cart_item_data;
    // },20,2 );

wp_send_json_success("test");
wp_die();

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

function add_custom_cart_item_data( $cart_item_data, $product_id,$customimage ){
    if( isset($customimage) && ! empty($customimage)) {
        $cart_item_data['customimage']= array(
            'value' => esc_attr($_POST['customimage']),
            'unique_key' => md5( microtime() . rand() ), // <= Make each cart item unique
        );
    }
    return $cart_item_data;
}

add_action('wp_ajax_nopriv_upload_background', 'upload_background');
add_action('wp_ajax_upload_background', 'upload_background');
function upload_background() {
    // require_once('../../../../../wp-load.php');
    if (isset($_FILES['ct_file'] ) && !empty($_FILES['ct_file']['name']) )
            {
                $allowedExts = array("png", "jpeg");
                $temp = explode(".", $_FILES["ct_file"]["name"]);
                $extension = end($temp);
                if ( in_array($extension, $allowedExts))
                {
                    if ( ($_FILES["ct_file"]["error"] > 0) && ($_FILES['ct_file']['size'] <= 3145728 ))
                    {
                        $response = array(
                            "status" => 'error',
                            "message" => 'ERROR Return Code: '. $_FILES["ct_file"]["error"],
                            );
                    }
                    else
                    {
                        
                        $upload_dir = wp_upload_dir();
                        $dir = str_replace( "uploads","", $upload_dir['basedir'] );
                        
                        $uploadedfile = $_FILES['ct_file'];
                        $upload_name = $_FILES['ct_file']['name'];
                        $uploads = wp_upload_dir();
                        $filepath = $dir.'themes/storefront-child/assets/customeruploads/'."/$upload_name";
    
                        if ( ! function_exists( 'wp_handle_upload' ) )
                        {
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                        }
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                        $upload_overrides = array( 'test_form' => false );
                        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                        if ( $movefile && !isset( $movefile['error'] )  ) {
    
                            $file = $movefile['file'];
                            $url = $movefile['url'];
                            $type = $movefile['type'];
    
                            $attachment = array(
                                'post_mime_type' => $type ,
                                'post_title' => $upload_name,
                                'post_content' => 'File '.$upload_name,
                                'post_status' => 'inherit'
                                );
    
                            $attach_id=wp_insert_attachment( $attachment, $file, 0);
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                            wp_update_attachment_metadata( $attach_id, $attach_data );
    
                        }
    
                        $response = array(
                            "status" => 'success',
                            "url" => $url
                            );
    
                    }
                }
                else
                {
                    $response = array(
                        "status" => 'error',
                        "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
                        );
                }
            }
        //     print json_encode($response);
        // exit;
    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_nopriv_change_bcg', 'change_bcg');
add_action('wp_ajax_change_bcg', 'change_bcg');
function change_bcg() {
    $category   = $_POST["data"];
    $ismobile   =   $_POST["ismobile"];    
    $gethtml = "";
    if($category == "all"){
        $gethtml = bcg_repeater_all($ismobile);
    }
    else{
        $gethtml = bcg_repeater_filtered($category,$ismobile);
    }
   

    wp_send_json_success($gethtml);
    wp_die();
}

add_action('wp_ajax_nopriv_change_mnlapper', 'change_mnlapper');
add_action('wp_ajax_change_mnlapper', 'change_mnlapper');
function change_mnlapper() {
    $category = $_POST["data"];
    $is_palete = str_contains($_POST["data"],"pallete");
    $category = str_replace("pallete","",$category);

    if($category == "all"){
        $gethtml = bcg_repeater_with_name_all($_POST["ismobile"],$_POST["textinput"] );
    }
    if($is_palete === true){
        $gethtml = bcg_repeater_with_name_filter_and_palete($category,true, $_POST["ismobile"],$_POST["textinput"] );
    }
    if($category != "all" && $is_palete != true){
        $gethtml = bcg_repeater_with_name_filter($category,false,$_POST["ismobile"],$_POST["textinput"]);
    }
    wp_send_json_success($gethtml);
    wp_die();
}


add_action('wp_ajax_nopriv_change_mtv', 'change_mtv');
add_action('wp_ajax_change_mtv', 'change_mtv');
function change_mtv() {
    $category   =   $_POST["data"];
    $ismobile   =   $_POST["ismobile"];
    $gethtml = "";  
    if($category == "all"){
        $gethtml = motive_repeater_all($ismobile );
    }
    else{
        $gethtml = motive_repeater_filtered($category, $ismobile);
    }
    wp_send_json_success($gethtml);
    wp_die();
}

add_action('wp_ajax_nopriv_change_canvas_theme', 'change_canvas_theme');
add_action('wp_ajax_change_canvas_theme', 'change_canvas_theme');
function change_canvas_theme() {
    $category   = $_POST["data"];
    $ismobile   =   $_POST["ismobile"];    
    $text1      =   $_POST["text1"];    
    $text2      =   $_POST["text2"];    
    $gethtml = "";
    $gethtml = repeater_canvas_filtered($category,$ismobile,$text1,$text2);
   

    wp_send_json_success($gethtml);
    wp_die();
}
function factorial(){
    if(30 % 3 == 0)  {
        return "0 cuy";
    }
}
function layout_configurator($ismobile){
$html ="";
$html .= '<section class="configurator standard">
            <div class="sidenav">
                <div class="beforenav"></div>
                <div class="nav_m text_nav active">
                    <img class="icon_active " src="'.get_stylesheet_directory_uri().'/assets/icons/Text-icon.png"/>
                    <img class="icon_noactive" src="'.get_stylesheet_directory_uri().'/assets/icons/Text-white-icon.png"/>
                </div>
                <div class="nav_m motive_nav">
                    <img class="icon_active " src="'.get_stylesheet_directory_uri().'/assets/icons/Image-icon.png"/>
                    <img class="icon_noactive" src="'.get_stylesheet_directory_uri().'/assets/icons/Image-white-icon.png"/>
                </div>
                <div class="nav_m bcg_nav">
                    <img class="icon_active " src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-icon.png"/>
                    <img class="icon_noactive" src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-white-icon.png"/>
                </div>
                <div class="beforenav"></div>
            </div>
            <div class="sidepanel">
                <div class="s_1">
                    <div class="canvas">
                        <img src="'.get_stylesheet_directory_uri().'/assets/default.png"></img>
                        <div class="g_name">
                            <p class="customtext ct_1">Cool </p>
                            <p class="customtext ct_2">Stickz</p>
                            <p class="customtext ct_3"></p>
                        </div>
                    </div>
                    <div class="g_input">
                        <div class="input_div_group">
                            <p class="indikator i_1">4/17</p>
                            <input maxlength="17" class="name_1" type="text" value="Cool"></input>
                        </div>
                        <div class="input_div_group">
                            <p class="indikator i_2">6/17</p>
                            <input maxlength="17" class="name_2" type="text" value="Stickz"></input>
                        </div>
                        <div class="input_div_group">
                            <p class="indikator i_3">0/17</p>
                            <input maxlength="17" class="name_3" type="text"></input>
                        </div>
                    </div>
                </div>
                <div class="s_2 step2">';
                    
        $html .=  motive_repeater($ismobile); 
        
        // end of img motiv selectio tab
        
        
        $html .=  bcg_repeater($ismobile); 
       
        // end of background image selection tab

        

        $html .=    '<div class="fnt_selection active">
                        <div class="divtitlefnt">
                        <p class="titlecat">VÆLG SKRIFTTYPE</p>
                        </div>
                        <div class="slider_fnt">';
        $html .=  fnt_repeater(); 
        $html .=        '</div>
                    </div>';

        $html .=    '<div class="clr_selection active">
                        <div class="sec_title_select">
                            <p class="titlecat">VÆLG SKRIFTTYPE FARVE</p>
                            <div class="colorpickerdiv"><p class="labelcustomcolor">Tilføj skriftfarve :</p> <input type="color" class="textcustomcolor"></input></div>
                        </div>
                        <div class="slider_clr">';
        $html.=     clr_repeater();

        $html .=    '   </div>
                    </div>';

        $html .='
            </div>
           
        </section>
        <div class="addtocartbuttondiv">
            <div class="groupaddtocart">
                <button class="addtocart navnlapper">Læg i indkøbskurv</button>
                <input class="admin_ajax" type="hidden" value="'.get_site_url().'/wp-admin/admin-ajax.php"></input>
            </div>
        </div>
        ';
        print_r("<!-- ini dia ".$html."terakhir ini dia -->");
        echo $html;
}

function motive_repeater_option(){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir( $dir.'themes/storefront-child/assets/motive' );
    $imagePath = get_stylesheet_directory_uri()."/assets/motive/";
    $html = "";
    $html .= "";
    $counter = 0;
    $html .= ' <option value="all">Alle Motiver</option>';
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $html .= ' <option value="'.$file.'">'.$file.'</option>';
        }
    }

    return $html;
   
}
function motive_repeater($ismobile){
    $limit = 23;
    if ($ismobile === true){
        $limit = 8;
    }
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir( $dir.'themes/storefront-child/assets/motive' );
    $imagePath = get_stylesheet_directory_uri()."/assets/motive/";
    $html = "";
    
    $html .=  ' <div class="master_img_selection">
                <div class="divcat">
                <p class="titlecat">VÆLG MOTIV</p>
                <div class="div_switch">
                    
                   <div class="groupslctcategory">
                    <select name="category" id="category_motive" class="selectcategory">
                
                ';

    $html .= motive_repeater_option();  
    
    $html .=   '     </select>
                    </div>
                     <div class="group_switch">
                        Intet Motiv
                        <label class="switch">
                            <input class="applyimage" type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="img_selection">
                    <div class="containerarrow">
                        <div class="previous"></div>
                        <div class="next "></div>
                    </div>
                    <div class="slider_motive">';

    $counter = 0;
    $counterupload = 0;
    $counterms = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $counterms = $counterms + 1;
            
            // if($counterms == 1 ){
                
                $categoryfile   = scandir( $dir.'themes/storefront-child/assets/motive/'. $file );
                $imagePath      = get_stylesheet_directory_uri()."/assets/motive/". $file."/";

                foreach ( $categoryfile as $category ) {

                    $check = str_contains($category,"._");
                    if ($category != "." && $category != ".." && $check == false ) {
                        $counter = $counter + 1;
                        if($counter == 1 ){
                        
                            $html .= '<div class="startcontent"><div class="contentimotive">';
                            if($counterupload == 0){
                                $html .= '<img class="upload_trigger  slctbackground'. $counter.' "  src="https://staging.codershive.io/wp-content/themes/storefront-child/assets/icons/upload-icon.png" alt="" />';
                            }
                           
                        }
                            $html .= '<img class="'. $counter.' slctbackground"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy"/>';
                        if($counter == $limit){
                            if($counterupload == 0){
                                $limit = 24;
                                if ($ismobile === true){
                                    $limit = 9;
                                }
                                $counterupload = 1;
                            }
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        
                    }
                }
                if ($file === end($fileList)) {
                    $html .= '</div></div>';
                }
            // }
        }
      
      }

      $html .=        '</div>
                    </div>
                    <div class="div_upload_bcg">
                    <div class="group_input_bcg">
                        <label class="label_upload_bcg"> Upload din motiv </label>
                        <input type="file" class="upload_motive"></input>
                    </div>
                </div>
                </div>';
      return $html;
}

function motive_repeater_filtered($category_filter, $ismobile){
    $limit = 24;
    if($ismobile === true){
        $limit = 9;
    }
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $categoryfile   = scandir( $dir.'themes/storefront-child/assets/motive/'. $category_filter );
    $imagePath      = get_stylesheet_directory_uri()."/assets/motive/". $category_filter."/";

    foreach ( $categoryfile as $category ) {
        $check = str_contains($category,"._");
        if ($category != "." && $category != ".." && $check == false ) {
            $counter = $counter + 1;
            if($counter == 1 ){
            
                $html .= '<div class="startcontent"><div class="contentimotive">';
            }
                $html .= '<img class="'. $check . ' slctbackground"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy">';
            if($counter == $limit){
                $html .= '</div></div>';
                $counter = 0;
            }
            if ($category === end($categoryfile)) {
                $html .= '</div></div>';
            }
        }
    }
    return $html;
}

function motive_repeater_all( $ismobile ){
    $limit = 24;
    if($ismobile === true){
        $limit = 9;
    }
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir( $dir.'themes/storefront-child/assets/motive' );
    $imagePath = get_stylesheet_directory_uri()."/assets/motive/";
    $html = "";
    

    $counter = 0;
    $counterms = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $counterms = $counterms + 1;
            
            // if($counterms == 1 ){
                
                $categoryfile   = scandir( $dir.'themes/storefront-child/assets/motive/'. $file );
                $imagePath      = get_stylesheet_directory_uri()."/assets/motive/". $file."/";

                foreach ( $categoryfile as $category ) {

                    $check = str_contains($category,"._");
                    if ($category != "." && $category != ".." && $check == false ) {
                        $counter = $counter + 1;
                        if($counter == 1 ){
                        
                            $html .= '<div class="startcontent"><div class="contentimotive">';
                        }
                            $html .= '<img class="'. $counter.' slctbackground"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy">';
                        if($counter == $limit){
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        
                    }
                }
                if ($file === end($fileList)) {
                    $html .= '</div></div>';
                }
            // }
        }
      
      }
      return $html;
}
function bcg_repeater_option(){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );
    $fileList = scandir( $dir.'themes/storefront-child/assets/background');
    $imagePath = get_stylesheet_directory_uri()."/assets/background/";
    $html = "";
    $counter = 0;
    $html .= ' <option value="all">Alle Baggrunde</option>';
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $html .= ' <option value="'.$file.'">'.$file.'</option>';
        }
    }

    return $html;
   
}
function bcg_repeater($ismobile){

    
    $limit = 9;
    if ($ismobile === true){
        $limit = 8;
    }
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir(  $dir.'themes/storefront-child/assets/background' );

    $html = "";
    $html .=  ' <div class="master_bcg_selection '.$ismobile.'">
    
    <div class="divcat">
        <p class="titlecat">VÆLG BAGGRUND</p>
        <div class="colorpickerdiv">
            <p class="labelcustomcolor">Tilføj baggrundsfarve :</p> 
            <input type="color" class="customcolor"></input>
            <div class="groupslctcategory">
                <select name="category" id="category_bcg" class="selectcategory">';
                $html .= bcg_repeater_option();  
    $html .=    '</select>
            </div>
        </div>
    </div>';

    $html .=    '<div class="bcg_selection">
                        <div class ="containerarrow">
                            <div class="previous"></div>
                            <div class="next "></div>
                        </div>
                        <div class="slider_bcg">';
    $counter = 0;
    $counterms = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {

                $categoryfile   = scandir( $dir.'themes/storefront-child/assets/background/'. $file );
                if ($categoryfile != "." && $categoryfile != "..") {
                    
                
                $imagePath      = get_stylesheet_directory_uri()."/assets/background/". $file."/";
                foreach ( $categoryfile as $category ) {
                    
                    
                        if ($category != "." && $category != "..") {    
                        $counter = $counter + 1;
                        if($counter == 1 ){

               
                $html .=  '<div class="startcontent">
                            <div class="contentimotive bcg">';
                        }
                            $html .= '<div class="contslct2">
                                        <img class="'. $counter.' slctbackground2 canvasbcg"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy"/>
                                      </div>';
                        if($counter == $limit ){
                    $html .= '</div>
                            </div>';
                            $counter = 0;
                        }
                        // else{
                           
                        // }
                        }
                    }
                   
                }

            if ($file === end($fileList)) {
                $html .= '</div>';
                
            }
        }
      }
      if ($ismobile === true){
        $html .=        '';
      }
      else{
            $html .=        '</div></div>';
      }
      $html .= '
        
      </div>
      <div class="div_upload_bcg">
            <div class="group_input_bcg">
                <label class="label_upload_bcg"> Upload din baggrund </label>
                <input type="file" class="upload_bcg"></input>
            </div>
        </div>
        </div>';
      print_r("<!-- inidia ".$html."-->");
      return $html;
}
function bcg_repeater_all($ismobile ){
    $limit = 9;
    if($ismobile === true){
        $limit = 8;
    }
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir(  $dir.'themes/storefront-child/assets/background' );

    $html = "";

    $counter = 0;
    $counterms = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $counterms = $counterms + 1;
            
            if($counterms == 1 ){
                $categoryfile   = scandir( $dir.'themes/storefront-child/assets/background/'. $file );
                $imagePath      = get_stylesheet_directory_uri()."/assets/background/". $file."/";
                foreach ( $categoryfile as $category ) {
                    if ($category != "." && $category != "..") {
                        $counter = $counter + 1;
                        if($counter == 1 ){
               
                            $html .= '<div class="startcontent">
                            <div class="contentimotive bcg">';
                        }
                            $html .= '<div class="contslct2"><img class="'. $counter.' slctbackground2 canvasbcg"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy"></div>';
                        if($counter == $limit){
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        // else{
                           
                        // }
                    }
                }

            }
            if ($file === end($fileList)) {
                $html .= '</div></div>';
            }
        }
      }
      return $html;
}

function bcg_repeater_filtered($category_filter, $ismobile ){
    $limit = 9;
    if($ismobile === true){
        $limit = 9;
    }

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );


    $html = "";
    $counter = 0;
    $counterms = 0;
    
                $categoryfile   = scandir( $dir.'themes/storefront-child/assets/background/' . $category_filter );
                $imagePath      = get_stylesheet_directory_uri()."/assets/background/". $category_filter."/";

                foreach ( $categoryfile as $category ) {
                    if ($category != "." && $category != "..") {
                        $counter = $counter + 1;
                        if($counter == 1 ){
               
                            $html .= '<div class="startcontent">
                            <div class="contentimotive bcg">';
                        }
                            $html .= '<div class="contslct2"><img class="'. $counter.' slctbackground2 canvasbcg"  src="' . $imagePath . $category . '" alt="" data-fancybox="gallery" loading="lazy"></div>';
                        if($counter == $limit){
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        else{
                            if ($category === end($categoryfile)) {
                                $html .= '</div></div>';
                            }
                        }
                    }
                }

      return $html;
}
function bcg_repeater_with_name_filter_and_palete($getcategory, $is_pallete , $ismobile, $textinput ){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $limit = 9;
    $filepalette = "palette";
    $class = 'palette';
    
    if($ismobile === true){
        $limit = 4;
    }
    if($is_pallete == false){
        $filepalette = "non_palette";
        $class = '';
    }

    $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/'.$filepalette );

    $html = "";
    $counter = 0;
    $counterms = 0;
    $settingsraw = "" ;
    $settings="";
    $palleteclass = "no_palete";

    $html.= '<div class ="containerarrow">
                <div class="previous"></div>
                <div class="next "></div>
            </div>
            <div class="slider_bcg">';
    $hidedefault = "hide";

    foreach ( $categoryfile as $category ) {
        if ($category != "." && $category != ".." && $category != "._") {
            $counterms = $counterms + 1;
         
            $filename = $category;
            $counter = $counter + 1;
            $filename = substr($filename,0,10);
            $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/".$filepalette.'/'.$category;
            if($is_pallete){
                $palleteclass = "";
                $hidedefault = "";
                $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/".$filepalette.'/'.$category."/thumbnail.png";
                $textename  = $dir.'themes/storefront-child/assets/minilapper/'.$filepalette.'/'.$category."/settings.txt";
                if($counterms == 1 ){
                $settingsfile   = fopen($textename, "r") or die("Unable to open file!");
                $settings       = fread($settingsfile,filesize($textename));

                $settingsraw       = $settings ;
                $settings  = json_decode($settings , true);
                fclose($settingsfile);
                }

                $settingsfileeach   = fopen($textename, "r") or die("Unable to open file!");
                $settingseach       = fread($settingsfileeach,filesize($textename));

                $settingsraweach       = $settingseach ;
                $settingseach  = json_decode($settingseach , true);
            }
            
            


            if($counter == 1 ){
                $html .= '<div class="startcontent">
                <div class="contentimotive">';
            }
                $html .= '<div class="contslct2 '.$palleteclass.'" ><img settings='."'".$settingsraweach."'".' class="'.$class .' '. $counter.' slctbackground2 palette canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
            if($counter ==  $limit){
                $html .= '</div></div>';
                $counter = 0;
            }
            else{
                if ($category === end($categoryfile)) {
                    $html .= '</div></div>';
                }
            }


        }
    }

    $settings = $settings[0];
      $html .=        '</div>
                   </div>';
      $html2 .= "";
      $html2 .=    '
                      <div class ="cchild cd1" style="background-color:'.$settings["font_1"]["bg_color"].';">
                          <p style="color:'.$settings["font_1"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd2" style="background-color:'.$settings["font_2"]["bg_color"].';">
                          <p style="color:'.$settings["font_2"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd3" style="background-color:'.$settings["font_3"]["bg_color"].';">
                          <p style="color:'.$settings["font_3"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd4" style="background-color:'.$settings["font_4"]["bg_color"].';">
                          <p style="color:'.$settings["font_4"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd5" style="background-color:'.$settings["font_5"]["bg_color"].';">
                          <p style="color:'.$settings["font_5"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd6" style="background-color:'.$settings["font_6"]["bg_color"].';">
                          <p style="color:'.$settings["font_6"]["f_color"].';"  class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                  ';

    // $html2 .='
    // </div>';
      $htmlarray = array ($html,$html2,$hidedefault);
      return $htmlarray ;

}


function bcg_repeater_with_name_filter($getcategory, $is_pallete , $ismobile,$textinput ){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $limit = 9;
    $filepalette = "palette";
    $class = 'palette';
    
    if($ismobile === true){
        $limit = 4;
    }
    if($is_pallete == false){
        $filepalette = "non_palette";
        $class = '';
    }

    $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/'.$filepalette.'/'.$getcategory );

    $html = "";
    $counter = 0;
    $counterms = 0;
    $settingsraw = "" ;
    $settings="";
    $palleteclass = "no_palete";

    $html.= '<div class ="containerarrow">
                <div class="previous"></div>
                <div class="next "></div>
            </div>
            <div class="slider_bcg">';
    $hidedefault = "hide";

    foreach ( $categoryfile as $category ) {
        if ($category != "." && $category != ".." && $category != "._") {

         
            $filename = $category;
            $counter = $counter + 1;
            $filename = substr($filename,0,10);
            $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/".$filepalette.'/'.$getcategory."/".$category;
            if($is_pallete){
                $palleteclass = "";
                $hidedefault = "";
                $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/".$filepalette.'/'.$getcategory."/".$category."/thumbnail.png";
                $textename  = $dir.'themes/storefront-child/assets/minilapper/'.$filepalette.'/'.$getcategory."/".$category."/settings.txt";

                $settingsfile   = fopen($textename, "r") or die("Unable to open file!");
                $settings       = fread($settingsfile,filesize($textename));

                $settingsraw       = $settings ;
                $settings  = json_decode($settings , true);
                fclose($settingsfile);
            }
            
            


            if($counter == 1 ){
                $html .= '<div class="startcontent">
                <div class="contentimotive">';
            }
                $html .= '<div class="contslct2 '.$palleteclass.'" ><img settings='."'".$settingsraw."'".' class="'.$class .' '. $counter.' slctbackground2 palette canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
            if($counter ==  $limit){
                $html .= '</div></div>';
                $counter = 0;
            }
            else{
                if ($category === end($categoryfile)) {
                    $html .= '</div></div>';
                }
            }


        }
    }

    $settings = $settings[0];
      $html .=        '</div>
                   </div>';
      $html2 .= "";
      $html2 .=    '
                      <div class ="cchild cd1" style="background-color:'.$settings["font_1"]["bg_color"].';">
                          <p style="color:'.$settings["font_1"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd2" style="background-color:'.$settings["font_2"]["bg_color"].';">
                          <p style="color:'.$settings["font_2"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd3" style="background-color:'.$settings["font_3"]["bg_color"].';">
                          <p style="color:'.$settings["font_3"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd4" style="background-color:'.$settings["font_4"]["bg_color"].';">
                          <p style="color:'.$settings["font_4"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd5" style="background-color:'.$settings["font_5"]["bg_color"].';">
                          <p style="color:'.$settings["font_5"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd6" style="background-color:'.$settings["font_6"]["bg_color"].';">
                          <p style="color:'.$settings["font_6"]["f_color"].';"  class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                  ';

    // $html2 .='
    // </div>';
      $htmlarray = array ($html,$html2,$hidedefault);
      return $htmlarray ;

}

function bcg_repeater_with_name_all($ismobile, $textinput){

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/palette');
   
    $html = "";
    $html .=    '
                            <div class ="containerarrow">
                                <div class="previous"></div>
                                <div class="next "></div>
                            </div>
                            <div class="slider_bcg">';
    $counter = 0;
    $counterms = 0;

    $settings="";
    $limit = 9;
    if($ismobile === TRUE){
        $limit = 4;
    }
    // foreach pallete
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != ".." && $category != "._") {
            $counterms = $counterms + 1;
            
            
            // if($counterms == 1 ){
                $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/palette/' );
                $imagePath = get_stylesheet_directory_uri()."/assets/minilapper/palette/";
                // foreach ( $categoryfile as $category ) {
                    // if ($category != "." && $category != "..") {

                        $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/palette/".$file."/"."thumbnail.png";
                        $textename  = $dir.'themes/storefront-child/assets/minilapper/palette/'.$file."/"."settings.txt";
                        $filename = $category;
                        $counter = $counter + 1;
                        $filename = substr($filename,0,10);
                        
                        if($counterms == 1 ){
                
                     
                        $settingsfile   = fopen($textename, "r") or die("Unable to open file!". $textename);
                        $settings       = fread($settingsfile,filesize($textename));

                        $settingsraw       = $settings ;
                        $settings  = json_decode($settings , true);
                        fclose($settingsfile);

                        }

                        $settingsfileeach   = fopen($textename, "r") or die("Unable to open file!". $textename);
                        $settingseach       = fread($settingsfileeach,filesize($textename));

                        $settingsraweach       = $settingseach ;
                        $settingseach  = json_decode($settingseach , true);
                        fclose($settingsfileeach);

                        if($counter == 1 ){
                            $html .= '<div class="startcontent">
                            <div class="contentimotive">';
                        }
                            $html .= '<div class="contslct2 palete" ><img settings='."'".$settingsraweach."'".' class="'. $counter.' slctbackground2 palette canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
                        if($counter == $limit){
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        // else{
                        //     if ($category === end($categoryfile)) {
                        //         $html .= '</div></div>';
                        //     }
                        // }


                    // }
                // }
            // }
         
           
        }
      
      }
      

    // //   nonpalete
    // $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/non_palette');
    
    // // $counter = 0;
    // $counterms = 0;

    // foreach ( $fileList as $file ) {
    //     if ($file != "." && $file != "..") {
    //         $counterms = $counterms + 1;
            
           
    //         // if($counterms == 1 ){
    //             $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/non_palette/'.$file );
    //             $imagePath = get_stylesheet_directory_uri()."/assets/minilapper/non_palette/".$file."/";
    //             foreach ( $categoryfile as $category ) {
    //                 if ($category != "." && $category != "..") {

    //                     $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/non_palette/".$file."/".$category;

    //                     $filename = $category;
    //                     $counter = $counter + 1;
    //                     $filename = substr($filename,0,10);

    //                     if($counter == 1 ){
    //                         $html .= '<div class="startcontent">
    //                         <div class="contentimotive">';
    //                     }
    //                         $html .= '<div class="contslct2 no_palete" ><img  class="'. $counter.' slctbackground2 canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
    //                     if($counter == $limit){
    //                         $html .= '</div></div>';
    //                         $counter = 0;
    //                     }
    //                     // else{
                           
    //                     // }


    //                 }
    //             }
    //         // }
                
    //             if ($file === end($fileList)) {
    //                 $html .= '</div></div>';
    //             }
           
    //     }
      
    //   }



      $settings = $settings[0];
      $html .=        '</div>
                        </div>
                   </div>';
    $html2 .= "";
    $html2 .=    '    <div class ="cchild cd1" style="background-color:'.$settings["font_1"]["bg_color"].';">
                          <p style="color:'.$settings["font_1"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd2" style="background-color:'.$settings["font_2"]["bg_color"].';">
                          <p style="color:'.$settings["font_2"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd3" style="background-color:'.$settings["font_3"]["bg_color"].';">
                          <p style="color:'.$settings["font_3"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd4" style="background-color:'.$settings["font_4"]["bg_color"].';">
                          <p style="color:'.$settings["font_4"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd5" style="background-color:'.$settings["font_5"]["bg_color"].';">
                          <p style="color:'.$settings["font_5"]["f_color"].';" class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd6" style="background-color:'.$settings["font_6"]["bg_color"].';">
                          <p style="color:'.$settings["font_6"]["f_color"].';"  class="n_child">'.$textinput.'</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>';
            $htmlarray = array ($html,$html2,"");
      return $htmlarray;
}

function bcg_repeater_with_name($ismobile){

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/palette');
   
    $html = "";
    $counter = 0;
    $counterms = 0;

    $settings="";
    $limit = 9;
    if($ismobile === true){
        $limit = 4;
    }

    // foreach pallete
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != ".." && $file != "._") {
            $counterms = $counterms + 1;
            
           
            // if($counterms == 1 ){
                $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/palette/'.$file );
                $imagePath = get_stylesheet_directory_uri()."/assets/minilapper/palette/".$file."/";
                // foreach ( $categoryfile as $category ) {
                    if ($file != "." && $file != ".." && $file != "._") {
                        $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/palette/".$file."/thumbnail.png";
                        $textename  = $dir.'themes/storefront-child/assets/minilapper/palette/'.$file."/settings.txt";
                        $filename = $file;
                        $counter = $counter + 1;
                        $filename = substr($filename,0,10);
                        
                        if($counterms == 1 ){
                        $settingsfile   = fopen($textename, "r") or die("Unable to open file!");
                        $settings       = fread($settingsfile,filesize($textename));

                        $settingsraw       = $settings ;
                        $settings  = json_decode($settings , true);
                        fclose($settingsfile);
                        }

                        $settingsfileeach   = fopen($textename, "r") or die("Unable to open file!");
                        $settingseach       = fread($settingsfileeach,filesize($textename));

                        $settingsraweach       = $settingseach ;
                        $settingseach  = json_decode($settingsraweach , true);
                        fclose($settingsfileeach);

                        if($counter == 1 ){
                            $html .= '<div class="startcontent">
                            <div class="contentimotive">';
                        }
                            $html .= '<div class="contslct2 palete" ><img settings='."'".$settingsraweach."'".' class="'. $counter.' slctbackground2 palette canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
                        if($counter == $limit){
                            $html .= '</div></div>';
                            $counter = 0;
                        }
                        else{
                            if ($file === end($fileList)) {
                                $html .= '</div></div>';
                            }
                        }


                    }
                // }
            // }
         
           
        }
      
      }


    // //   nonpalete
    // $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/non_palette');
    
    // // $counter = 0;
    // $counterms = 0;

    // foreach ( $fileList as $file ) {
    //     if ($file != "." && $file != "..") {
    //         $counterms = $counterms + 1;
            
           
    //         // if($counterms == 1 ){
    //             $categoryfile = scandir( $dir.'themes/storefront-child/assets/minilapper/non_palette/'.$file );
    //             $imagePath = get_stylesheet_directory_uri()."/assets/minilapper/non_palette/".$file."/";
    //             foreach ( $categoryfile as $category ) {
    //                 if ($category != "." && $category != "..") {

    //                     $thumbnail  = get_stylesheet_directory_uri()."/assets/minilapper/non_palette/".$file."/".$category;

    //                     $filename = $category;
    //                     $counter = $counter + 1;
    //                     $filename = substr($filename,0,10);

    //                     if($counter == 1 ){
    //                         $html .= '<div class="startcontent">
    //                         <div class="contentimotive">';
    //                     }
    //                         $html .= '<div class="contslct2 no_palete" ><img settings='."'".$settingsraw."'".' class="'. $counter.' slctbackground2 canvasbcg"  src="' . $thumbnail.'" alt="" data-fancybox="gallery" loading="lazy"><p class="filename">'. $filename.'</p></div>';
    //                     if($counter == $limit){
    //                         $html .= '</div></div>';
    //                         $counter = 0;
    //                     }
    //                     // else{
                           
    //                     // }


    //                 }
    //             }
    //         // }
                
    //             if ($file === end($fileList)) {
    //                 $html .= '</div></div>';
    //             }
           
    //     }
      
    //   }



      $settings = $settings[0];
      $html .=        '</div>
                   </div>';
      $html .=    '<div class="choose_default">
                      <div class ="cchild cd1" style="background-color:'.$settings["font_1"]["bg_color"].';">
                          <p style="color:'.$settings["font_1"]["f_color"].';" class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd2" style="background-color:'.$settings["font_2"]["bg_color"].';">
                          <p style="color:'.$settings["font_2"]["f_color"].';" class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd3" style="background-color:'.$settings["font_3"]["bg_color"].';">
                          <p style="color:'.$settings["font_3"]["f_color"].';" class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd4" style="background-color:'.$settings["font_4"]["bg_color"].';">
                          <p style="color:'.$settings["font_4"]["f_color"].';" class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd5" style="background-color:'.$settings["font_5"]["bg_color"].';">
                          <p style="color:'.$settings["font_5"]["f_color"].';" class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                      <div class ="cchild cd6" style="background-color:'.$settings["font_6"]["bg_color"].';">
                          <p style="color:'.$settings["font_6"]["f_color"].';"  class="n_child">Coolstickz</p>
                          <div class="n_note">
                              30 stk
                          </div>
                      </div>
                  </div>';



      return $html;
}


function fnt_repeater(){
    $html = "   <div class='textdiv t1'>
                    <p class='al'  >AaBbCcDdEe</p>
                    <p class='nt'   >0123456789</p>
                </div>
                <div class='textdiv t2' >
                    <p class='al'>AaBbCcDdEe</p>
                    <p class='nt'>0123456789</p>
                </div>
                <div class='textdiv t3'>
                    <p class='al'>AaBbCcDdEe</p>
                    <p class='nt'>0123456789</p>
                </div>
                <div class='textdiv t4' >
                    <p class='al'>AaBbCcDdEe</p>
                    <p class='nt'>0123456789</p>
                </div>
                <div class='textdiv t5' >
                    <p class='al'>AaBbCcDdEe</p>
                    <p class='nt'>0123456789</p>
                </div>
                <div class='textdiv t6' >
                    <p class='al'>AaBbCcDdEe</p>
                    <p class='nt'>0123456789</p>
                </div>"; 

    return $html;
}


function clr_repeater(){

    $colour = array(
        '#009fe3',
       ' #f8d2d2',
        '#fef5fa',
        '#3fa535',
        '#cd1719',
        '#283583',
        '#b2dcda',
        '#f9f4b4',
        '#dbbfdd',
        '#009fe3',
        '#e6007d',
        '#ffed00',
        '#a9d6c6',
        '#aa9e00',
        '#c685a9',
        '#aaaaaa',
        '#956b6c',
        '#00705a',
        '#9b569e',
        '#cdd60d',
        '#f6a763',
        '#ffffff',
        '#000000',
        '#e4caa2'
    );

    $html = "";
    $counter = 0;    
    $counter2 = 0;    
    for($i = 0 ; $i < count($colour); $i++ ){
        $counter = $counter + 1;

        $counter2 = $counter2 + 1;
        if($counter == 1){
            $html .='<div class="option_cont">';
        }
        if($counter2 != 12 && ($i + 1) != count($colour)){
            $html .='<div class="clr_option" style="background-color:'.$colour[$i].'"></div>';
        }
        if($counter2 == 12 || ($i + 1) == count($colour)){
            $counter2 = 0;
            $html .='<div class="centerborder clr_option" style="background-color:'.$colour[$i].'"></div>';
        }
        if($counter == 24 ){
            $html .='</div>';
            $counter = 0;   
        }
        else{
            if(($i + 1) == count($colour) ){
                $html .='</div>';
            }
        }
    } 

    return $html;
}


function minilapper_repeater_option(){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );
    $html = "";
    $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/palette');
    $html .= ' <option palette="1" value="all">Alle Baggrunde</option>';
    $html .= ' <option palette="1" value="pallete">Palet</option>';   
    $counter = 0;
    // foreach ( $fileList as $file ) {
    //     if ($file != "." && $file != "..") {
    //         $html .= ' <option palette="1" value="pltistrue'.$file.'">'.$file.'</option>';
    //     }
    // }


    // withpalete
    $fileList   = scandir( $dir.'themes/storefront-child/assets/minilapper/non_palette');
    $counter = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $html .= ' <option  value="'.$file.'">'.$file.'</option>';
        }
    }
    return $html;
   
}

function layout_configurator_minilapper($ismobile){
    $html ="";
    $html .= '<section class="configurator minilapper">
                <div class="sidenav">
                    <div class="beforenav"></div>
                    <div class="nav_m bcg_nav active">
                        <img class="icon_active " src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-icon.png"/>
                        <img class="icon_noactive" src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-white-icon.png"/>
                    </div>
                    <div class="beforenav"></div>
                </div>
                <div class="sidepanel">
                    <div class="s_1">
                        <div class="canvas">
                            <div class="g_name">
                                <p class="ct_1 customtext">CoolStickz </p>
                            </div>
                        </div>
                        <div class="g_input">
                            <div class="input_div_group">
                                <p class="indikator i_1 ">10/17</p>
                                <input class="name_1  " maxlength = "17" type="text" value="Coolstickz"></input>
                            </div>
                        </div>
                    </div>
                    <div class="s_2 step2">
                        <div class="divcat">
                            <p class="titlecat">VÆLG BAGGRUND</p>';
                            // <div class="groupslctcategory">
                            // <select name="category" id="category_minilapper" class="selectcategory">';
                       
            // $html   .= minilapper_repeater_option();                

            // $html   .='      </select>
            // $html   .='     </div>
            $html   .='        </div>';
            
            $html .=    '<div class="bcg_selection">
                            <div class ="containerarrow">
                                <div class="previous"></div>
                                <div class="next "></div>
                            </div>
                            <div class="slider_bcg">';
            $html .=  bcg_repeater_with_name($ismobile); 
            
            // end of background image selection tab

    
            $html .='
                </div>
                
            </section>
            <div class="addtocartbuttondiv">
                <div class="groupaddtocart">
                    <button class="addtocart">Læg i indkøbskurv</button>
                    <input class="admin_ajax" type="hidden" value="'.get_site_url().'/wp-admin/admin-ajax.php"></input>
                </div>
            </div>';
    
            echo $html;
}

function layout_configurator_navnlapper(){
    $html ="";
    $html .= '<section class="configurator navnlapper">
                <div class="sidenav">
                    <div class="beforenav"></div>
                    <div class="nav_m bcg_nav active">
                        <img class="icon_active " src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-icon.png"/>
                        <img class="icon_noactive" src="'.get_stylesheet_directory_uri().'/assets/icons/Motive-white-icon.png"/>
                    </div>
                    <div class="beforenav"></div>
                </div>
                <div class="sidepanel">
                    <div class="s_1">
                        <div class="canvas">
                            <div class="r_1">';
    $html .=                    repeater_canvas();                          
    $html .=               '</div>
                        </div>
                    </div>
                    <div class="s_2 step2">
                        <div class="g_input">
                            <div class="input_div_group">
                                <input type="text" maxlength="17" value="Coolstickz" class="name_1"></input>
                                <p class="indikator i_1">10/17</p>
                            </div>    
                            <div class="input_div_group">    
                                <input type="text" maxlength="17" value="Navnlapper" class="name_2"></input>
                                <p class="indikator i_2">10/17</p>
                            </div>
                        </div>
                        <div class ="sec_title">
                            <p class="title"> Vaelg Tema</p>';

    $html .= repeater_canvas_thumbnail();    

    $html .='                    </div> ';

            
            // end of background image selection tab

            // $html .= repeater_tema();

            $html .='</div>
                </div>
                
            </section>
            <div class="addtocartbuttondiv">
                <div class="groupaddtocart">
                    <button class="addtocart">Læg i indkøbskurv</button>
                    <input class="admin_ajax" type="hidden" value="'.get_site_url().'/wp-admin/admin-ajax.php"></input>
                </div>
            </div>';
    
            echo $html;
}
function repeater_canvas_filtered($category,$ismobile,$text1,$text2){
    $html ="";
            $bgpath = get_stylesheet_directory_uri()."/assets/navnelapper/".$category;
           
            $html .='<div class="slide_canvas" >
                            <div class="ctxt is_1" style="background-image:url('."'".$bgpath.'/picture_1.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_2" style="background-image:url('."'".$bgpath.'/picture_2.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_3" style="background-image:url('."'".$bgpath.'/picture_3.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_4" style="background-image:url('."'".$bgpath.'/picture_4.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_5" style="background-image:url('."'".$bgpath.'/picture_5.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_6" style="background-image:url('."'".$bgpath.'/picture_6.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt is_7" style="background-image:url('."'".$bgpath.'/picture_7.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt bi_1" style="background-image:url('."'".$bgpath.'/picture_8.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt bi_2" style="background-image:url('."'".$bgpath.'/picture_9.png'."'".'">
                                <p class="ct_1 customtext">'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                            <div class="ctxt bi_3" style="background-image:url('."'".$bgpath.'/picture_10.png'."'".'">
                                <p class="ct_1  customtext"'.$text1.'</p>
                                <p class="ct_2 customtext">'.$text2.'</p>
                            </div>
                    </div>';

                    return $html ;

}
function repeater_canvas(){
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir( $dir.'themes/storefront-child/assets/navnelapper/' );
    // $imagePath = get_stylesheet_directory_uri()."/assets/navnelapper/";
    $html = "";
    $html .= '<div class ="sec_slider_tema">
                <div class="slider_canvas">        
    ';
    $counter = 1;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $category = $file;
            $bgpath = get_stylesheet_directory_uri()."/assets/navnelapper/".$category;
            if($counter == 1 ){
                $counter == $counter + 1;
            $html .='<div class="slide_canvas" >
                            <div class="ctxt is_1" style="background-image:url('."'".$bgpath.'/picture_1.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_2" style="background-image:url('."'".$bgpath.'/picture_2.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_3" style="background-image:url('."'".$bgpath.'/picture_3.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_4" style="background-image:url('."'".$bgpath.'/picture_4.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_5" style="background-image:url('."'".$bgpath.'/picture_5.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_6" style="background-image:url('."'".$bgpath.'/picture_6.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt is_7" style="background-image:url('."'".$bgpath.'/picture_7.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt bi_1" style="background-image:url('."'".$bgpath.'/picture_8.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt bi_2" style="background-image:url('."'".$bgpath.'/picture_9.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                            <div class="ctxt bi_3" style="background-image:url('."'".$bgpath.'/picture_10.png'."'".'">
                                <p class="ct_1 customtext">Coolstickz</p>
                                <p class="ct_2 customtext">navnlapper</p>
                            </div>
                    </div>';
            }

        }
        
    }
    
    $html .= '
        </div>
    </div>';
    
    return $html;
}


function repeater_canvas_thumbnail(){

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];
    $dir = str_replace( "uploads","", $upload_dir['basedir'] );

    $fileList = scandir( $dir.'themes/storefront-child/assets/navnelapper/' );

    
    // $imagePath = get_stylesheet_directory_uri()."/assets/navnelapper/";
    $html = "";
    $html .= '<div class ="sec_slider_tema_thumbnail">
                <div class="slider_thmb">        
    ';
    $counter = 0;
    foreach ( $fileList as $file ) {
        if ($file != "." && $file != "..") {
            $counter = $counter + 1;
            $category = (strlen($file) > 10) ? substr($file,0,7).'...' : $file;
            $thumbpath = get_stylesheet_directory_uri()."/assets/navnelapper/".$file."/thumbnail.png";
            if($counter == 1){
                $html .= "<div class ='c_slide_thumb'>
                            <div class ='slide_thumb'>";
            }

            $html .= "  <div class='c_slide ".$counter."'>
                            <img class='img_thumb' src='".$thumbpath."'></img>
                            <p class='title_tema'>".$category."</p>
                        </div>";
            
            if($counter == 9){
                $counter = 0;
                $html .= "</div>
                </div>";
            }
            else{
                if ($file === end($fileList)) {
                    $html .= '</div>
                    </div>';
                }
            }
           
        }
    }
    $html .= "</div>
    </div>";

    return $html;
}

?>