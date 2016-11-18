<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Marco Muths
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types = 1);

namespace Phapp\Application\Adapter\React;

use Phapp\Application\Http\ResponseProxy;
use Phapp\Application\ResponseInterface;
use React\Http\Response as ReactResponse;

class Response implements ResponseInterface
{
    /** @var ReactResponse */
    private $response;

    /** @var array */
    private $headers = [];

    /** @var int */
    private $httpStatusCode = 500;

    /** @var string */
    private $content = '';

    /** @var \Phalcon\Http\CookieInterface[] */
    private $cookies = [];

    /**
     * @param ReactResponse $response
     */
    public function __construct(ReactResponse $response)
    {
        $this->response = $response;
    }

    public function send($result)
    {
        $this->readNativeHeaders();
        if ($result instanceof ResponseProxy) {
            $this->read($result);
        }

        $this->closeOpenedSession();
        $this->flushHeaders();
        $this->writeCookies();

        $this->response->writeHead($this->httpStatusCode, $this->headers);
        $this->response->end($this->content);
    }

    private function readNativeHeaders()
    {
        foreach (headers_list() as $header) {
            if (false !== $pos = strpos($header, ':')) {
                $name = substr($header, 0, $pos);
                $value = trim(substr($header, $pos + 1));
                if (isset($this->headers[$name])) {
                    if (!is_array($this->headers[$name])) {
                        $this->headers[$name] = [$this->headers[$name]];
                    }
                    $this->headers[$name][] = $value;
                } else {
                    $this->headers[$name] = $value;
                }
            }
        }
    }

    private function read(ResponseProxy $response)
    {
        /** @var \Phalcon\Http\Response\Headers $headers */
        $headers = $response->getHeaders();
        $this->headers = array_merge($this->headers, $headers->toArray());

        $this->httpStatusCode = (int) $headers->get('Status');
        $this->content = $response->getContent();

        if (!isset($this->headers['Content-Length'])) {
            $this->headers['Content-Length'] = strlen($this->content);
        }

        if ($cookies = $response->getCookies()) {
            $this->cookies = $cookies->getAll();
        }
    }

    private function closeOpenedSession()
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            session_write_close();
            session_unset();
        }
    }

    private function flushHeaders()
    {
        header_remove();
    }

    private function writeCookies()
    {
        $cookies = [];

        foreach ($this->cookies as $cookie) {
            $cookieHeader = sprintf('%s=%s', $cookie->getName(), $cookie->getValue());
            if ($cookie->getPath()) {
                $cookieHeader .= '; Path=' . $cookie->getPath();
            }
            if ($cookie->getDomain()) {
                $cookieHeader .= '; Domain=' . $cookie->getDomain();
            }
            if ($cookie->getExpiration()) {
                $cookieHeader .= '; Expires=' . gmdate('D, d-M-Y H:i:s', $cookie->getExpiration()). ' GMT';
            }
            if ($cookie->getSecure()) {
                $cookieHeader .= '; Secure';
            }
            if ($cookie->getHttpOnly()) {
                $cookieHeader .= '; HttpOnly';
            }
            $cookies[] = $cookieHeader;
        }

        if (isset($this->headers['Set-Cookie'])) {
            $this->headers['Set-Cookie'] = array_merge((array) $this->headers['Set-Cookie'], $cookies);
        } else {
            $this->headers['Set-Cookie'] = $cookies;
        }
    }
}
