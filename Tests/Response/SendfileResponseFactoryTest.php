<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SendfileResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createShouldSetXAccelRedirectHeader()
    {
        $requestStack = $this->createRequestStack();
        $factory = new SendfileResponseFactory(__DIR__.'/../Fixtures', $requestStack);
        $response = $factory->create('internet.txt', 'application/zip');

        $this->assertSame(__DIR__.'/../Fixtures/internet.txt', $response->headers->get('X-Accel-Redirect'));
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
