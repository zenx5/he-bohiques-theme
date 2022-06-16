<?php


class BohiquesUpdate
{
    public static function is_update()
    {
        $url = "https://api.github.com/repos/zenx5/he-bohiques-theme/releases";
        $client = new \GuzzleHttp\Client();
        $releases = json_decode($client->request('get', $url)->getBody()->getContents());
        $release = $releases[count($releases) - 1];
        $remoteVersion = intval(str_replace(["v", "."], ["", ""], $release->tag_name));
        $currentVersion = intval(str_replace(["v", "."], ["", ""], BohiquesThemeChild::$version));
        return !($remoteVersion > $currentVersion);
    }
}
