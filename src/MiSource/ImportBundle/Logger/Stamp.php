<?php
namespace MiSource\ImportBundle\Logger;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class Stamp
{

    private $fileName;

    /**
     * @return mixed
     */
    public function getFileName()
    {
        $this->fileName = 'stash_' . date('d.m.Y');
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function write($messageToLog)
    {
        $timeStamp = date('D M j G:i:s Y');
        error_log('[' . $timeStamp . '] : ' . $messageToLog . PHP_EOL, 3, $this->checkCreateFile());
    }


    public function checkCreateFile()
    {
        $path =  '/tmp/stamp';
        $fullPath =  $path .'/'. $this->getFileName();

        if(file_exists($fullPath)) {
           return $fullPath;
        }

        $fileSystem = new Filesystem();
        if(!$fileSystem->exists($fullPath)) {

            if(! $fileSystem->exists($path)) {

                try {

                    $fileSystem->mkdir($path);

                } catch (IOExceptionInterface $e) {
                    echo "An error occurred while creating your directory at ".$e->getPath();
                }
            }

            $fileSystem->touch($fullPath);
        }
        return $fullPath;
    }
}