<?php


class BohiquesThemeChild
{

    public static $options = [
        [
            "name" => "G Translate Flags",
            "key" => "bohiques_gtranslate_flags",
            "default" => false
        ]
    ];

    public static function init()
    {
        add_action('admin_menu', array('BohiquesThemeChild', 'menu'));
        add_action('wp_head', array('BohiquesThemeChild', 'gtranslate_flags'));
    }

    public static function menu()
    {
        add_menu_page(
            "Bohiques Theme",
            "Bohiques Theme",
            "manage_options",
            "bohiques-settings",
            array('BohiquesThemeChild', 'render_settings'),
            "",
            6
        );
    }

    public static function render_settings()
    {
        include  WP_CONTENT_DIR . '/themes/he-bohiques-theme/templates/back/settings.php';
    }

    public static function gtranslate_flags()
    {
        if (get_option('bohiques_gtranslate_flags') ?? false) {
            include  WP_CONTENT_DIR . '/themes/he-bohiques-theme/templates/front/gtranslate-flags/flag-script.php';
        }
    }
}
