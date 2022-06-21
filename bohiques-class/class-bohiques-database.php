<?php


class BohiquesDatabase
{

    public static function init()
    {
        global $wpdb;

        $results = $wpdb->get_results("
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}bohiques_clients` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(255) NULL,
                `token` VARCHAR(255) NULL,
                `status` VARCHAR(45) NULL,
                PRIMARY KEY (`id`))
            ENGINE = InnoDB;
            ", OBJECT);
    }

    public static function read_clients($id = null)
    {
        if (null === $id) {
            $cond = "1";
        } else {
            $cond = "id = $id";
        }
        global $wpdb;
        try {
            return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bohiques_clients WHERE $cond", OBJECT);
        } catch (Exception $err) {
            return $err;
        }
    }

    public static function update_client($id)
    {
        global $wpdb;
        $status = $_POST['status'];
        if (in_array($status, ['active', 'deactive'])) {
            return $wpdb->get_results("UPDATE {$wpdb->prefix}bohiques_clients SET `status`='$status' WHERE `id`='$id' ");
        }
    }

    public static function create_clients($paiload)
    {
        global $wpdb;
        return $wpdb->get_results("INSERT INTO {$wpdb->prefix}bohiques_clients(`url`, `token`,`status`) VALUES('{$paiload['url']}','{$paiload['token']}','{$paiload['status']}')", OBJECT);
    }

    public static function delete_client($id)
    {
        global $wpdb;
        return $wpdb->get_results("DELETE FROM {$wpdb->prefix}bohiques_clients WHERE id='$id'", OBJECT);
    }
}
