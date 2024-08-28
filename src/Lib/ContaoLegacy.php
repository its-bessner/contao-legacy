<?php
namespace ItsBessner\ContaoLegacy\Lib;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
class ContaoLegacy {

    /**
     * Indicates if the software is alive.
     *
     * This method returns a boolean value indicating if the software is alive or not.
     *
     * @return bool Returns true if the software is alive, false otherwise.
     */
    static function alive() {
       return true;
    }


    /**
     * Returns whether the current request is a backend request or not.
     *
     * @return boolean True if the current request is a backend request, false otherwise.
     */
    public static function isBE()
    {
        return (System::getContainer()->get('contao.routing.scope_matcher')
            ->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
        );

    }

    public static function getSystemRoot() {
        return System::getContainer()->getParameter('kernel.project_dir');
    }



}
