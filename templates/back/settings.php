<div id="notif">
    <div class="notice notice-success" id="saved">
        <p>Se <span id="option_value"></span> <b id="option_name"></b></p>
    </div>
</div>

<?php

use GuzzleHttp\Client;

if (isset($_GET['id'])) {
    $clients = BohiquesDatabase::read_clients($_GET['id']);

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
} else {
    $options = self::$options;
}



?>
<h3>Bohiques Settings <?= get_option('get_time_test') ?></h3>
<?php if (isset($clients[0])) : ?>
    <span style="display:block;margin-bottom: 20px;">
        <i>URL: <?= $clients[0]->url ?></i>
    </span>
<?php endif; ?>

<table border="1px" width="40%">
    <tr>
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
                    name: name,
                    target: target,
                    value: value
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