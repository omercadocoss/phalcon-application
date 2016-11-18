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

use Phapp\Application\MultipartMessageInterface;
use React\Http\Request as ReactRequest;

class MultipartMessage implements MultipartMessageInterface
{
    /** @var ReactRequest  */
    private $request;

    /** @var array */
    private $data;

    /** @var string */
    private $requestOrder;

    /**
     * @param ReactRequest $request
     * @param array $data
     * @param string $requestOrder
     */
    public function __construct(ReactRequest $request, array $data = [], string $requestOrder = 'GPC')
    {
        $this->request = $request;
        $this->data = $data;
        $this->requestOrder = $requestOrder;
    }

    public function getMethod() : string
    {
        $this->request->getMethod();
    }

    public function getPath() : string
    {
        $this->request->getPath();
    }

    public function getHeaders() : array
    {
        return $this->request->getHeaders();
    }

    public function getQuery() : array
    {
        return $this->request->getQuery();
    }

    public function getPost() : array
    {
        return $this->data['post'] ?? [];
    }

    public function getRawBody() : string
    {
        return $this->data['body'] ?? [];
    }

    public function getUploadedFiles() : array
    {
        return $this->data['files'] ?? [];
    }

    public function getRequestOrder() : string
    {
        return $this->requestOrder;
    }
}
