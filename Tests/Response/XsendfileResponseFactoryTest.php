<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class XsendfileResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createShouldSetXSendfileHeader()
    {
        $requestStack = $this->createRequestStack();
        $factory = new XsendfileResponseFactory(__DIR__.'/../Fixtures', $requestStack);
        $response = $factory->create('internet.txt', 'application/zip');

        $this->assertSame(__DIR__.'/../Fixtures/internet.txt', $response->headers->get('X-Sendfile'));
    }

    /**
     * @return RequestStack
     */
    private function createRequestStack()
    {
        $request = Request::create('/');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
