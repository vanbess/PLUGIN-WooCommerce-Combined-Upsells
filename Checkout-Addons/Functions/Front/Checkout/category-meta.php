<?php

/**
 * Add selector to category add screen
 */
function upsell_v2_checkout_addon_cat_selector() {
    ?>
    <tr class="form-field">
        <th scope="row">
            <?php pll_e('Enter comma separated upsell IDs if you would like to override checkout add-on upsells'); ?>
        </th>
        <td>
            <input type="text" name="upsell-v2-checkout-addon-cats" id="upsell-v2-checkout-addon-cats" >
        </td>
    </tr>
    <?php
}

/**
 * Add selector to category edit screen
 */
function upsell_v2_checkout_addon_cat_edit_selector($term) {

    // get term id
    $term_id = $term->term_id;

    // get specified ids
    $specced_ids = get_term_meta($term_id, 'upsell-v2-checkout-addon-cats', true);
    ?>

    <tr class="form-field">
        <th scope="row">
            <label for="upsell-v2-checkout-addon-cats">
                <?php pll_e('Enter comma separated upsell IDs if you would like to override checkout add-on upsells'); ?>
            </label>
        </th>
        <td>
            <input type="text" name="upsell-v2-checkout-addon-cats" id="upsell-v2-checkout-addon-cats" value="<?php print $specced_ids; ?>">
        </td>
    </tr>

    <?php
}

/**
 * Save term meta
 */
function upsell_v2_checkout_addon_cat_data_save($term_id) {
    // get submitted data
    $submitted_val = $_POST['upsell-v2-checkout-addon-cats'];

    // udpate term meta
    update_term_meta($term_id, 'upsell-v2-checkout-addon-cats', $submitted_val);
}

// add custom meta to product cats
add_filter('product_cat_add_form_fields', 'upsell_v2_checkout_addon_cat_selector');
add_filter('product_cat_edit_form_fields', 'upsell_v2_checkout_addon_cat_edit_selector');

// save category checkout add-on settings
add_action('edited_product_cat', 'upsell_v2_checkout_addon_cat_data_save');
add_action('create_product_cat', 'upsell_v2_checkout_addon_cat_data_save');
