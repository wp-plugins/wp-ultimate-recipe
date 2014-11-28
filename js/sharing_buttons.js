jQuery(document).ready(function() {
    Socialite.setup({
        facebook: {
            lang: wpurp_sharing_buttons.facebook_lang
        },
        twitter: {
            lang: wpurp_sharing_buttons.twitter_lang
        },
        googleplus: {
            lang: wpurp_sharing_buttons.google_lang
        }
    });
    Socialite.load();
});