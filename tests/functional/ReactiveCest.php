<?php

class ReactiveCest
{
    private $expectedRequestData = [];

    /**
     * @param FunctionalTester $tester
     */
    public function testServiceAwareness(FunctionalTester $tester)
    {
        $tester->sendGet('/');
        $services = json_decode($tester->grabResponse(), true)['services'];

        $tester->assertEquals(['event-loop' => true, 'cookies' => true, 'request' => true], $services);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function testBasicRequest(FunctionalTester $tester)
    {
        $tester->sendGet('/');

        $tester->seeResponseIsJson();
        $tester->seeHttpHeader('content-type', 'application/json; charset=UTF-8');
        $tester->canSeeResponseCodeIs(200);

        $this->setExpectedRequestData(['isGet' => true]);

        $requestData = json_decode($tester->grabResponse(), true)['requestData'];
        $tester->assertEquals($this->expectedRequestData, $requestData);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function testGetRequest(FunctionalTester $tester)
    {
        $tester->sendGet('/?foo=123&bar=baz');
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isGet'          => true,
            'hasQueryArgFoo' => true,
            'getQuery'       => ['foo' => '123', 'bar' => 'baz'],
            'queryArgFoo'    => '123',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        $tester->sendGet('/?foo=4&bar=foo');
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isGet'          => true,
            'hasQueryArgFoo' => true,
            'getQuery'       => ['foo' => '4', 'bar' => 'foo'],
            'queryArgFoo'    => '4',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        $tester->sendGet('/');
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData(['isGet' => true]);

        $tester->assertEquals($this->expectedRequestData, $requestData);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function testPostRequest(FunctionalTester $tester)
    {
        $tester->sendPost('/', ['foo' => '123', 'bar' => 'baz']);
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isPost'         => true,
            'getMethod'      => 'POST',
            'hasPostArgFoo'  => true,
            'getPost'        => ['foo' => '123', 'bar' => 'baz'],
            'postArgFoo'     => '123',
            'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '15'],
            'getContentType' => 'application/x-www-form-urlencoded',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        $tester->sendPost('/', ['foo' => '4', 'bar' => 'bla']);
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isPost'         => true,
            'getMethod'      => 'POST',
            'hasPostArgFoo'  => true,
            'getPost'        => ['foo' => '4', 'bar' => 'bla'],
            'postArgFoo'     => '4',
            'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '13'],
            'getContentType' => 'application/x-www-form-urlencoded',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        /* @todo if post data not exist react is waiting infinitly for that!!
         * $tester->sendPost('/');
         * $requestData = json_decode($tester->grabResponse(), true)['requestData'];
         *
         * $this->setExpectedRequestData([
         * 'isPost'         => true,
         * 'getMethod'      => 'POST',
         * 'hasPostArgFoo'  => false,
         * 'getPost'        => [],
         * 'postArgFoo'     => null,
         * 'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '13'],
         * 'getContentType' => 'application/x-www-form-urlencoded',
         * ]);
         *
         * $tester->assertEquals($this->expectedRequestData, $requestData);
         */
    }

    /**
     * @param FunctionalTester $tester
     */
    public function testPutRequest(FunctionalTester $tester)
    {
        $tester->sendPUT('/', ['foo' => '123', 'bar' => 'baz']);
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isPut'          => true,
            'getMethod'      => 'PUT',
            'hasPutArgFoo'   => true,
            'getPut'         => ['foo' => '123', 'bar' => 'baz'],
            'putArgFoo'      => '123',
            'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '15'],
            'getContentType' => 'application/x-www-form-urlencoded',
            'getRawBody'     => 'foo=123&bar=baz',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        $tester->sendPUT('/', ['foo' => '4', 'bar' => 'bla']);
        $requestData = json_decode($tester->grabResponse(), true)['requestData'];

        $this->setExpectedRequestData([
            'isPut'          => true,
            'getMethod'      => 'PUT',
            'hasPutArgFoo'   => true,
            'getPut'         => ['foo' => '4', 'bar' => 'bla'],
            'putArgFoo'      => '4',
            'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '13'],
            'getContentType' => 'application/x-www-form-urlencoded',
            'getRawBody'     => 'foo=4&bar=bla',
        ]);

        $tester->assertEquals($this->expectedRequestData, $requestData);

        /* @todo if put data not exist react is waiting infinitly for that!!
         * $tester->sendPUT('/');
         * $requestData = json_decode($tester->grabResponse(), true)['requestData'];
         *
         * $this->setExpectedRequestData([
         * 'isPut'          => true,
         * 'getMethod'      => 'PUT',
         * 'hasPutArgFoo'   => true,
         * 'getPut'         => [],
         * 'putArgFoo'      => null,
         * 'getHeaders'     => ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => '13'],
         * 'getContentType' => 'application/x-www-form-urlencoded',
         * 'getRawBody'     => '',
         * ]);
         *
         * $tester->assertEquals($this->expectedRequestData, $requestData);
         */
    }

    private function setExpectedRequestData(array $overwrites = [])
    {
        $this->expectedRequestData = [
            'getScheme'            => 'http',
            'getMethod'            => 'GET',
            'getPort'              => 80,
            'isSoap'               => false,
            'isAjax'               => false,
            'isGet'                => false,
            'isPost'               => false,
            'isPut'                => false,
            'isDelete'             => false,
            'isPatch'              => false,
            'isHead'               => false,
            'isOptions'            => false,
            'isTrace'              => false,
            'hasPostArgFoo'        => false,
            'hasServerArgFoo'      => false,
            'hasPutArgFoo'         => false,
            'hasQueryArgFoo'       => false,
            'hasFiles'             => 0,
            'getQuery'             => [],
            'getPost'              => [],
            'getPut'               => [],
            'getRawBody'           => '',
            'getHeaders'           => ['User-Agent' => 'Symfony2 BrowserKit', 'Host' => 'react'],
            'getJsonRawBody'       => null,
            'getUploadedFiles'     => [],
            'headerArgFoo'         => '',
            'serverArgFoo'         => null,
            'queryArgFoo'          => null,
            'postArgFoo'           => null,
            'putArgFoo'            => null,
            'getAcceptableContent' => [],
            'getBasicAuth'         => null,
            'getBestAccept'        => '',
            'getBestCharset'       => '',
            'getClientAddress'     => '172.17.0.3',
            'getClientCharsets'    => [],
            'getBestLanguage'      => '',
            'getContentType'       => null,
            'getDigestAuth'        => [],
            'getHttpHost'          => 'react',
            'getServerAddress'     => '127.0.0.1',
            'getServerName'        => 'react',
            'getLanguages'         => [],
            'getURI'               => '/',
            'getUserAgent'         => 'Symfony2 BrowserKit',
        ];

        $this->expectedRequestData = array_replace_recursive($this->expectedRequestData, $overwrites);
    }
}
