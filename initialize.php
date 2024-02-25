<?php
class Installer {

    public static function postCreate() {

        preg_match("/.*\/(?'vendor'.*)\/(?'bundle'.*)$/", __DIR__, $matches);

        $vendor = self::toCamelCase($matches["vendor"]);
        $bundle = self::toCamelCase($matches["bundle"]);


        $json = json_decode(file_get_contents(__DIR__ . "/composer.json"));
        unset($json->scripts);
        $json->type = "contao-bundle";
        $json->name = $matches["vendor"] . "/" . $matches["bundle"];
        $json->description = "";
        $json->extra["contao-manager-plugin"] = "$vendor\\$bundle\\ContaoManager\\Plugin";
        $json->autoload = (object) ["psr-4" => (object) [$vendor . "\\" . $bundle . "\\" => "src/"]];

        file_put_contents(__DIR__ . "/composer.json", json_encode($json, JSON_UNESCAPED_SLASHES));




        var_dump($vendor, $bundle);

        print str_repeat(PHP_EOL, 2);

    }

    public static function toCamelCase($input) {
        $parts = preg_split("/[-_]/", $input);
        foreach($parts as &$part) {
            $part = ucfirst($part);
        }
        return implode('', $parts);
    }

}

Installer::postCreate();