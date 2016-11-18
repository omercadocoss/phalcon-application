<?php

declare(strict_types = 1);

namespace Phapp\Application\Http;

use Phalcon\Http\Response\Cookies;

class CookieCollection extends Cookies
{
    /**
     * @return \Phalcon\Http\Cookie[]
     */
    public function getAll() : array
    {
        return $this->_cookies;
    }
}
