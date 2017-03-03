<?php
namespace MiSource\ImportBundle\Util;

class Util
{
    public static function log($messageToLog)
    {
        $timeStamp = date('D M j G:i:s Y');
        error_log('[' . $timeStamp . '] : ' . $messageToLog . PHP_EOL, 3, '/tmp/logz');
    }
}