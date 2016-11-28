<?php

namespace StubReactiveProject\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phapp\Application\Http\CookieCollection;
use Phapp\Application\Http\MultipartRequest;
use React\EventLoop\LoopInterface;

class Index extends Controller
{
    public function indexAction()
    {
        $services = [
            'event-loop' => $this->di->getShared('event-loop') instanceof LoopInterface,
            'cookies'    => $this->di->getShared('cookies') instanceof CookieCollection,
            'request'    => $this->di->getShared('request') instanceof MultipartRequest,
        ];

        /**
         * session, cookie, native headers test!!
         */

        $request = [
            'getScheme'            => $this->request->getScheme(), // @todo https!??
            'getMethod'            => $this->request->getMethod(),
            'getPort'              => $this->request->getPort(), // @todo how to test?
            'isSoap'               => $this->request->isSoap(),
            'isAjax'               => $this->request->isAjax(),
            'isGet'                => $this->request->isGet(),
            'isPost'               => $this->request->isPost(),
            'isPut'                => $this->request->isPut(),
            'isDelete'             => $this->request->isDelete(), // @todo test with data
            'isPatch'              => $this->request->isPatch(), // @todo test with data
            'isHead'               => $this->request->isHead(),
            'isOptions'            => $this->request->isOptions(), // @todo test with data
            'isTrace'              => $this->request->isTrace(), // @todo test with data

            'hasPostArgFoo'        => $this->request->hasPost('foo'),
            'hasServerArgVar'      => $this->request->hasServer('var'),
            'hasPutArgFoo'         => $this->request->hasPut('foo'),
            'hasQueryArgFoo'       => $this->request->hasQuery('foo'),
            'hasFiles'             => $this->request->hasFiles(), // @todo

            'getQuery'             => $this->request->getQuery(),
            'getPost'              => $this->request->getPost(),
            'getPut'               => $this->request->getPut(),
            'getRawBody'           => $this->request->getRawBody(),
            'getHeaders'           => $this->request->getHeaders(),

            'getJsonRawBody'       => $this->request->getJsonRawBody(true), // @todo
            'getUploadedFiles'     => $this->request->getUploadedFiles(), // @todo

            'headerArgFoo'         => $this->request->getHeader('foo'),
            'serverArgVar'         => $this->request->getServer('var'),
            'queryArgFoo'          => $this->request->getQuery('foo'),
            'postArgFoo'           => $this->request->getPost('foo'),
            'putArgFoo'            => $this->request->getPut('foo'),

            'getAcceptableContent' => $this->request->getAcceptableContent(), // @todo
            'getBasicAuth'         => $this->request->getBasicAuth(), // @todo
            'getBestAccept'        => $this->request->getBestAccept(), // @todo
            'getBestCharset'       => $this->request->getBestCharset(), // @todo
            'getClientAddress'     => $this->request->getClientAddress(), // @todo
            'getClientCharsets'    => $this->request->getClientCharsets(), // @todo
            'getBestLanguage'      => $this->request->getBestLanguage(), // @todo
            'getContentType'       => $this->request->getContentType(), // @todo
            'getDigestAuth'        => $this->request->getDigestAuth(), // @todo
            'getHttpHost'          => $this->request->getHttpHost(), // @todo
            'getServerAddress'     => $this->request->getServerAddress(), // @todo check if its possible
            'getServerName'        => $this->request->getServerName(),
            'getLanguages'         => $this->request->getLanguages(), // @todo
            'getURI'               => $this->request->getURI(),
            'getUserAgent'         => $this->request->getUserAgent(),
        ];

        if (isset($request['getHeaders']['Referer'])) {
            unset($request['getHeaders']['Referer']);
        }

        $response = new Response;
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode(200, 'OK');
        $response->setJsonContent(['services' => $services, 'requestData' => $request]);

        return $response;
    }

    public function refererAction()
    {
        $response = new Response;
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode(200, 'OK');
        $response->setJsonContent(['httpReferer' => $this->request->getHTTPReferer()]);

        return $response;
    }
}
