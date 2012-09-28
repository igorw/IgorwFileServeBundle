<?php

namespace Igorw\FileServeBundle\Response;

use Symfony\Component\HttpFoundation\Request;

class PhpResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideRequestsAndContentDisposition
     */
    public function createShouldReturnResponseWithRequestSpecificContentDisposition($disposition, $request)
    {
        $factory = new PhpResponseFactory(__DIR__.'/../Fixtures');

        $options = array(
            'request'           => $request,
            'serve_filename'    => 'internet.zip',
            'inline'            => false,
        );
        $response = $factory->create('internet.txt', 'application/zip', $options);

        $this->assertSame($disposition, $response->headers->get('Content-Disposition'));
    }

    public function provideRequestsAndContentDisposition()
    {
        return array(
            array('attachment; filename=internet.zip', $this->createRequestWithUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.11 (KHTML, like Gecko) Ubuntu/12.04 Chromium/20.0.1132.47 Chrome/20.0.1132.47 Safari/536.11')),
            array('attachment; filename*=UTF-8\'\'internet.zip', $this->createRequestWithUserAgent('Yeti/1.0 (NHN Corp.; http://help.naver.com/robots/)')),
            array('attachment; filename=internet.zip', null),
        );
    }

    private function createRequestWithUserAgent($userAgent)
    {
        $request = Request::create('/');
        $request->headers->set('User-Agent', $userAgent);
        return $request;
    }
}
