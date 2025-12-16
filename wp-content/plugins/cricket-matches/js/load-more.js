jQuery(document).ready(function($) {
    $('.cricket-load-more-btn').on('click', function() {
        var button = $(this);
        var page = button.data('page');
        var maxPages = button.data('max-pages');
        var limit = button.data('limit');
        var loader = button.next('.cricket-load-more-loader');
        var container = button.closest('.container');

        // Show loader, hide button
        button.hide();
        loader.show();

        $.ajax({
            url: cricketAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'cricket_load_more',
                page: page,
                limit: limit
            },
            success: function(response) {
                if (response) {
                    // Append new posts before the load more wrap
                    container.find('.cricket-load-more-wrap').before(response);

                    // Update page number
                    var newPage = page + 1;
                    button.data('page', newPage);

                    // Hide button if no more pages
                    if (newPage >= maxPages) {
                        button.parent('.cricket-load-more-wrap').remove();
                    } else {
                        // Show button again
                        loader.hide();
                        button.show();
                    }
                } else {
                    // No more posts
                    button.parent('.cricket-load-more-wrap').remove();
                }
            },
            error: function() {
                loader.hide();
                button.show();
                alert('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
            }
        });
    });
});
