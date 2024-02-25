<?php
class Installer {

    public static function postCreate() {

        preg_match("/.*\/(?'vendor'.*)\/(?'bundle'.*)$/", __DIR__, $matches);

        $vendor_camel = self::toCamelCase($matches["vendor"]);
        $bundle_camel = self::toCamelCase($matches["bundle"]);
        $vendor_snake = strtolower($vendor_camel);
        $bundle_snake = strtolower($bundle_camel);


        self::setJson($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setConfigRoutes($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setConfigServices($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setContaoManager($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setFrontendController($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);
        self::setExtension($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel);


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
            _controller: $vendor_camel\\$bundle_camel\\Controller\\FrontendController::test
            _scope: frontend
        EOT;

        file_put_contents(__DIR__ . "/src/config/routes.yml", $content);


    }

    public static function setConfigServices($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $content = <<<EOT
        services:
          $vendor_camel\\$bundle_camel\\Controller\\FrontendController:
            public: true
            arguments:
              - '@doctrine.dbal.default_connection'
              - 'Some other argument'
        EOT;

        file_put_contents(__DIR__ . "/src/config/services.yml", $content);


    }


    public static function setContaoManager($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $content =<<<EOT
        <?php

        namespace $vendor_camel\\$bundle_camel\\ContaoManager;

        use $vendor_camel\\$bundle_camel\\$vendor_camel{$bundle_camel}Bundle;
        use Contao\\CoreBundle\\ContaoCoreBundle;
        use Contao\\ManagerPlugin\\Bundle\\BundlePluginInterface;
        use Contao\\ManagerPlugin\\Bundle\\Config\\BundleConfig;
        use Contao\\ManagerPlugin\\Routing\\RoutingPluginInterface;
        use Contao\\ManagerPlugin\\Bundle\\Parser\\ParserInterface;
        use Symfony\\Component\\Config\\Loader\\LoaderResolverInterface;
        use Symfony\\Component\\HttpKernel\\KernelInterface;

        class Plugin implements BundlePluginInterface, RoutingPluginInterface
        {
            public function getBundles(ParserInterface \$parser)
            {
                return [
                    BundleConfig::create($vendor_camel{$bundle_camel}Bundle::class)
                        ->setLoadAfter([ContaoCoreBundle::class]),
                ];
            }

            public function getRouteCollection(LoaderResolverInterface \$resolver, KernelInterface \$kernel)
            {
                \$file = __DIR__ . '/..//config/routes.yml';
                return \$resolver->resolve(\$file)->load(\$file);
            }
        }
        EOT;

        file_put_contents(__DIR__ . "/src/ContaoManager/Plugin.php", $content);

    }

    public static function setFrontendController($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $content =<<<EOT
        <?php
        namespace $vendor_camel\\$bundle_camel\\Controller;
        use Doctrine\\DBAL\\Connection;
        use Symfony\\Component\\HttpFoundation\\Response;
        
        class FrontendController {
        
            private Connection \$dbal;
            private string \$name;
        
            public function __construct(Connection \$dbal, string \$name)
            {
                \$this->dbal = \$dbal;
                \$this->name = \$name;
            }
        
            public function test() {
                return new Response("Your 2nd argument: '" . \$this->name . "'");
            }
        
        
        }

        EOT;

        file_put_contents(__DIR__ . "/src/Controller/FrontendController.php", $content);

    }
    
    public static function setExtension($vendor_snake, $bundle_snake, $vendor_camel, $bundle_camel) {

        $content =<<<EOT
        <?php
        namespace $vendor_camel\\$bundle_camel\\DependencyInjection;
        
        use Symfony\\Component\\Config\\FileLocator;
        use Symfony\\Component\\DependencyInjection\\ContainerBuilder;
        use Symfony\\Component\\DependencyInjection\\Extension\\Extension;
        use Symfony\\Component\\DependencyInjection\\Loader\\YamlFileLoader;
        
        class $vendor_camel{$bundle_camel}Extension extends Extension
        {
            public function load(array \$configs, ContainerBuilder \$container): void
            {
                \$loader = new YamlFileLoader(\$container, new FileLocator(__DIR__ . '/../config'));
                \$loader->load('services.yml');
            }
        }

        EOT;

        file_put_contents(__DIR__ . "/src/DependencyInjection/$bundle_camel{$vendor_camel}Extension.php", $content);

    }

}


Installer::postCreate();