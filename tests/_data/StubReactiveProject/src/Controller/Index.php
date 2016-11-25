<?php

namespace StubReactiveProject\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phapp\Application\Http\CookieCollection;
use Phapp\Application\Http\MultipartRequest;
use Phapp\Application\Http\ResponseProxy;
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
            'isSoap'               => $this->request->isSoap(), // @todo
            'isAjax'               => $this->request->isAjax(), // @todo
            'isGet'                => $this->request->isGet(),
            'isPost'               => $this->request->isPost(),
            'isPut'                => $this->request->isPut(),
            'isDelete'             => $this->request->isDelete(), // @todo
            'isPatch'              => $this->request->isPatch(), // @todo
            'isHead'               => $this->request->isHead(), // @todo
            'isOptions'            => $this->request->isOptions(), // @todo
            'isTrace'              => $this->request->isTrace(), // @todo

            'hasPostArgFoo'        => $this->request->hasPost('foo'),
            'hasServerArgFoo'      => $this->request->hasServer('foo'), // @todo
            'hasPutArgFoo'         => $this->request->hasPut('foo'),
            'hasQueryArgFoo'       => $this->request->hasQuery('foo'),
            'hasFiles'             => $this->request->hasFiles(), // @todo

            'getQuery'             => $this->request->getQuery(),
            'getPost'              => $this->request->getPost(),
            'getPut'               => $this->request->getPut(),
            'getRawBody'           => $this->request->getRawBody(),
            'getHeaders'           => $this->request->getHeaders(), // @todo

            'getJsonRawBody'       => $this->request->getJsonRawBody(), // @todo
            'getUploadedFiles'     => $this->request->getUploadedFiles(), // @todo

            'headerArgFoo'         => $this->request->getHeader('foo'),
            'serverArgFoo'         => $this->request->getServer('foo'), // @todo
            'queryArgFoo'          => $this->request->getQuery('foo'),
            'postArgFoo'           => $this->request->getPost('foo'),
            'putArgFoo'            => $this->request->getPut('foo'),

            // @todo do unit tests for that
            //'isValidHttpMethod'              => $this->request->isValidHttpMethod('foo'),
            //'getHttpMethodParameterOverride' => $this->request->getHttpMethodParameterOverride(),
            //'isStrictHostCheck'              => $this->request->isStrictHostCheck(),

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
            // @todo do dedicated tests for that
            //'getHTTPReferer'       => $this->request->getHTTPReferer(),
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
}
