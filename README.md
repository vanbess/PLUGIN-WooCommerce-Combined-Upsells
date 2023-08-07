# Silverback Dev Combined Upsell Plugin V2 for WooCommerce

This is the reworked version of the original SBWC Combined Upsell plugin for WooCommerce.

Due to various reported conflicts and outdated code it was decided to redo the plugin from scratch using more streamlined and optimized code and code which is more modernized and easier to use and understand.

**Current upsell methods which are included are:**

1. Product Upsell - upsells which are displayed on the single product page, right before the add to cart button.
2. Checkout Add-on Upsell - upsells which are displayed on the checkout page, prompting users to add additional products to their cart prior to checkout.
3. Checkout Pop-up Upsell - popup which displays on checkout page.
4. Cart Add-on Upsell - upsells which display on cart page.
5. Mini-cart Add-on Upsell - upsells which display in mini cart (Flatsome theme). Upsells pulled from Cart Add-on Upsell.
6. Product Bundle Upsell - bundled upsells for product single.

**Upsell tracking module**

Currently supports impressions, conversions etc tracking for following modules (found under Upsell v2 Tracking admin menu): Product Upsell, Checkout Add-on Upsell, Checkout Pop-up Upsell and Cart Add-on Upsell. Planned support for Multi Woo Checkout items. Support for multiple locale tracking via Polylang locale based tracking items. Note that custom post type support needs to be added under Polylang for tracking CPTs under Languages -> Settings page in backend for this feature to function properly.

**!!!NOTE: PLUGIN REQUIRES POLYLANG TO BE INSTALLED AND ACTIVATED AND TRACKING CUSTOM POST TYPE SUPPORT TO BE ADDED UNDER POLYLANG SETTINGS PAGE, OTHERWISE IT WILL BAIL SILENTLY**