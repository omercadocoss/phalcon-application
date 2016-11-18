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

use Phalcon\Http\Request;
use Phalcon\Http\Request\File;
use Phapp\Application\MultipartMessageInterface;

class MultipartRequest extends Request
{
    /** @var MultipartMessageInterface */
    private $message;

    /**
     * @param MultipartMessageInterface $message
     */
    public function __construct(MultipartMessageInterface $message)
    {
        $this->message = $message;

        $this->populateRequestTime();
        $this->populateHeaders();
        $this->populateServerEnvironment();
        $this->populateQuery();
        $this->populatePost();
        $this->populateRequest();
    }

    private function populateRequestTime()
    {
        $start = microtime(true);
        $_SERVER['REQUEST_TIME'] = (int) $start;
        $_SERVER['REQUEST_TIME_FLOAT'] = $start;
    }

    private function populateCookies()
    {
        $_COOKIE = [];
        // @todo validate cookies
        if (isset($this->message->getHeaders()['Cookie'])) {
            $cookies = explode(';', $this->message->getHeaders()['Cookie']);
            foreach ($cookies as $cookie) {
                $keyValue = explode('=', trim($cookie));
                $_COOKIE[$keyValue[0]] = $keyValue[1];
                /*
                if ($keyValue[0] === session_name()) {
                    session_id($keyValue[1]);
                    session_regenerate_id(true);
                }
                */
            }
        }
    }

    private function populateHeaders()
    {
        $nonHeaders = ['AUTHORIZATION', 'HTTPS', 'COOKIE', 'REMOTE_ADDR'];
        $staticHeaders = ['X-HTTP-METHOD-OVERRIDE'];

        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0 || in_array($name, $staticHeaders)) {
                unset($_SERVER[$name]);
            }
        }

        foreach ($this->message->getHeaders() as $name => $value) {
            if (isset($_ENV[$name])) {
                continue;
            }
            $name = strtoupper($name);
            if (in_array($name, $staticHeaders)) {
                $_SERVER[$name] = $value;
            } else {
                $name = str_replace('-', '_', $name);
                if (!in_array($name, $nonHeaders)) {
                    $_SERVER['HTTP_' . $name] = $value;
                }
            }
        }
    }

    private function populateServerEnvironment()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) unset($_SERVER['CONTENT_TYPE']);
        if (isset($_SERVER['PHP_AUTH_USER'])) unset($_SERVER['PHP_AUTH_USER']);
        if (isset($_SERVER['PHP_AUTH_PW'])) unset($_SERVER['PHP_AUTH_PW']);
        if (isset($_SERVER['PHP_AUTH_DIGEST'])) unset($_SERVER['PHP_AUTH_DIGEST']);
        if (isset($_SERVER['AUTH_TYPE'])) unset($_SERVER['AUTH_TYPE']);
        if (isset($_SERVER['REMOTE_ADDR'])) unset($_SERVER['REMOTE_ADDR']);

        // $_SERVER['SERVER_PORT'] = 0;
        // $_SERVER['HTTPS'] = '';
        $_SERVER['REQUEST_METHOD'] = $this->message->getMethod();
        $_SERVER['REQUEST_URI'] = $this->message->getPath();
        $_SERVER['QUERY_STRING'] = http_build_query($this->message->getQuery());
        $_SERVER['DOCUMENT_ROOT'] = $_ENV['DOCUMENT_ROOT'] ?? getcwd();
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] ?? '';

        if (isset($this->message->getHeaders()['Content-Type'])) {
            $_SERVER['CONTENT_TYPE'] = $this->message->getHeaders()['Content-Type'];
        }

        if (isset($this->message->getHeaders()['Authorization'])) {
            $authorizationHeader = $this->message->getHeaders()['Authorization'];
            $authorizationHeaderParts = explode(' ', $authorizationHeader);
            $type = $authorizationHeaderParts[0];
            if (($type === 'Basic' || $type === 'Digest') && isset($authorizationHeaderParts[1])) {
                $credentials = base64_decode($authorizationHeaderParts[1]);
                $credentialsParts = explode(':', $credentials);
                $_SERVER['PHP_AUTH_USER'] = $credentialsParts[0] ?? '';
                $_SERVER['PHP_AUTH_PW'] = $credentialsParts[1] ?? '';
                $_SERVER['PHP_AUTH_DIGEST'] = $authorizationHeader;
                $_SERVER['AUTH_TYPE'] = $type;
            }
        }
    }

    private function populateQuery()
    {
        $_GET = $this->message->getQuery();
    }

    private function populatePost()
    {
        $_POST = $this->message->getPost();
    }

    private function populateRequest()
    {
        switch ($this->message->getRequestOrder()) {
            case 'GPC': $_REQUEST = array_replace_recursive($_GET, $_POST, $_COOKIE); break;
            case 'GCP': $_REQUEST = array_replace_recursive($_GET, $_COOKIE, $_POST); break;
            case 'PGC': $_REQUEST = array_replace_recursive($_POST, $_GET, $_COOKIE); break;
            case 'PCG': $_REQUEST = array_replace_recursive($_POST, $_COOKIE, $_GET); break;
            case 'CPG': $_REQUEST = array_replace_recursive($_COOKIE, $_POST, $_GET); break;
            case 'CGP': $_REQUEST = array_replace_recursive($_COOKIE, $_GET, $_POST); break;
            default   : $_REQUEST = array_replace_recursive($_GET, $_POST, $_COOKIE);
        }
    }

    public function getRawBody() : string
	{
		return $this->message->getRawBody();
    }

    public function hasFiles($onlySuccessful = false) : int
    {
        return count($this->message->getUploadedFiles());
    }

    public function getUploadedFiles($onlySuccessful = false) : array
    {
        $files = [];

        foreach ($this->message->getUploadedFiles() as $index => $fileData) {
            /** @var \React\Http\File $file */
            $file = $fileData['file'];
            $files[] = new File([
                "tmp_name" => $fileData['name'],
                "name"     => $file->getFilename(),
                "type"     => $file->getContentType(),
            ], $index);
        }

        return $files;
    }
}
