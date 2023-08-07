<?php 

// add Polyang support for custom tracking post types
if (defined('POLYLANG')) :

    // get Polylang options/settings
    $polylang_opts = get_option('polylang');

    // add single-addon tracking cpt support
    if (!in_array('single-addon', $polylang_opts['post_types'])) :
        array_push($polylang_opts['post_types'], 'single-addon');
    endif;
    
    // add cart-addon tracking cpt support
    if (!in_array('cart-addon', $polylang_opts['post_types'])) :
        array_push($polylang_opts['post_types'], 'cart-addon');
    endif;
    
    // add checkout-addon tracking cpt support
    if (!in_array('checkout-addon', $polylang_opts['post_types'])) :
        array_push($polylang_opts['post_types'], 'checkout-addon');
    endif;
    
    // add checkout-popup tracking cpt support
    if (!in_array('checkout-popup', $polylang_opts['post_types'])) :
        array_push($polylang_opts['post_types'], 'checkout-popup');
    endif;

    // update Polylang options/settings
    update_option('polylang', $polylang_opts);

endif;

?>