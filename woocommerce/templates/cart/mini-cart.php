<?php




add_filters( 'woocommerce_cart_item_thumbnail', 'test');


function test(){
    $a = '<img';
    $a .= ' src="' . $src . '"';
    $a .= ' class="' . $class . '"';
    $a .= ' />';
    return $a;
}
?>