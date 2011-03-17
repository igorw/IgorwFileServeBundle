<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class PhpResponse extends Response
{
    private $filename;

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function sendContent()
    {
        readfile($this->filename);
    }
}
