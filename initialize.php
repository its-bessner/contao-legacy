<?php
class Installer {

    public static function postCreate() {

        preg_match("/.*\/(?'vendor'.*)\/(?'bundle'.*)$/", __DIR__, $matches);

        $vendor_snake = $matches["vendor"];
        $bundle_snake = $matches["bundle"];
        $vendor_camel = self::toCamelCase($matches["vendor"]);
        $bundle_camel = self::toCamelCase($matches["bundle"]);


        self::setJson($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setConfigRoutes($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);


        print str_repeat(PHP_EOL, 2);



    }

    public static function toCamelCase($input) {
        $parts = preg_split("/[-_]/", $input);
        foreach($parts as &$part) {
            $part = ucfirst($part);
        }
        return implode('', $parts);
    }

    public static function setJson($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $json = json_decode(file_get_contents(__DIR__ . "/composer.json"));
        unset($json->scripts);
        $json->type = "contao-bundle";
        $json->name = $vendor_snake . "/" . $bundle_snake;
        $json->description = "";
        $json->extra->{'contao-manager-plugin'} = "$vendor_camel\\$bundle_camel\\ContaoManager\\Plugin";
        $json->autoload = (object) ["psr-4" => (object) [$vendor_camel . "\\" . $bundle_camel . "\\" => "src/"]];

        file_put_contents(__DIR__ . "/composer.json", json_encode($json, JSON_UNESCAPED_SLASHES));



    }

    public static function setConfigRoutes($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $content = <<<EOT
        $vendor_snake{$bundle_snake}_test:
          path: $vendor_snake$bundle_snake/test
          defaults:
            _controller: $vendor_camel\\$bundle_camel\\Controller\\FrontendController::test\
            _scope: frontend
        EOT;

        file_put_contents(__DIR__ . "/src/config/routes.yml", $content);


    }

}


Installer::postCreate();