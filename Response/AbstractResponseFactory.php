<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractResponseFactory
{
    protected $baseDir;
    protected $request;
    protected $fullFilename;
    protected $contentType;
    protected $options;

    public function __construct($baseDir, Request $request)
    {
        $this->baseDir = $baseDir;
        $this->request = $request;
    }

    public function create($filename, $contentType = 'application/octet-stream', $options = array())
    {
        $this->contentType = $contentType;
        $this->options = $options;
        
        // calculate absolute or relative path
        if (array_key_exists('is_absolute_path', $this->options) && true === $this->options['is_absolute_path']) {
            $this->fullFilename = $filename;
        } else {
            $this->fullFilename = $this->baseDir.'/'.$filename;
        }

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
        $response->headers->set('Content-Disposition', $this->resolveDispositionHeader($this->options));
    }

    protected function resolveDispositionHeader(array $options)
    {
        $disposition = $this->options['inline'] ? 'inline' : 'attachment';
        $filename = $this->options['serve_filename'];

        return "$disposition; ".$this->resolveDispositionHeaderFilename($filename);
    }

    /**
     * Algorithm inspired by phpBB3
     */
    protected function resolveDispositionHeaderFilename($filename)
    {
        $userAgent = $this->request->headers->get('User-Agent');

        if (preg_match('#MSIE|Safari|Konqueror#', $userAgent)) {
            return "filename=".rawurlencode($filename);
        }

        return "filename*=UTF-8''".rawurlencode($filename);
    }
}
