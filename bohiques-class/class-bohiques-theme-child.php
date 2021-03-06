<?php


class BohiquesThemeChild
{
    public static $version = "v1.0.0";

    public static $options = [

        [
            "name" => "G Translate Flags PR",
            "key" => "bohiques_gtranslate_flags_pr",
            "default" => false
        ]
    ];

    public static function init()
    {
        define('DEFAULT_CONNECTION_TYPE', 'Client');
        define('DIR_THEME', WP_CONTENT_DIR . '/themes/he-bohiques-theme');
        add_action('admin_menu', array('BohiquesThemeChild', 'menu'));
        add_action('wp_head', array('BohiquesThemeChild', 'gtranslate_flags'));

        add_action('wp_ajax_bohiques_save', array('BohiquesThemeChild', 'save'));
        add_action('wp_ajax_bohiques_update_token', array('BohiquesThemeChild', 'update_token'));
        add_action('wp_ajax_bohiques_create_client', array('BohiquesThemeChild', 'create_client'));
        add_action('wp_ajax_bohiques_update_client', array('BohiquesThemeChild', 'update_client'));
        add_action('wp_ajax_bohiques_delete_client', array('BohiquesThemeChild', 'delete_client'));
        add_action('rest_api_init', array('BohiquesThemeChild', 'custom_endpoints'));


        //add_action('wp_ajax_nopriv_bohiques_remote_connection', array('BohiquesThemeChild', 'remove_connection'));

        BohiquesDatabase::init();
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
        add_submenu_page(
            "bohiques-settings",
            "Connection",
            "Connection",
            "manage_options",
            "bohiques-theme-connection",
            array('BohiquesThemeChild', 'render_connection')
        );
    }

    public static function save()
    {

        $value = esc_html((is_numeric($_POST['value']) || is_bool($_POST['value'])) ? json_decode($_POST["value"]) : $_POST["value"]);

        update_option($_POST["target"], $value);
        echo json_encode([
            "target" => esc_html($_POST["target"]),
            "value" => $value,
            "name" => esc_html($_POST["name"])
        ]);
        wp_die();
    }

    public static function update_token()
    {
        $token = rand(1000000000, 9999999999);
        update_option("bohiques_token_access", $token);
        echo $token;
        wp_die();
    }

    public static function create_client()
    {
        echo json_encode(BohiquesDatabase::create_clients([
            "url" => esc_url($_POST["url"]),
            "token" => esc_html($_POST["token"]),
            "status" => esc_html($_POST["status"])
        ]));

        wp_die();
    }

    public static function delete_client()
    {
        BohiquesDatabase::delete_client($_POST['id']);
        wp_die();
    }

    public static function update_client()
    {
        BohiquesDatabase::update_client($_POST['id']);
        wp_die();
    }

    public static function custom_endpoints()
    {

        register_rest_route('bohiques/v1', '/options/(?P<id>\d+)', array(
            'methods' => 'get',
            'callback' => array('BohiquesThemeChild', 'get_options'),
        ));

        register_rest_route('bohiques/v1', '/options/(?P<id>\d+)', array(
            'methods' => 'put',
            'callback' => array('BohiquesThemeChild', 'update_options'),
        ));
    }

    public static function update_options(WP_REST_Request $request)
    {
        $token = esc_html($request['id']);
        $status = esc_html($_POST['status']);
        if (true || $token == get_option("bohiques_token_access")) {
            if (in_array($status, ['active', 'deactive']) && $status == 'active') {
                $value = json_decode(esc_html($request['value']));
                update_option(esc_html($request["target"]), $value);
                return [
                    "target" => esc_html($request["target"]),
                    "value" => $value,
                    "name" => esc_html($request["name"])
                ];
            }
        }
    }

    public static function get_options(WP_REST_Request $request)
    {
        $token = esc_html($request['id']);
        $status = esc_html($_POST['status']);
        if (true || $token == get_option("bohiques_token_access")) {
            if (in_array($status, ['active', 'deactive']) && $status == 'active') {
                return [
                    "code" => 202,
                    "token" => $token,
                    "data" => self::$options
                ];
            }
        } else {
            return [
                "code" => 404,
                "token" => $token,
                "data" => []
            ];
        }
    }



    public static function render_settings()
    {
        include  WP_CONTENT_DIR . '/themes/he-bohiques-theme/templates/back/settings.php';
    }

    public static function render_connection()
    {
        include  WP_CONTENT_DIR . '/themes/he-bohiques-theme/templates/back/connection.php';
    }

    public static function gtranslate_flags()
    {
        if (get_option('bohiques_gtranslate_flags') ?? false) {
            include  WP_CONTENT_DIR . '/themes/he-bohiques-theme/templates/front/gtranslate-flags/flag-script.php';
        }
    }
}
