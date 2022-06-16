<?php



?>
<div id="notif">
    <div class="notice notice-success" id="saved">
        <p>Se <span id="option_value"></span> <b id="option_name"></b></p>
    </div>
</div>


<h3>Bohiques Settings</h3>

<table border="1px" width="40%">
    <tr>
        <th>
            Opción
        </th>
        <th>
            Habilitar
        </th>
    </tr>
    <?php foreach (self::$options as $option) : ?>
        <tr>
            <th><?= $option['name'] ?></th>
            <td align="center">
                <input type="checkbox" id="<?= $option['key'] ?>" data-tag="<?= $option['name'] ?>" name="<?= $option['key'] ?>" <?= get_option($option['key'], $option['default']) ? 'checked' : '' ?> />
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<script>
    (function($) {
        $('#saved').hide();
        $("input[type='checkbox']").change(function() {
            const value = $(this).is(':checked');
            const target = $(this).attr('name');
            const name = $(this).data('tag');
            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_save',
                    name: name,
                    target: target,
                    value: value
                },
                success: response => {
                    const json = JSON.parse(response)
                    $('#saved').show();
                    $('#option_value').text(json.value ? 'habilitado' : 'deshabilitado')
                    $('#option_name').text(json.name)
                    $('#saved').fadeOut(4000);
                }
            })
        })
    })(jQuery);
</script>