<?php

/**
 * Tracking init file
 */

// 1. Admin menu
include UPSELL_V2_PATH . 'Tracking/Admin/tracking-menu-admin.php';

// 2. Single product upsells
include UPSELL_V2_PATH . 'Tracking/Admin/single-product.php';

// 3. Cart addons
include UPSELL_V2_PATH . 'Tracking/Admin/cart-addons.php';

// 4. Checkout addons
include UPSELL_V2_PATH . 'Tracking/Admin/checkout-addons.php';

// 5. Checkout popup
include UPSELL_V2_PATH . 'Tracking/Admin/checkout-popup.php';

// 6. Thank you page tracking update
include UPSELL_V2_PATH . 'Tracking/Front/thank-you-page.php';

// 7. Cron to update impressions
include UPSELL_V2_PATH . 'Tracking/Caching/update-impressions-chron.php';

// 8. Add PLL for tracking CPTs by defualt
include UPSELL_V2_PATH . 'Tracking/PLL/add-pll-support.php';

// 9. Apply upsell product cart discount, if present
include UPSELL_V2_PATH . 'Tracking/Front/cart-discounts.php';
