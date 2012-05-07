<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class XsendfileResponseFactory extends AbstractResponseFactory
{
    protected function setResponseHeaders(Response $response)
    {
        parent::setResponseHeaders($response);

        // $response->headers->set('X-Sendfile', realpath($this->fullFilename));
        $response->headers->set('X-Sendfile', $this->fullFilename);
    }
}
