<?php


namespace Acme\ContaoBundle;


use \Exception;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeContaoBundleBundle extends Bundle {
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
