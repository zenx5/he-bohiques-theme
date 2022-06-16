<?php


class BohiquesThemeChild
{
    public $version = "v1.0.0";

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
        add_action('wp_ajax_bohiques_save', array('BohiquesThemeChild', 'save'));
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

    public static function save()
    {
        update_option($_POST["target"], json_decode($_POST["value"]));
        echo json_encode([
            "target" => $_POST["target"],
            "value" => json_decode($_POST["value"])
        ]);
        wp_die();
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
