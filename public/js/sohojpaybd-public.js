(function ($) {
    "use strict";

    $(document).ready(function () {
        $('form.woocommerce-checkout').on('checkout_place_order_success', function (event, data) {
            if (data.result === 'success' && data.payment_url) {
                if ($('input[name="payment_method"]:checked').val() === 'sohojpaybd') {
                    
                    // Load Sohojpay script dynamically
                    var script = document.createElement('script');
                    script.src = "https://scripts.sohojpaybd.com/checkout.js";
                    script.onload = function () {
                        sohojpayCheckOut(data.payment_url);
                    };
                    document.body.appendChild(script);

                    return false; // Prevent WooCommerce redirect
                }
            }
        });
    });

})(jQuery);
