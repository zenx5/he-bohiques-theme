<?php
if (isset($_GET['id'])) {
    include 'client.php';
    return;
}
$clients = BohiquesDatabase::read_clients();
// $clients = [
//     [
//         "id" => 2,
//         "url" => "https://bohiques.com",
//         "token" => "232348490340",
//         "status" => "active"
//     ]
// ];


$connection_type = get_option('bohiques-theme-connection-type') ? get_option('bohiques-theme-connection-type') : DEFAULT_CONNECTION_TYPE;

?>

<div id="notif">
    <div class="notice notice-success" id="saved">
        Guardado!
    </div>
</div>
<h3>Bohiques Settings</h3>

<table border="1px" width="50%">
    <tr>
        <th>
            Connection Type
        </th>
        <td>
            <select id="bohiques-theme-connection-type" name="bohiques-theme-connection-type" style="min-width:100%; width:100%;">
                <option value="Client" <?= $connection_type == "Client" ? "selected" : "" ?>>Cliente</option>
                <option value="Server" <?= $connection_type == "Server" ? "selected" : "" ?>>Server</option>
            </select>
        </td>
    </tr>
</table>

<table id="bohiques-client" width="60%" style="margin-top: 20px;">
    <tr>
        <th>Dominio</th>
        <td>
            <?= get_site_url() ?>
        </td>
        <td style="display: flex; flex-direction:row;justify-content:center;">

        </td>
    </tr>
    <tr>
        <th>Token de Acceso</th>
        <td>
            <i id="bohiques_token_access"><?= get_option("bohiques_token_access") ?></i>
        </td>
        <td style="display: flex; flex-direction:row;justify-content:center;">
            <button id="update_token" class="button" style="display:flex;align-items:center;margin:1px;">
                <i class="dashicons-before dashicons-update" style="display:flex"></i>
            </button>
        </td>
    </tr>
</table>

<table id="bohiques-server" width="60%" style="margin-top: 20px;">
    <tr>
        <th>Site</th>
        <th>Token</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($clients as $client) : ?>
        <tr>
            <td style="text-align: center;">
                <a href="<?= $client->url ?>" target="_blank"><?= $client->url ?></a>
            </td>
            <td style="text-align: center;"><?= $client->token ?></td>
            <td style="text-align: center;">
                <select class="status" data-id="<?= $client->id ?>">
                    <option value='active' <?= $client->status == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value='deactive' <?= $client->status == 'deactive' ? 'selected' : '' ?>>Deactive</option>
                </select>
            </td>
            <td style="display: flex; flex-direction:row;justify-content:center;">
                <button data-id="<?= $client->id ?>" class="button admin-client admin-client-<?= $client->id ?>" <?= $client->status == 'deactive' ? 'disabled' : '' ?> style="display:flex;align-items:center;margin:1px;">
                    <i class="dashicons-before dashicons-admin-generic" style="display:flex"></i>
                </button>
                <button data-id="<?= $client->id ?>" class="button delete-client" style="display:flex;align-items:center;margin:1px;">
                    <i class="dashicons-before dashicons-trash" style="display:flex"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td style="padding:10px;"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <input type="text" id="bohiques-client-url" name="bohiques-client-url" value="" />
        </td>
        <td style="text-align: center;">
            <input type="text" id="bohiques-client-token" name="bohiques-client-token" value="" />
        </td>
        <td style="text-align: center;">
            <select id="bohiques-client-status" name="bohiques-client-status">
                <option selected>Active</option>
                <option>Deactive</option>
            </select>
        </td>
        <td style="display: flex; flex-direction:row;justify-content:center;">
            <button class="button" style="display:flex;align-items:center;margin:1px;" id="save-client">
                <i class="dashicons-before dashicons-cloud-saved" style="display:flex"></i>
            </button>
        </td>
    </tr>

</table>


<script>
    (function($) {
        function toggle() {
            const type = $("#bohiques-theme-connection-type").val();
            if (type === 'Client') {
                $("#bohiques-client").show();
                $("#bohiques-server").hide();
            } else {
                $("#bohiques-client").hide();
                $("#bohiques-server").show();
            }
        }

        toggle();

        $('#saved').hide();



        $(".admin-client").click(function() {
            location.href = location.origin + location.pathname + "?page=bohiques-settings&id=" + $(this).data('id')
        })

        $(".status").change(function() {
            const id = $(this).data("id");
            const status = $(this).val();
            const disabled = status == 'active' ? false : true;
            $(".admin-client-" + id).attr("disabled", disabled);
            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_update_client',
                    id: id,
                    status: status
                }
            })
        })

        $(".delete-client").click(function() {

            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_delete_client',
                    id: $(this).data('id')
                },
                success: () => {
                    location.reload()
                }
            })
        })

        $("#save-client").click(function() {
            const url = $("#bohiques-client-url").val();
            const token = $("#bohiques-client-token").val();
            const status = $("#bohiques-client-status").val();

            if (url != '' && token != '') {

                console.log("saving...")
                $.ajax({
                    method: 'post',
                    url: ajaxurl,
                    data: {
                        action: 'bohiques_create_client',
                        url: url,
                        token: token,
                        status: status
                    },
                    success: () => {
                        location.reload()
                    }
                })
            }
        })

        $("#update_token").click(function() {
            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_update_token'
                },
                success: response => {
                    $("#bohiques_token_access").text(response);
                }
            })
        })

        $("#bohiques-theme-connection-type").change(function() {
            console.log($(this).val())
            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_save',
                    target: 'bohiques-theme-connection-type',
                    value: $(this).val(),
                    name: 'Connection Type'
                },
                success: (response) => {
                    console.log(response)
                    $('#saved').show();
                    $('#saved').fadeOut(4000);
                    toggle();
                }
            })
        })
    })(jQuery);
</script>