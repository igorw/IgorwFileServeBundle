<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseFactory
{
    protected $filename;
    protected $contentType;
    protected $options;

    public function create($filename, $contentType = 'application/octet-stream', $options = array())
    {
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf("Provided filename '%s' for %s is not readable.", $filename, __METHOD__));
        }

        $this->filename = $filename;
        $this->contentType = $contentType;
        $this->options = $options;

        $this->parseOptions();

        $response = $this->createResponse();
        $this->setResponseHeaders($response);

        return $response;
    }

    protected function parseOptions()
    {
        $this->options['serve_filename'] = isset($this->options['serve_filename']) ? $this->options['serve_filename'] : basename($this->filename);
        $this->options['inline'] = isset($this->options['inline']) ? (Bool) $this->options['inline'] : true;
    }

    protected function createResponse()
    {
        $response = new Response();
        return $response;
    }

    protected function setResponseHeaders(Response $response)
    {
        $response->headers->set('Cache-Control', 'public');
        $response->headers->set('Content-Type', $this->contentType);

        $disposition = $this->options['inline'] ? 'inline' : 'attachment';
        $response->headers->set('Content-Disposition', $disposition . "; filename*=UTF-8''" . rawurlencode($this->options['serve_filename']));
    }
}
