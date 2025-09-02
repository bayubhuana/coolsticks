jQuery(window).on("load", function() {
    
    
});

function minilapper(){
// jquerry minilapper

jQuery( ".minilapper .canvasbcg.palette" ).click(function() {
  $src = jQuery(this).attr("src");

  $setting = jQuery(this).attr("settings");
  var $setting = jQuery.parseJSON( $setting );
  jQuery(".minilapper .canvas").css("background-image","none");
  jQuery(".minilapper .canvas").css("background-color",$setting[0]["font_1"]["bg_color"]);
  jQuery(".minilapper .g_name p").css("color",$setting[0]["font_1"]["f_color"]);
  
  jQuery(".cchild.cd1").css("background-color",$setting[0]["font_1"]["bg_color"]);
  jQuery(".cchild.cd1 p").css("color",$setting[0]["font_1"]["f_color"]);

  jQuery(".cchild.cd2").css("background-color",$setting[0]["font_2"]["bg_color"]);
  jQuery(".cchild.cd2 p").css("color",$setting[0]["font_2"]["f_color"]);

  jQuery(".cchild.cd3").css("background-color",$setting[0]["font_3"]["bg_color"]);
  jQuery(".cchild.cd3 p").css("color",$setting[0]["font_3"]["f_color"]);

  jQuery(".cchild.cd4").css("background-color",$setting[0]["font_4"]["bg_color"]);
  jQuery(".cchild.cd4 p").css("color",$setting[0]["font_4"]["f_color"]);

  jQuery(".cchild.cd5").css("background-color",$setting[0]["font_5"]["bg_color"]);
  jQuery(".cchild.cd5 p").css("color",$setting[0]["font_5"]["f_color"]);

  jQuery(".cchild.cd6").css("background-color",$setting[0]["font_6"]["bg_color"]);
  jQuery(".cchild.cd6 p").css("color",$setting[0]["font_6"]["f_color"]);

  // jQuery(".minilapper .canvas").css("background-image", "url('"+$src+"')");
})

 jQuery(".cchild").click(function() {
  
  $bg = jQuery(this).css("background-color");
  $fc = jQuery(this).find(".n_child").css("color");

  jQuery(".canvas").css("background-color",$bg);
  jQuery(".canvas").css("background-image","unset");
  jQuery(".ct_1").css("color",$fc);
  
})
jQuery(".no_palete").click(function() {

  $bg_image = jQuery(this).find("img").attr("src");
  $bg_image = jQuery(this).find("img").attr("src");

  jQuery(".canvas").css("background-color","transparent");
  jQuery(".canvas").css("background-image","url('"+$bg_image+"')");
  jQuery(".ct_1").css("color","white");

  jQuery(".choose_default").addClass("hide");
})
jQuery(".palete").click(function() {
  jQuery(".choose_default").removeClass("hide");
})

}
jQuery( document ).ready(function() {
  jQuery( ".upload_trigger" ).click(function() {
    jQuery( ".upload_motive").click();
  });
  jQuery('.upload_bcg').change(function() {
    // jQuery(".standard .canvas").css("background-color", "");
    // jQuery(".standard .canvas").css("background-image", "url('https://staging.codershive.io/wp-content/themes/storefront-child/assets/icons/spiner-loading.gif')");
    
    var fileInput = jQuery('.upload_bcg').prop('files')[0];
    var formData = new FormData()
    formData.append('action', "upload_background")
    formData.append('ct_file', fileInput)
    jQuery.ajax({
          url: jQuery(".admin_ajax").val(),
          type: 'POST',
          processData: false, // important
          contentType: false, // important
          dataType: 'json',
          data: formData,
          success: function(jsonData) {
              // const data = JSON.parse(jsonData);
              console.log(jsonData["data"]["url"]);
              jQuery(".standard .canvas").css("background-color", "");
              jQuery(".standard .canvas").css("background-image", "url('"+jsonData["data"]["url"]+"')");
          },
          error: function(xhr, ajaxOptions, thrownError) {
              console.log(xhr);
          }
    });
  })

  jQuery('.upload_motive').change(function() {
    jQuery(".canvas img").attr("src", "https://staging.codershive.io/wp-content/themes/storefront-child/assets/icons/spiner-loading.gif");
    var fileInput = jQuery('.upload_motive').prop('files')[0];
    var formData = new FormData()
    formData.append('action', "upload_background")
    formData.append('ct_file', fileInput)
    jQuery.ajax({
          url: jQuery(".admin_ajax").val(),
          type: 'POST',
          processData: false, // important
          contentType: false, // important
          dataType: 'json',
          data: formData,
          success: function(jsonData) {
              // const data = JSON.parse(jsonData);
              console.log(jsonData["data"]["url"]);
              jQuery(".canvas img").attr("src", jsonData["data"]["url"]);
          },
          error: function(xhr, ajaxOptions, thrownError) {
              console.log(xhr);
          }
    });
  })

  jQuery('.applyimage').change(function() {

    if (jQuery('.applyimage').is(':checked')) {
      jQuery('.canvas img').removeClass("hide");
    }
    else{
      jQuery('.canvas img').addClass("hide");
    }
  });
 
  minilapper();
  function setfontsize(){
    $fontsize = "45px";
    $val = jQuery('.ct_3').text();

    jQuery(".customtext").removeClass("three_font");
    jQuery(".customtext").removeClass("ten_char_font");
    if( $val != "" ){
      $fontsize = "33px";
      jQuery(".customtext").addClass("three_font");
    }
    

    $val  = jQuery(".name_1").val();
    $val2 = jQuery(".name_2").val();
   

    if (jQuery(".name_3")[0]){
      $val3 = jQuery(".name_3").val();
      if($val.length > 10 || $val2.length > 10 || $val3.length > 10 ){
        jQuery(".customtext").addClass("ten_char_font");
      }
    }
    else{
      if($val.length > 10 || $val2.length > 10  ){
        jQuery(".customtext").addClass("ten_char_font");
      }
    }
  }


  jQuery('#category_motive').on('change', function() {
    $val = jQuery('#category_motive').val();
    $ismobile = false;
    if(window.matchMedia("(max-width: 767px)").matches){
      // The viewport is less than 768 pixels wide
      $ismobile = true;
    }
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: jQuery(".admin_ajax").val(),
        data:  {action: "change_mtv",data : $val, ismoblie : $ismobile },
        success: function(result){
            jQuery('.standard .slider_motive').slick('unslick');
            jQuery('.slider_motive').empty();
            jQuery('.slider_motive').html(result["data"]);
            // 
            jQuery('.slider_motive').slick({
              dots: true,
              arrows:true,
              infinite: true,
              variableWidth: true,
              prevArrow: jQuery('.img_selection .containerarrow .previous'),
              nextArrow: jQuery('.img_selection .containerarrow .next')
            });
      
            jQuery( ".slctbackground" ).click(function() {
              $src = jQuery(this).attr("src");
              jQuery(".standard .canvas img").attr("src", $src);
            });
            jQuery('.slider_motive')[0].slick.refresh()
        },
        erorr: function(msg){
            console.log(msg);
        }
       
    });
    
  });

  jQuery('#category_bcg').on('change', function() {
    $ismobile = false;
    if(window.matchMedia("(max-width: 767px)").matches){
      // The viewport is less than 768 pixels wide
      $ismobile = true;
    }
    $val = jQuery('#category_bcg').val();
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: jQuery(".admin_ajax").val(),
        data:  {action: "change_bcg",data : $val, ismobile : $ismobile },
        success: function(result){
            jQuery('.standard .slider_bcg').slick('unslick');
            jQuery('.slider_bcg').empty();
            jQuery('.slider_bcg').html(result["data"]);
            // 
            jQuery('.standard .slider_bcg').slick({
              dots: true,
              arrows:true,
              infinite: true,
              variableWidth: true,
              prevArrow: jQuery('.bcg_selection .containerarrow .previous'),
              nextArrow: jQuery('.bcg_selection .containerarrow .next')
            });
            jQuery( ".standard .canvasbcg" ).click(function() {
              $src = jQuery(this).attr("src");
              jQuery(".standard .canvas").css("background-image", "url('"+$src+"')");
            })
            jQuery('.slider_bcg')[0].slick.refresh()
        },
        erorr: function(msg){
            console.log(msg);
        }
       
    });
    
  });

  jQuery('#category_minilapper').on('change', function() {
    $ismobile = false;
    if(window.matchMedia("(max-width: 767px)").matches){
      // The viewport is less than 768 pixels wide
      $ismobile = true;
    }
    $val = jQuery('#category_minilapper').val();
    $text = jQuery('.name_1').val();
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: jQuery(".admin_ajax").val(),
        data:  {action: "change_mnlapper",data : $val , ismobile : $ismobile, textinput : $text},
        success: function(result){
          
            jQuery('.minilapper .slider_bcg').slick('unslick');
            jQuery('.bcg_selection').empty();
            jQuery('.bcg_selection').html(result["data"][0]);
            jQuery('.choose_default').html(result["data"][1]);
            jQuery('.choose_default').addClass(result["data"][2]);
            if(result["data"][2] == ""){
              console.log("test ksoosng");
              jQuery('.choose_default').removeClass("hide");
            }
            

            jQuery('.minilapper .slider_bcg').slick({
              dots: true,
              arrows:true,
              infinite: true,
              variableWidth: true,
              prevArrow: jQuery('.bcg_selection .containerarrow .previous'),
              nextArrow: jQuery('.bcg_selection .containerarrow .next')
            });
            minilapper();
            jQuery('.minilapper .slider_bcg')[0].slick.refresh()
        },
        erorr: function(msg){
            console.log(msg);
        }
       
    });
    
  });

  jQuery('.c_slide').on('click', function() {
    $val = jQuery(this).find(".title_tema").text();
    $ismobile = false;
    if(window.matchMedia("(max-width: 767px)").matches){
      // The viewport is less than 768 pixels wide
      $ismobile = true;
    }
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: jQuery(".admin_ajax").val(),
        data:  {action: "change_canvas_theme",data : $val, ismoblie : $ismobile, text1 : jQuery(".navnlapper .name_1").val(),text2 : jQuery(".navnlapper .name_2").val()},
        success: function(result){
            jQuery('.slider_canvas').slick('unslick');
            jQuery('.slider_canvas').empty();
            jQuery('.slider_canvas').html(result["data"]);
            // 
            jQuery('.slider_canvas').slick({
              dots: true,
              arrows:true,
              infinite: true,
              variableWidth: true
            });
      
            jQuery( ".slctbackground" ).click(function() {
              $src = jQuery(this).attr("src");
              jQuery(".standard .canvas img").attr("src", $src);
            });
            jQuery('.slider_canvas')[0].slick.refresh()
        },
        erorr: function(msg){
            console.log(msg);
        }
       
    });
    
  });



  // add navnlapper to cart 

  // jQuery('.addtocart.navnlapper').on('click', function() {
  //   $ismobile = false;
  //   $html = jQuery('.canvas').html();
  //   if(window.matchMedia("(max-width: 767px)").matches){
  //     // The viewport is less than 768 pixels wide
  //     $ismobile = true;
  //   }
  //   jQuery.ajax({
  //       type: "post",
  //       dataType: "json",
  //       url: jQuery(".admin_ajax").val(),
  //       data:  {action: "add_navnlapper_tocart",customimage : "https://staging.codershive.io/wp-content/themes/storefront-child/assets/default.png"},
  //       success: function(result){
  //         console.log(result);
          
  //       },
  //       erorr: function(msg){
  //           console.log(msg);
  //       }
       
  //   });
    
  // });
  jQuery( ".nav_m" ).click(function() {
    jQuery( ".nav_m" ).removeClass("active");
    jQuery(this).addClass("active");
  })
  jQuery( ".text_nav" ).click(function() {
    jQuery(".fnt_selection").addClass("active");
    jQuery(".clr_selection").addClass("active");

    jQuery(".master_bcg_selection").removeClass("active");
    jQuery(".master_img_selection").removeClass("active");
    
  })
  jQuery( ".motive_nav" ).click(function() {
    jQuery(".master_img_selection").addClass("active");

    jQuery(".master_bcg_selection").removeClass("active");
    jQuery(".fnt_selection").removeClass("active");
    jQuery(".clr_selection").removeClass("active");
    
  })
  jQuery( ".bcg_nav" ).click(function() {
    jQuery(".master_bcg_selection").addClass("active");

    jQuery(".master_img_selection").removeClass("active");
    jQuery(".fnt_selection").removeClass("active");
    jQuery(".clr_selection").removeClass("active");
    
  })
  jQuery( ".textdiv" ).click(function() {
    $font = jQuery(this).css("font-family");
    jQuery(".customtext").css("font-family", $font);
    
  });
  jQuery( ".clr_option" ).click(function() {
    $bg = jQuery(this).css("background-color");
    jQuery(".customtext").css("color", $bg);
  })
  jQuery( ".slctbackground" ).click(function() {
    $src = jQuery(this).attr("src");
    jQuery(".standard .canvas img").attr("src", $src);
  })
  jQuery( ".standard .canvasbcg" ).click(function() {
    $src = jQuery(this).attr("src");
    jQuery(".standard .canvas").css("background-color","");
    jQuery(".standard .canvas").css("background-image", "url('"+$src+"')");
  })
  
  jQuery('.name_1').bind('input', function() { 
    $val    = jQuery(this).val();
    $length = jQuery(this).val().length;
    jQuery('.ct_1').text($val);
    jQuery('.cchild p').text($val);
    
    jQuery('.i_1').text($length+"/17");
    jQuery(".i_1").removeClass("red");
    if($length == 17 ){
      jQuery('.i_1').text("Maksgrænsen på 17 tegn er nået");
      jQuery(".i_1").addClass("red");
    }

    setfontsize();
     // get the current value of the input field.
  });
  jQuery('.name_2').bind('input', function() { 
    $val = jQuery(this).val();
    $length = jQuery(this).val().length;
    jQuery('.ct_2').text($val);
    setfontsize();

    jQuery('.i_2').text($length+"/17");
    jQuery(".i_2").removeClass("red");
    if($length == 17 ){
      jQuery('.i_2').text("Maksgrænsen på 17 tegn er nået");
      jQuery(".i_2").addClass("red");
    }

     // get the current value of the input field.
  });
  jQuery('.standard .name_3').bind('input', function() { 
    $val = jQuery(this).val();
    $length = jQuery(this).val().length;
    jQuery('.ct_3').text($val);
    setfontsize();

    jQuery('.i_3').text($length+"/17");
    jQuery(".i_3").removeClass("red");
    if($length == 17 ){
      jQuery('.i_3').text("Maksgrænsen på 17 tegn er nået");
      jQuery(".i_3").addClass("red");
    }
     // get the current value of the input field.
  });
  jQuery(".customcolor").on('input',function(e){
    $color = jQuery(".customcolor").val();
    console.log($color );
    jQuery(".standard .canvas").css("background-image","");
    jQuery(".standard .canvas").css("background-color",$color);
  });
  jQuery(".textcustomcolor").on('input',function(e){
    $color = jQuery(".textcustomcolor").val();
    console.log($color );
    jQuery(".standard .customtext").css("color",$color);
  });



  


 

    jQuery('.slider_motive').slick({
        dots: true,
        arrows:true,
        infinite: true,
        variableWidth: true,
        prevArrow: jQuery('.img_selection .containerarrow .previous'),
        nextArrow: jQuery('.img_selection .containerarrow .next')
      });

      jQuery('.minilapper .slider_bcg').slick({
        dots: true,
        arrows:true,
        infinite: true,
        variableWidth: true,
        prevArrow: jQuery('.bcg_selection .containerarrow .previous'),
        nextArrow: jQuery('.bcg_selection .containerarrow .next')
      });

      jQuery('.standard .slider_bcg').slick({
        dots: true,
        arrows:true,
        infinite: true,
        variableWidth: true,
        prevArrow: jQuery('.bcg_selection .containerarrow .previous'),
        nextArrow: jQuery('.bcg_selection .containerarrow .next')
      });


      jQuery('.slider_canvas').slick({
        dots: false,
        arrows:true,
        infinite: true,
        variableWidth: true,
        prevArrow: jQuery('.bcg_selection .containerarrow .previous'),
        nextArrow: jQuery('.bcg_selection .containerarrow .next')
      });

      jQuery('.slider_thmb').slick({
        dots: true,
        arrows:true,
        infinite: true,
        variableWidth: true,
      });
    jQuery( ".slctimg" ).click(function() {
        jQuery(".custimg").attr("src",jQuery(this).attr("src"));
    });

    jQuery( ".slctbackground" ).click(function() {
        jQuery(".containercustom").css("background-image",'url("'+jQuery(this).attr("src").replace(/" "/g, "%20")+'")');
    });
    
    jQuery(".customtype").on('input', function(){ 
        jQuery(".custtext").text(jQuery(this).val());
     });

     jQuery("#btn-Preview-Image").on("click", function() {
        var element = jQuery(".containercustom"); // global variable
                var getCanvas; // global variable
                var newData;
        html2canvas(element, {
            allowTaint :false,
            useCORS: true, 
            onrendered: function(canvas) {
                getCanvas = canvas;
                var imgageData =  canvas.toDataURL("image/png");
                var a = document.createElement("a");
                a.href = imgageData; //Image Base64 Goes here
                a.download = "Image.png"; //File name Here
                a.click(); //Downloaded file
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: jQuery(".admin_ajax").val(),
                    data:  {action: "hookimage",image : imgageData },
                    success: function(msg){
                        console.log(msg);
                        console.log("test");
                    },
                    erorr: function(msg){
                        console.log(msg);
                    }
                });
    
    
            },
            logging:true
        });
        return false;
    });
 
});
function dataURLtoBlob(dataURL) {
    let array, binary, i, len;
    binary = atob(dataURL.split(',')[1]);
    array = [];
    i = 0;
    len = binary.length;
    while (i < len) {
      array.push(binary.charCodeAt(i));
      i++;
    }
    return new Blob([new Uint8Array(array)], {
      type: 'image/png'
    });
  };


  