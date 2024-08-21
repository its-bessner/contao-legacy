<?php


namespace ItsBessner\ContaoLegacy;


use \Exception;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ItsBessnerContaoLegacyBundle extends Bundle {
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
