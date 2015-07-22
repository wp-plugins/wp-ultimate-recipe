jQuery(document).ready(function() {
    jQuery('.wpurp-twitter').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { twitter: true },
            buttons: {
                twitter: {
                    count: jQuery(btn).data('layout'),
                    lang: wpurp_sharing_buttons.twitter_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpurp-facebook').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { facebook: true },
            buttons: {
                facebook: {
                    action: 'like',
                    layout: jQuery(btn).data('layout'),
                    share: jQuery(btn).data('share'),
                    lang: wpurp_sharing_buttons.facebook_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpurp-google').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { googlePlus: true },
            buttons: {
                googlePlus: {
                    size: jQuery(btn).data('layout'),
                    annotation: jQuery(btn).data('annotation'),
                    lang: wpurp_sharing_buttons.google_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpurp-pinterest').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { pinterest: true },
            buttons: {
                pinterest: {
                    media: jQuery(btn).data('media'),
                    description: jQuery(btn).data('description'),
                    config: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false,
            click: function(api, options) {
                api.openPopup('pinterest');
            }
        });
    });

    jQuery('.wpurp-stumbleupon').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { stumbleupon: true },
            buttons: {
                stumbleupon: {
                    layout: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpurp-linkedin').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { linkedin: true },
            buttons: {
                linkedin: {
                    counter: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });
});