<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseFactory
{
    protected $baseDir;
    protected $skipFileExists;
    protected $requestStack;
    protected $fullFilename;
    protected $contentType;
    protected $options;

    public function __construct($baseDir, RequestStack $requestStack, $skipFileExists = false)
    {
        $this->baseDir = $baseDir;
        $this->requestStack = $requestStack;
        $this->skipFileExists = $skipFileExists;
    }

    public function create($filename, $contentType = 'application/octet-stream', $options = array())
    {
        $this->options = $options;
        $this->fullFilename = (!empty($this->options['absolute_path'])) ? $filename : $this->baseDir.'/'.$filename;
        $this->contentType = $contentType;

        if (!$this->skipFileExists && !is_readable($this->fullFilename)) {
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
     * @param string $filename
     *
     * @return string
     */
    protected function resolveDispositionHeaderFilename($filename)
    {
        if ($this->clientSupportsUtf8Filename()) {
            $dispositionHeaderFilename = "filename*=UTF-8''".rawurlencode($filename);
        } else {
            $dispositionHeaderFilename = "filename=".rawurlencode($filename);
        }

        return $dispositionHeaderFilename;
    }

    /**
     * Algorithm inspired by phpBB3
     *
     * @throws \LogicException
     *
     * @return bool
     */
    private function clientSupportsUtf8Filename()
    {
        $request = $this->requestStack->getCurrentRequest();
        if (is_null($request)) {
            throw new \LogicException('Current request is not available');
        }

        $userAgent = $request->headers->get('User-Agent');

        if (preg_match('#MSIE|Safari|Konqueror#', $userAgent)) {
            return false;
        }

        return true;
    }
}
