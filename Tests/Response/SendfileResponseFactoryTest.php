<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;

class SendfileResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createShouldSetXAccelRedirectHeader()
    {
        $factory = new SendfileResponseFactory(__DIR__.'/../Fixtures', new Request());
        $response = $factory->create('internet.txt', 'application/zip');

        $this->assertSame(__DIR__.'/../Fixtures/internet.txt', $response->headers->get('X-Accel-Redirect'));
    }
}
