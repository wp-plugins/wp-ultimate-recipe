jQuery(document).ready(function() {

    var button_title = jQuery('#wpurp-insert-recipe').val();

    tinymce.create('tinymce.plugins.ultimaterecipe_plugin', {
        init : function(ed, url) {
            ed.addCommand('ultimaterecipe_insert_shortcode', function() {
                ed.windowManager.open({
                    id: "wpurp-form",
                    width: 480,
                    height: "auto",
                    wpDialog: true,
                    title: "WP Ultimate Recipe Plugin - " + button_title
                }, {
                    plugin_url: url
                });
            });

            ed.addButton('ultimaterecipe_button', {
                title : button_title,
                cmd : 'ultimaterecipe_insert_shortcode',
                image: url + '/../img/icon_20.png'
            });
        }
    });

    tinymce.PluginManager.add('ultimaterecipe_button', tinymce.plugins.ultimaterecipe_plugin);
});