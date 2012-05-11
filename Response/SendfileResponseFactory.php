<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class SendfileResponseFactory extends AbstractResponseFactory
{
    protected function setResponseHeaders(Response $response)
    {
        parent::setResponseHeaders($response);
        $response->headers->set('X-Accel-Redirect', $this->fullFilename);
    }
}
