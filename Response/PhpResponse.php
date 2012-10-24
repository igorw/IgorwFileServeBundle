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
        $file = fopen($this->filename, 'rb');
        $out = fopen('php://output', 'wb');

        stream_copy_to_stream($file, $out);

        fclose($out);
        fclose($file);
    }
}
