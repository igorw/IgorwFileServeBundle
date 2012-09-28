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
        $this->fullFilename = $this->baseDir.'/'.$filename;
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
        $this->options['request'] = isset($this->options['request']) ? $this->options['request'] : null;
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
        $response->headers->set('Content-Disposition', $this->resolveDispositionHeader($this->options));
    }

    protected function resolveDispositionHeader(array $options)
    {
        $disposition = $this->options['inline'] ? 'inline' : 'attachment';
        $filename = $this->options['serve_filename'];
        $request = $this->options['request'];

        return "$disposition; ".$this->resolveDispositionHeaderFilename($filename, $request);
    }

    /**
     * Algorithm inspired by phpBB3
     */
    protected function resolveDispositionHeaderFilename($filename, $request)
    {
        $userAgent = !is_null($request) ? $request->headers->get('User-Agent') : null;

        if (!$userAgent || preg_match('#MSIE|Safari|Konqueror#', $userAgent)) {
            return "filename=".rawurlencode($filename);
        }

        return "filename*=UTF-8''".rawurlencode($filename);
    }
}
