<?php

namespace Igorw\FileServeBundle\Response;

class PhpResponseFactory extends AbstractResponseFactory
{
    protected function createResponse()
    {
        $response = new PhpResponse();
        $response->setFilename($this->filename);
        return $response;
    }

    protected function setResponseHeaders(Response $response)
    {
        parent::setResponseHeaders($response);

        $response->headers->set('Content-Length', filesize($this->filename));
    }
}
