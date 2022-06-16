<h3>Bohiques Settings</h3>

<table border="1px" width="40%">
    <tr>
        <th>
            Opción
        </th>
        <th>
            Valor
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