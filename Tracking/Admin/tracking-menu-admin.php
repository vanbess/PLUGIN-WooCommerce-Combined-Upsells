<?php

// Register main admin menu to hold tracking data
add_action('admin_menu', 'sbwc_upsell_register_tracking_admin_menu');

function sbwc_upsell_register_tracking_admin_menu()
{
    add_menu_page(__('Upsell V2 Tracking Info'), __('Upsell V2 Tracking'), 'manage_options', 'sbwc-uv2-tracking', 'sbwc_uv2_tracking', 'dashicons-chart-line', 20);
}
