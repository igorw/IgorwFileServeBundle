<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PhpResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideRequestsAndContentDisposition
     */
    public function createShouldReturnResponseWithRequestSpecificContentDisposition($disposition, $requestStack)
    {
        $factory = new PhpResponseFactory(__DIR__.'/../Fixtures', $requestStack);

        $options = array(
            'serve_filename'    => 'internet.zip',
            'inline'            => false,
        );
        $response = $factory->create('internet.txt', 'application/zip', $options);

        $this->assertSame($disposition, $response->headers->get('Content-Disposition'));
    }

    /** @test */
    public function createWithRelativePath()
    {
        $requestStack = $this->createRequestStack();
        $factory = new PhpResponseFactory(__DIR__.'/../Fixtures', $requestStack);

        $response = $factory->create('internet.txt', 'text/plain');

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertSame("the internets\n", $output);
    }

    /** @test */
    public function createWithAbsolutePath()
    {
        $requestStack = $this->createRequestStack();
        $factory = new PhpResponseFactory(__DIR__.'/../Fixtures', $requestStack);

        $response = $factory->create(__DIR__.'/../Fixtures/internet.txt', 'text/plain', array(
            'absolute_path' => true,
        ));

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertSame("the internets\n", $output);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function createWithNonExistentPathShouldThrowException()
    {
        $requestStack = $this->createRequestStack();
        $factory = new PhpResponseFactory(__DIR__.'/../Fixtures', $requestStack);

        $response = $factory->create(__DIR__.'/../Fixtures/missing.txt', 'text/plain');
    }

    public function provideRequestsAndContentDisposition()
    {
        return array(
            'user agent without UTF-8 filename support' => array(
                'attachment; filename=internet.zip',
                $this->createRequestStackWithUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.11 (KHTML, like Gecko) Ubuntu/12.04 Chromium/20.0.1132.47 Chrome/20.0.1132.47 Safari/536.11')
            ),
            'user agent with UTF-8 filename support' => array(
                'attachment; filename*=UTF-8\'\'internet.zip',
                $this->createRequestStackWithUserAgent('Yeti/1.0 (NHN Corp.; http://help.naver.com/robots/)')
            ),
        );
    }

    /**
     * @param string $userAgent
     *
     * @return RequestStack
     */
    private function createRequestStackWithUserAgent($userAgent)
    {
        $requestStack = $this->createRequestStack();
        $requestStack->getCurrentRequest()->headers->set('User-Agent', $userAgent);

        return $requestStack;
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
