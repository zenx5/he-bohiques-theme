<style>
    .container {
        display: flex;
        flex-direction: row;
    }

    .container div.table {
        width: 40%;
    }

    .container div.logo {
        justify-content: center;
        display: flex;
        flex-direction: row;
        width: 60%;
    }

    .container div.table table {
        border: 1px solid black;
        width: 100%;
        border-collapse: collapse;
    }

    table tr,
    table tr td,
    table tr th {
        border: 1px solid #00000040;
        padding: 5px 10px;
    }

    table tr.head {
        background-color: #00000010;
    }

    .bohiques-sub-title {
        display: block;
        margin-bottom: 20px;
    }
</style>

<div id="notif">
    <div class="notice notice-success" id="saved">
        <p>Se <span id="option_value"></span> <b id="option_name"></b></p>
    </div>
</div>

<?php

use GuzzleHttp\Client;

if (isset($_GET['id'])) {
    $clients = BohiquesDatabase::read_clients($_GET['id']);

    if (count($clients) > 0) {
        try {
            $token = $clients[0]->token;
            $url = $clients[0]->url . "/wp-json/bohiques/v1/options/$token";
            $client = new Client();
            $response = $client->request("get", $url);
            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['code'] == "202") {
                $options = $result["data"];
            } else {
                $options = [];
            }
        } catch (Exception $error) {
            $options = self::$options;
        }
    } else {
        $options = self::$options;
    }
} else {
    $options = self::$options;
}
?>

<h3>Bohiques Settings <?= get_option('get_time_test') ?></h3>
<?php if (isset($clients[0])) : ?>
    <span class="bohiques-sub-title">
        <i>URL: <?= $clients[0]->url ?></i>
    </span>
<?php endif; ?>
<div class="container">
    <div class="table">
        <table>
            <tr class="head">
                <th>
                    Opción
                </th>
                <th>
                    Habilitar
                </th>
            </tr>
            <?php foreach ($options as $option) : ?>
                <tr>
                    <th><?= $option['name'] ?></th>
                    <td align="center">
                        <input type="checkbox" id="<?= $option['key'] ?>" data-tag="<?= $option['name'] ?>" name="<?= $option['key'] ?>" <?= get_option($option['key'], $option['default']) ? 'checked' : '' ?> />
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="logo">
        <a href="https://bohiques.com" target="_blank">
            <img style="width: 300px;margin-left: auto;margin-right: auto;display: block;" src="<?= WP_PLUGIN_URL . "/pay-it-ath/templates/screenshot.png" ?>" />
        </a>
    </div>
</div>
<?php if (isset($_GET['id'])) : ?>
    <div style="margin-top: 20px;">
        <button class="button" id="back-connection">Volver</button>
    </div>
<?php endif; ?>

<script>
    (function($) {
        $('#saved').hide();

        $("#back-connection").click(function() {
            location.href = location.origin + location.pathname + "?page=bohiques-theme-connection"
        })

        $("input[type='checkbox']").change(function() {
            const value = $(this).is(':checked');
            const target = $(this).attr('name');
            const name = $(this).data('tag');
            console.log(value, target, name)
            $.ajax({
                method: <?= isset($_GET['id']) ? "'put'" : "'post'" ?>,
                url: <?= isset($_GET['id']) ? "'$url'" : 'ajaxurl' ?>.replace("http", "https"),
                data: {
                    action: 'bohiques_save',
                    name: escape(name),
                    target: escape(target),
                    value: escape(value)
                },
                success: response => {
                    <?php if (isset($_GET['id'])) : ?>
                        const json = response
                    <?php else : ?>
                        const json = JSON.parse(response)
                    <?php endif; ?>
                    console.log(json)
                    $('#saved').show();
                    $('#option_value').text(json.value ? 'habilitado' : 'deshabilitado')
                    $('#option_name').text(json.name)
                    $('#saved').fadeOut(4000);
                }
            })
        })
    })(jQuery);
</script>