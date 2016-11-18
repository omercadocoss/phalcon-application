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

namespace Phapp\Application\Http;

use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

class ResponseProxy implements ResponseInterface
{
    /** @var Response */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function setDI($dependencyInjector)
	{
		$this->response->setDI($dependencyInjector);
	}

    public function getDI()
	{
        return $this->response->getDI();
    }

    public function setStatusCode($code, $message = null)
    {
        $this->response->setStatusCode($code, $message);
        return $this;
    }

    public function getStatusCode()
	{
		return $this->response->getHeaders()->get("Status");
	}

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function setHeader($name, $value)
    {
        $this->response->setHeader($name, $value);
        return $this;
    }

    public function setHeaders(Response\HeadersInterface $headers)
	{
        $this->response->setHeaders($headers);
        return $this;
	}

    public function setCookies(Response\CookiesInterface $cookies)
	{
        $this->response->setCookies($cookies);
		return $this;
	}

    public function getCookies()
    {
        return $this->response->getCookies();
    }

    public function setRawHeader($header)
    {
        $this->response->setRawHeader($header);
        return $this;
    }

    public function resetHeaders()
    {
        $this->response->resetHeaders();
        return $this;
    }

    public function setExpires(\DateTime $datetime)
    {
        $this->response->setExpires($datetime);
        return $this;
    }

    public function setNotModified()
    {
        $this->response->setNotModified();
        return $this;
    }

    public function setLastModified(\DateTime $datetime)
    {
        $this->response->setLastModified($datetime);
        return $this;
    }

    public function setCache($minutes)
    {
        $this->response->setCache($minutes);
        return $this;
    }

    public function setContentType($contentType, $charset = null)
    {
        $this->response->setContentType($contentType, $charset);
        return $this;
    }

    public function setContentLength($contentLength)
    {
        $this->response->setContentLength($contentLength);
        return $this;
    }

    public function setEtag($eTag)
    {
        $this->response->setEtag($eTag);
        return $this;
    }

    public function redirect($location = null, $externalRedirect = false, $statusCode = 302)
    {
        $this->response->redirect($location, $externalRedirect, $statusCode);
        return $this;
    }

    public function setContent($content)
    {
        $this->response->setContent($content);
        return $this;
    }

    public function setJsonContent($content)
    {
        $this->response->setJsonContent($content);
        return $this;
    }

    public function appendContent($content)
    {
        $this->response->appendContent($content);
        return $this;
    }

    public function getContent()
    {
        return $this->response->getContent();
    }

    public function sendHeaders()
    {
        // do nothing
        return $this;
    }

    public function sendCookies()
    {
        // do nothing
        return $this;
    }

    public function send()
    {
        // do nothing
        return $this;
    }

    public function isSent()
	{
		return $this->response->isSent();
	}

    public function setFileToSend($filePath, $attachmentName = null)
    {
        $this->response->setFileToSend($filePath, $attachmentName);
        return $this;
    }
}
