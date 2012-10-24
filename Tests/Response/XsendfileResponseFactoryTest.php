<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;

class XsendfileResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createShouldSetXSendfileHeader()
    {
        $factory = new XsendfileResponseFactory(__DIR__.'/../Fixtures', new Request());
        $response = $factory->create('internet.txt', 'application/zip');

        $this->assertSame(__DIR__.'/../Fixtures/internet.txt', $response->headers->get('X-Sendfile'));
    }
}
