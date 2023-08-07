<?php

/**
 * Settings page for Upsell v2
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 2.0.2
 * @package sbwc-upsell-v2
 * @subpackage product-addon
 * @subpackage checkout-addon
 */
// add submenu item to woocommerce menu 
add_action('admin_menu', 'add_upsell_v2_settings_page');

function add_upsell_v2_settings_page()
{
    add_submenu_page('woocommerce', __('Upsell V2 Settings', 'woocommerce'), __('Upsell V2 Settings', 'woocommerce'), 'manage_options', 'upsell-v2-settings', 'upsell_v2_settings', 99);
}

// render submenu html etc
function upsell_v2_settings()
{
    // **********
    // save data
    // **********
    if (isset($_POST['upsell-v2-upsells-submit'])) :

        // ****************
        // checkout addons
        // ****************
        $checkout_ao_ids   = isset($_POST['upsell-v2-checkout-addons']) ? $_POST['upsell-v2-checkout-addons'] : '';
        $checkout_ao_langs = isset($_POST['upsell-v2-checkout-addons-langs']) ? $_POST['upsell-v2-checkout-addons-langs'] : '';

        // build language => product ids array
        $ch_ao_language_ids = [];

        foreach ($checkout_ao_langs as $key => $lang) :
            $ch_ao_language_ids[$lang] = $checkout_ao_ids[$key];
        endforeach;

        // save language => product ids array if not empty
        if (!empty(array_filter($ch_ao_language_ids))) :
            update_option('upsell_v2_checkout_addons', maybe_serialize($ch_ao_language_ids));
        endif;

        // ****************
        // checkout popups
        // ****************
        $checkout_pu_visibility = isset($_POST['upsell-v2-checkout-pop-up-visible']) ? $_POST['upsell-v2-checkout-pop-up-visible'] : '';
        $checkout_pu_ids        = isset($_POST['upsell-v2-checkout-popup-addons']) ? $_POST['upsell-v2-checkout-popup-addons'] : '';
        $checkout_pu_langs      = isset($_POST['upsell-v2-checkout-popup-langs']) ? $_POST['upsell-v2-checkout-popup-langs'] : '';
        $checkout_pu_title      = isset($_POST['upsell-v2-checkout-pop-up-title']) ? $_POST['upsell-v2-checkout-pop-up-title'] : '';
        $checkout_pu_prod_count = isset($_POST['upsell-v2-checkout-pop-up-prod-count']) ? $_POST['upsell-v2-checkout-pop-up-prod-count'] : '';

        // save
        update_option('upsell_v2_checkout_popup_visible', $checkout_pu_visibility);
        update_option('upsell_v2_checkout_popup_title', $checkout_pu_title);
        update_option('upsell_v2_checkout_popup_prod_count', $checkout_pu_prod_count);

        // build language => product ids array
        $ch_pu_language_ids = [];

        foreach ($checkout_pu_langs as $key => $lang) :
            $ch_pu_language_ids[$lang] = $checkout_pu_ids[$key];
        endforeach;

        // save language => product ids array if not empty
        if (!empty(array_filter($ch_pu_language_ids))) :
            update_option('upsell_v2_checkout_popup_addons', maybe_serialize($ch_pu_language_ids));
        endif;

        // ************
        // cart addons
        // ************
        $cart_ao_ids    = isset($_POST['upsell-v2-cart-addons']) ? $_POST['upsell-v2-cart-addons'] : '';
        $ccart_ao_langs = isset($_POST['upsell-v2-cart-addons-langs']) ? $_POST['upsell-v2-cart-addons-langs'] : '';

        // build language => product ids array
        $ccart_ao_langs_ids = [];

        foreach ($ccart_ao_langs as $key => $lang) :
            $ccart_ao_langs_ids[$lang] = $cart_ao_ids[$key];
        endforeach;

        // save language => product ids array if not empty
        if (!empty(array_filter($ccart_ao_langs_ids))) :
            update_option('upsell_v2_cart_addons', maybe_serialize($ccart_ao_langs_ids));
        endif;

        // *****************
        // mini cart addons
        // *****************
        $minicart_ao_ids    = isset($_POST['upsell-v2-minicart-addons']) ? $_POST['upsell-v2-minicart-addons'] : '';
        $minicart_ao_langs = isset($_POST['upsell-v2-minicart-addons-langs']) ? $_POST['upsell-v2-minicart-addons-langs'] : '';

        // build language => product ids array
        $minicart_ao_langs_ids = [];

        foreach ($minicart_ao_langs as $key => $lang) :
            $minicart_ao_langs_ids[$lang] = $minicart_ao_ids[$key];
        endforeach;

        // save language => product ids array if not empty
        if (!empty($minicart_ao_langs_ids)) :
            update_option('upsell_v2_minicart_addons', maybe_serialize($minicart_ao_langs_ids));
        endif;

    endif;

    // retrieve pll languages list
    $pll_langs = pll_languages_list();

?>
    <div id="upsell-v2-settings-cont">

        <h2><?php _e('Upsell V2 Settings', 'woocommerce') ?></h2>

        <?php if (isset($_POST['upsell-v2-upsells-submit'])) : ?>
            <div class="notice notice-success is-dismissible" style="margin-left: 0; margin-bottom: 15px;">
                <p><?php _e('Settings successfully saved.', 'woocommerce'); ?></p>
            </div>
        <?php endif; ?>

        <form id="sbwc-upsellv2-upsell-settings" action="" method="post">

            <!-- Checkout Addons -->
            <div id="upsell-v2-tabs">
                <ul>
                    <li><a href="#minicart-addons"><?php _e('Minicart Addons', 'woocommerce') ?></a></li>
                    <li><a href="#cart-addons"><?php _e('Cart Addons', 'woocommerce') ?></a></li>
                    <li><a href="#checkout-addons"><?php _e('Checkout Addons', 'woocommerce') ?></a></li>
                    <li><a href="#checkout-popup"><?php _e('Checkout Pop-Up', 'woocommerce') ?></a></li>
                </ul>

                <!-- *************** -->
                <!-- minicart addons -->
                <!-- *************** -->
                <div id="minicart-addons">

                    <!-- language/product ids inputs -->
                    <label for="upsell-v2-minicart-addons"><?php _e('Add language-specific cart addons product ids below, separated by commas, for example: 1245,2658,6987,1547 etc', 'woocommerce'); ?></label>

                    <?php

                    // retrieve cart addons
                    $mini_cart_addons = maybe_unserialize(get_option('upsell_v2_minicart_addons'));

                    foreach ($pll_langs as $key => $lang_code) : ?>
                        <label for="upsell-v2-minicart-addons"><?php _e("Upsell product IDs for " . strtoupper($lang_code), 'woocommerce') ?></label>
                        <input type="text" name='upsell-v2-minicart-addons[]' value="<?php echo $mini_cart_addons[$lang_code] ?>"><br>
                        <input type="hidden" name="upsell-v2-minicart-addons-langs[]" value="<?php echo $lang_code ?>">
                    <?php endforeach; ?>

                </div>

                <!-- *********** -->
                <!-- cart addons -->
                <!-- *********** -->
                <div id="cart-addons">

                    <!-- language/product ids inputs -->
                    <label for="upsell-v2-cart-addons"><?php _e('Add language-specific cart addons product ids below, separated by commas, for example: 1245,2658,6987,1547 etc', 'woocommerce'); ?></label>

                    <?php

                    // retrieve cart addons
                    $cart_addons = maybe_unserialize(get_option('upsell_v2_cart_addons'));

                    foreach ($pll_langs as $key => $lang_code) : ?>
                        <label for="upsell-v2-checkout-addons"><?php _e("Upsell product IDs for " . strtoupper($lang_code), 'woocommerce') ?></label>
                        <input type="text" name='upsell-v2-cart-addons[]' value="<?php echo $cart_addons[$lang_code] ?>"><br>
                        <input type="hidden" name="upsell-v2-cart-addons-langs[]" value="<?php echo $lang_code ?>">
                    <?php endforeach; ?>

                </div>

                <!-- *************** -->
                <!-- checkout addons -->
                <!-- *************** -->
                <div id="checkout-addons">

                    <!-- product ids -->
                    <label for="upsell-v2-checkout-addons"><?php _e('Add language-specific checkout add-on product ids below, separated by commas, for example: 1245,2658,6987,1547 etc', 'woocommerce'); ?></label>

                    <!-- language/product ids inputs -->
                    <?php

                    // retrieve existing checkout upsells
                    $checkout_upsells = maybe_unserialize(get_option('upsell_v2_checkout_addons'));

                    foreach ($pll_langs as $key => $lang_code) : ?>
                        <label for="upsell-v2-checkout-addons"><?php _e("Upsell product IDs for " . strtoupper($lang_code), 'woocommerce') ?></label>
                        <input type="text" name='upsell-v2-checkout-addons[]' value="<?php echo $checkout_upsells[$lang_code] ?>"><br>
                        <input type="hidden" name="upsell-v2-checkout-addons-langs[]" value="<?php echo $lang_code ?>">
                    <?php endforeach; ?>

                </div>

                <!-- ************** -->
                <!-- checkout popup -->
                <!-- ************** -->
                <div id="checkout-popup">

                    <!-- show/hide popup -->
                    <label for="upsell-v2-checkout-pop-up-visible"><?php _e('Show or hide pop-up?', 'woocommerce'); ?></label>

                    <span class="upsell-v2-admin-help"><?php _e('NOTE: If no pop-up products have been published this setting will not have any effect.', 'woocommerce'); ?></span>

                    <select id="upsell-v2-checkout-pop-up-visible" curr="<?php echo get_option('upsell_v2_checkout_popup_visible'); ?>" name="upsell-v2-checkout-pop-up-visible">
                        <option value="no"><?php _e('No', 'woocommerce'); ?></option>
                        <option value="yes"><?php _e('Yes', 'woocommerce'); ?></option>
                    </select>

                    <!-- language/product ids inputs -->
                    <label for="upsell-v2-checkout-addons"><?php _e('Add languange-specific checkout pop-up product ids below, separated by commas, for example: 1245,2658,6987,1547 etc', 'woocommerce'); ?></label>

                    <?php
                    // retrieve existing checkout popups
                    $checkout_popups = maybe_unserialize(get_option('upsell_v2_checkout_popup_addons'));

                    foreach ($pll_langs as $key => $lang_code) : ?>
                        <label for="upsell-v2-checkout-popup-addons"><?php _e("Upsell product IDs for " . strtoupper($lang_code), 'woocommerce') ?></label>
                        <input type="text" name='upsell-v2-checkout-popup-addons[]' value="<?php echo $checkout_popups[$lang_code] ?>"><br>
                        <input type="hidden" name="upsell-v2-checkout-popup-langs[]" value="<?php echo $lang_code ?>">
                    <?php endforeach; ?>

                    <!-- checkout popup title -->
                    <label for="upsell-v2-checkout-pop-up-title"><?php _e('Specify checkout pop-up title', 'woocommerce'); ?></label>

                    <input type="text" placeholder="<?php _e('You might be interested in (default):', 'woocommerce'); ?>" value="<?php echo get_option('upsell_v2_checkout_popup_title'); ?>" name="upsell-v2-checkout-pop-up-title">

                    <!-- display limit -->
                    <label for="upsell-v2-checkout-pop-up-prod-count"><?php _e('How many popup products should be shown?', 'woocommerce'); ?></label>

                    <span class="upsell-v2-admin-help"><?php _e('NOTE: Only works if at least the same amount (or more) of products are present, else only product IDs which has been published will be displayed.', 'woocommerce'); ?></span>

                    <input type="number" name="upsell-v2-checkout-pop-up-prod-count" value="<?php echo get_option('upsell_v2_checkout_popup_prod_count'); ?>" min="1" step="1"><br>

                </div>

            </div>

            <!-- submit upsell data -->
            <input class="button button-primary button-large" type="submit" name="upsell-v2-upsells-submit" value="<?php _e("Save Upsell Data", "woocommerce"); ?>">

        </form>

    </div>
<?php
    // enqueue admin js
    wp_enqueue_script('upsell-v2-admin-js');
}
