<?php
namespace Acme\ContaoBundle\Controller;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;

class FrontendController {

    private Connection $dbal;
    private string $name;

    public function __construct(Connection $dbal, string $name)
    {
        $this->dbal = $dbal;
        $this->name = $name;
    }

    public function test() {
        return new Response("Your 2nd argument: '" . $this->name . "'");
    }


}
