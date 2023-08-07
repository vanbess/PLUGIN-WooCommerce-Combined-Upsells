<?php

/**
 * Register custom post type for single product addons/upsells
 */
add_action('init', 'single_addon_cpt');

function single_addon_cpt()
{
    $args = array(
        'labels'             => array(
            'name'               => __('Single Product Upsells', 'woocommerce'),
            'singular_name'      => __('Single Product Upsell', 'woocommerce'),
            'add_new'            => __('Add New', 'woocommerce'),
            'add_new_item'       => __('Add New Single Product Upsell', 'woocommerce'),
            'edit_item'          => __('Edit Single Product Upsell', 'woocommerce'),
            'new_item'           => __('New Single Product Upsell', 'woocommerce'),
            'view_item'          => __('View Single Product Upsell', 'woocommerce'),
            'search_items'       => __('Search Single Product Upsells', 'woocommerce'),
            'not_found'          => __('Nothing Found', 'woocommerce'),
            'not_found_in_trash' => __('Nothing found in the Trash', 'woocommerce'),
            'parent_item_colon'  => ''
        ),
        'show_in_menu'       => 'sbwc-uv2-tracking',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),

    );

    register_post_type('single-addon', $args);
}

/**
 * Add CPT meta input for product id
 */
function single_addon_cpt_metabox()
{
    add_meta_box(
        "single_addon_product_id_meta",
        "Product ID",
        "single_addon_meta_input",
        "single-addon",
        "normal",
        "low"
    );
}

add_action('admin_init', 'single_addon_cpt_metabox');

/**
 * Render meta input HTML
 */
function single_addon_meta_input()
{ ?>
    <!-- product id -->
    <p>
        <label><b><i><?php _e('Product ID:', 'woocommerce'); ?></i></b></label>
        <input type="number" name="product_id" min="0" value="<?php echo get_post_meta(get_the_ID(), 'product_id', true); ?>">
    </p>

    <!-- percentage discount -->
    <p>
        <label><b><i><?php _e('Discount %:', 'woocommerce'); ?></i></b></label>
        <input type="number" name="discount" min="0" value="<?php echo get_post_meta(get_the_ID(), 'discount', true); ?>">
    </p>
<?php
}

/**
 * Save CPT post meta (product ID as per above function)
 */
function save_single_addon_cpt_meta()
{
    global $post;

    if (isset($_POST['product_id'])) :

        // product id
        update_post_meta($post->ID, 'product_id', $_POST['product_id']);

        // product discount
        update_post_meta($post->ID, 'discount', $_POST['discount']);
    endif;

}

add_action('save_post_single-addon', 'save_single_addon_cpt_meta');

/**
 * Add custom CPT columns
 */
function single_addon_cpt_custom_head_columns($defaults)
{

    $defaults['product_id']      = __('Product ID', 'woocommerce');
    $defaults['count_view']      = __('Impressions', 'woocommerce');
    $defaults['count_click']     = __('Clicks', 'woocommerce');
    $defaults['count_paid']      = __('Conversions', 'woocommerce');
    $defaults['conversion_rate'] = __('Conversion Rate', 'woocommerce');
    $defaults['revenue']         = __('Revenue', 'woocommerce');

    unset($defaults['date']);

    return $defaults;
}

add_filter('manage_single-addon_posts_columns', 'single_addon_cpt_custom_head_columns', 10);

/**
 * Add custom CPT column data
 */
