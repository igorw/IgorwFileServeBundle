<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseFactory
{
    protected $baseDir;
    protected $fullFilename;
    protected $contentType;
    protected $options;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function create($filename, $contentType = 'application/octet-stream', $options = array())
    {
        $this->fullFilename = $options['absolute'] ? $filename : $this->baseDir . '/' . $filename;
        $this->contentType = $contentType;
        $this->options = $options;

        if (!is_readable($this->fullFilename)) {
            throw new \InvalidArgumentException(sprintf("Provided filename '%s' for %s is not readable.", $this->fullFilename, __METHOD__));
        }

        $this->parseOptions();

        $response = $this->createResponse();
        $this->setResponseHeaders($response);

        return $response;
    }

    protected function parseOptions()
    {
        $this->options['serve_filename'] = isset($this->options['serve_filename']) ? $this->options['serve_filename'] : basename($this->fullFilename);
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
