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
        /*
        if (!$this->di->getShared('event-loop') instanceof LoopInterface) {
            throw new \UnexpectedValueException('EventLoop service must an instance of LoopInterface');
        }
        if (!$this->di->getShared('cookies') instanceof CookieCollection) {
            throw new \UnexpectedValueException('Cookies service must be an instance of CookieCollection');
        }
        if (!$this->di->getShared('request') instanceof MultipartRequest) {
            throw new \UnexpectedValueException('Request service must be an instance of MultipartRequest');
        }
        if (!$this->di->getShared('response') instanceof ResponseProxy) {
            throw new \UnexpectedValueException('Response service must be an instance of ResponseProxy');
        }
        */

        $request = [
            'getScheme'                      => $this->request->getScheme(),
            'getMethod'                      => $this->request->getMethod(),
            'getPort'                        => $this->request->getPort(),
            'isSoap'                         => $this->request->isSoap(),
            'isAjax'                         => $this->request->isAjax(),
            'isGet'                          => $this->request->isGet(),
            'isPost'                         => $this->request->isPost(),
            'isPut'                          => $this->request->isPut(),
            'isDelete'                       => $this->request->isDelete(),
            'isPatch'                        => $this->request->isPatch(),
            'isHead'                         => $this->request->isHead(),
            'isOptions'                      => $this->request->isOptions(),
            'isTrace'                        => $this->request->isTrace(),

            'hasPost'                        => $this->request->hasPost('foo'),
            'hasServer'                      => $this->request->hasServer('foo'),
            'hasPut'                         => $this->request->hasPut('foo'),
            'hasQuery'                       => $this->request->hasQuery('foo'),
            'hasFiles'                       => $this->request->hasFiles(),

            'getQuery'                       => $this->request->getQuery(),
            'getPost'                        => $this->request->getPost(),
            'getPut'                         => $this->request->getPut(),
            'getRawBody'                     => $this->request->getRawBody(),
            'getHeaders'                     => $this->request->getHeaders(),

            'getJsonRawBody'                 => $this->request->getJsonRawBody(),
            'getUploadedFiles'               => $this->request->getUploadedFiles(),

            'header'                         => $this->request->getHeader('foo'),
            'server'                         => $this->request->getServer('foo'),
            'query'                          => $this->request->getQuery('foo'),
            'post'                           => $this->request->getPost('foo'),
            'put'                            => $this->request->getPut('foo'),

            'isValidHttpMethod'              => $this->request->isValidHttpMethod('foo'),
            'getHttpMethodParameterOverride' => $this->request->getHttpMethodParameterOverride(),
            'isStrictHostCheck'              => $this->request->isStrictHostCheck(),

            'getAcceptableContent'           => $this->request->getAcceptableContent(),
            'getBasicAuth'                   => $this->request->getBasicAuth(),
            'getBestAccept'                  => $this->request->getBestAccept(),
            'getBestCharset'                 => $this->request->getBestCharset(),
            'getClientAddress'               => $this->request->getClientAddress(),
            'getClientCharsets'              => $this->request->getClientCharsets(),
            'getBestLanguage'                => $this->request->getBestLanguage(),
            'getContentType'                 => $this->request->getContentType(),
            'getDigestAuth'                  => $this->request->getDigestAuth(),
            'getHttpHost'                    => $this->request->getHttpHost(),
            'getHTTPReferer'                 => $this->request->getHTTPReferer(),
            'getServerAddress'               => $this->request->getServerAddress(),
            'getServerName'                  => $this->request->getServerName(),
            'getLanguages'                   => $this->request->getLanguages(),
            'getURI'                         => $this->request->getURI(),
            'getUserAgent'                   => $this->request->getUserAgent(),
        ];

        $response = new Response;
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode(200, 'OK');
        $response->setJsonContent($request);

        return $response;
    }
}
