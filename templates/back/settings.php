<?php

use Gitonomy\Git\Repository;

$repository = new Repository(WP_CONTENT_DIR . '/themes/he-bohiques-theme');

// foreach ($repository->getReferences()->getBranches() as $branch) {
//     echo '- '.$branch->getName().PHP_EOL;
// }

echo $repository->run('add', ['.']);

echo WP_CONTENT_DIR . '/themes/he-bohiques-theme';

?>

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
                <input type="checkbox" id="<?= $option['key'] ?>" name="<?= $option['key'] ?>" <?= get_option($option['key'], $option['default']) ? 'checked' : '' ?> />
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<script>
    (function($) {
        $("input[type='checkbox']").change(function() {
            const value = $(this).is(':checked');
            const target = $(this).attr('name');
            $.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: 'bohiques_save',
                    target: target,
                    value: value
                },
                success: response => {
                    console.log(response)
                }
            })
        })
    })(jQuery);
</script>