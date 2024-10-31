(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    // Hide review notice
    $('html').on('click', '.securiti-hide-review-notice', function(e) {

        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'securiti_hide_review_notice',
                nonce: securiti_policy_notice_admin_js.nonce,
            },
            success: function(response) {
                // Hide the notice on success
                $('.securiti-review-notice').slideUp('fast');
            }
        });

    });

    // Hide review notice
    $('html').on('click', '.securiti-later-review-notice', function(e) {

        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'securiti_later_review_notice',
                nonce: securiti_policy_notice_admin_js.nonce,
            },
            success: function(response) {
                // Hide the notice on success
                $('.securiti-review-notice').slideUp('fast');
            }
        });

    });
})(jQuery);