function single_addon_cpt_custom_column_data($col_name, $post_id)
{

    switch ($col_name) {
        case 'post_id':
            echo ($post_id);
            break;
        case 'product_id':
            $prod_id     = get_post_meta($post_id, 'product_id', true);
            echo $prod_id ?: '-';
            break;
        case 'count_view':
            $view_count  = get_post_meta($post_id, 'count_view', true);
            echo $view_count ?: '-';
            break;
        case 'count_click':
            $click_count = get_post_meta($post_id, 'count_click', true);
            echo $click_count ?: '-';
            break;
        case 'count_paid':
            $paid_count  = get_post_meta($post_id, 'count_paid', true);
            echo $paid_count ?: '-';
            break;
        case 'conversion_rate':
            $paid_count  = get_post_meta($post_id, 'count_paid', true);
            $view_count  = get_post_meta($post_id, 'count_view', true);
            $click_count = get_post_meta($post_id, 'count_click', true);

            $conversion_rate = 0;

            if (is_numeric($paid_count) && is_numeric($view_count) && is_numeric($click_count)) :
                $impressions = $view_count + $click_count;
                $rate        = $paid_count && $view_count ? (($paid_count * 100) / $impressions) : 0;
                update_post_meta($post_id, 'conversion_rate', $rate);
                $conversion_rate = get_post_meta($post_id, 'conversion_rate', true);
            endif;

            echo $conversion_rate > 0 ? number_format($conversion_rate, 2, '.', '') . '%' : '-';

            break;

        case 'revenue':

            $revenue        = get_post_meta($post_id, 'revenue', true);
            $order_currency = get_post_meta($post_id, 'order_currency', true);

            if ($revenue && $order_currency && function_exists('alg_wc_cs_get_exchange_rate')) :
                if ($order_currency !== 'USD') :
                    $ext_rate = alg_wc_cs_get_exchange_rate($order_currency, 'USD');
                    $conv_revenue = $revenue * $ext_rate;
                    echo 'USD ' . number_format($conv_revenue, 2, '.', '');
                else :
                    echo $order_currency . ' ' . number_format($revenue, 2, '.', '');
                endif;
            elseif ($revenue && $order_currency && !function_exists('alg_wc_cs_get_exchange_rate')) :
                echo $order_currency . ' ' . number_format($revenue, 2, '.', '');
            elseif (!$revenue) :
                echo '-';
            endif;

            break;
    }
}

add_action('manage_single-addon_posts_custom_column', 'single_addon_cpt_custom_column_data', 10, 2);

/**
 * make columns sortable
 */
add_filter('manage_edit-single-addon_sortable_columns', 'upsellv2_sort_single_addon_tracking_columns');

function upsellv2_sort_single_addon_tracking_columns($columns)
{
    $columns['count_view']      = 'count_view';
    $columns['count_click']     = 'count_click';
    $columns['count_paid']      = 'count_paid';
    $columns['conversion_rate'] = 'conversion_rate';
    $columns['revenue']         = 'revenue';

    return $columns;
}

/**
 * process column sorting using pre_get_posts
 */
add_action('pre_get_posts', 'upsellv2_single_addon_sortby');

function upsellv2_single_addon_sortby($query)
{

    // bail if not admin or not main query
    if (!is_admin() || !$query->is_main_query()) :
        return;
    endif;

    // sort by impressions
    if ('count_view' === $query->get('orderby')) :
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'count_view');
        $query->set('meta_type', 'numeric');
    endif;

    // sort by clicks
    if ('count_click' === $query->get('orderby')) :
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'count_click');
        $query->set('meta_type', 'numeric');
    endif;

    // sort by conversions
    if ('count_paid' === $query->get('orderby')) :
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'count_paid');
        $query->set('meta_type', 'numeric');
    endif;

    // sort by conversion rate
    if ('conversion_rate' === $query->get('orderby')) :
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'conversion_rate');
        $query->set('meta_type', 'decimal');
    endif;

    // sort by revenue
    if ('revenue' === $query->get('orderby')) :
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'revenue');
        $query->set('meta_type', 'decimal');
    endif;
}

/*********************************************
 * Query single product addons tracking items
 *********************************************/
