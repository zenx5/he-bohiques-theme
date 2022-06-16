<?php


class BohiquesUpdate
{
    public static function check()
    {
        $currentVersion = intval(str_replace(["v", "."], ["", ""], BohiquesThemeChild::$version));
    }
}
