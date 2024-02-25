<?php

namespace Acme\ContaoBundle\ContaoManager;

use Acme\ContaoBundle\AcmeContaoBundleBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface {
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(AcmeContaoBundleBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $file = __DIR__.'/..//config/routes.yml';
        return $resolver->resolve($file)->load($file);
    }
}