function upsellv2_query_single_addon_tracking_items()
{

    $tracking_items = new WP_Query([
        'post_type'      => 'single-addon',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    // holds tracked item data
    $tracked_item_data = [];

    // if tracked items present, push tracking id and product id to $tracked_item_data
    if ($tracking_items->have_posts()) :
        while ($tracking_items->have_posts()) :
            $tracking_items->the_post();

            $tracked_item_data[get_the_ID()] = get_post_meta(get_the_ID(), 'product_id', true);

        endwhile;
        wp_reset_postdata();

    // if tracked items not present, set $tracked_item_data to false
    else :
        $tracked_item_data = false;
    endif;

    // return tracked item data
    return $tracked_item_data;
}

/*********************************************************
 * AJAX functions which updates tracking data for upsells
 *********************************************************/
add_action('wp_ajax_nopriv_upsellv2_update_single_upsell_tracking', 'upsellv2_update_single_upsell_tracking');
add_action('wp_ajax_upsellv2_update_single_upsell_tracking', 'upsellv2_update_single_upsell_tracking');

function upsellv2_update_single_upsell_tracking()
{

    check_ajax_referer('update single product upsell tracking data');

    // ****************
    // REGISTER CLICKS
    // ****************
    if (isset($_POST['clicks'])) :

        $prod_id = absint($_POST['prod_id']);

        $tracked_items = upsellv2_query_single_addon_tracking_items();

        // bail early if $tracked_items === false
        if ($tracked_items === false) :
            wp_die();
        endif;

        // check if prod id is present in $tracked_items array
        if (in_array($prod_id, $tracked_items)) :

            // retrieve tracking id
            $tracking_id = array_search($prod_id, $tracked_items);

            // retrieve existing clicks (if any)
            $clicks = get_post_meta($tracking_id, 'count_click', true);

            // if existing clicks
            if ($clicks) :

                // increment clicks
                $old_clicks = absint($clicks);
                $new_clicks = $old_clicks + 1;

                // update clicks to new value
                update_post_meta($tracking_id, 'count_click', $new_clicks);

            // if no existing clicks
            else :

                // set click
                $new_clicks = 1;

                // update click meta
                update_post_meta($tracking_id, 'count_click', $new_clicks);

            endif;

        endif;

    endif;

    wp_die();
}

/*********************************************
 * JS which handles updating of tracking data
 *********************************************/
function upsellv2_single_addon_tracking_js()
{ ?>

    <script>
        jQuery(document).ready(function($) {

            /******************
             * REGISTER CLICKS
             ******************/
            $('.upsell-v2-product-upsell-table-cont').click(function() {

                // retrieve product id (simple or variable)
                if ($(this).find('.upsell-v2-product-upsell-simple-product').data('product-id')) {
                    var prod_id = $(this).find('.upsell-v2-product-upsell-simple-product').data('product-id');
                } else {
                    var prod_id = $(this).find('.upsell-v2-product-upsell-variable-product').data('product-id');
                }

                // retrieve nonce
                var nonce = $(document).find('#upsell-v2-product-upsell-title').data('tracking-nonce');

                // setup ajaxc
                var data = {
                    '_ajax_nonce': nonce,
                    'action': 'upsellv2_update_single_upsell_tracking',
                    'prod_id': prod_id,
                    'clicks': true
                }

                $.post('<?php echo admin_url('admin-ajax.php') ?>', data);

            });

        });
    </script>

<?php }

/**
 * Enqueue JS which handles tracking data
 */
add_action('wp_footer', 'upsellv2_single_upsell_tracking_scripts');

function upsellv2_single_upsell_tracking_scripts()
{
    if (is_product()) :
        wp_enqueue_script('upsellv2-single-addon-tracking-js', upsellv2_single_addon_tracking_js(), ['jquery'], false, true);
    endif;
}

/**
 * Custom bulk action to reset tracking data
 */
add_filter('bulk_actions-edit-single-addon', function ($bulk_actions) {
    $bulk_actions['reset-tracking'] = __('Reset Tracking', 'woocommerce');
    return $bulk_actions;
});

/**
 * Actually reset tracking via bulk action
 */
add_filter('handle_bulk_actions-edit-single-addon', function ($redirect_url, $action, $post_ids) {
    if ($action == 'reset-tracking') {
        foreach ($post_ids as $post_id) {

            // reset all tracking meta
            delete_post_meta($post_id, 'count_view');
            delete_post_meta($post_id, 'count_click');
            delete_post_meta($post_id, 'count_paid');
            delete_post_meta($post_id, 'conversion_rate');
            delete_post_meta($post_id, 'revenue');
        }
        $redirect_url = add_query_arg('reset-tracking', count($post_ids), $redirect_url);
    }
    return $redirect_url;
}, 10, 3);
