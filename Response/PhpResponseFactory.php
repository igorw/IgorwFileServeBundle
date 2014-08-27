<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class PhpResponseFactory extends AbstractResponseFactory
{
    protected function createResponse()
    {
        $response = new PhpResponse();
        $response->setFilename($this->fullFilename);
        return $response;
    }

    protected function setResponseHeaders(Response $response)
    {
        parent::setResponseHeaders($response);

        $fileSize = filesize($this->fullFilename);
        $response->headers->set('Content-Length', $fileSize);
        if (strstr($response->headers->get('content-type'), 'video')) {
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Content-Range', 'bytes 0-'.($fileSize-1)."/$fileSize");
        }
    }
}
