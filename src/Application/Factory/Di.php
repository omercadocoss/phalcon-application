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

namespace Phapp\Application\Factory;

use Phalcon\Config;
use Phalcon\DiInterface;
use Phapp\Application\Service\InjectableInterface;

class Di
{
    /**
     * @param array $config
     * @param DiInterface $defaultDi
     * @return DiInterface
     */
    public static function createMvcFrom(array $config, DiInterface $defaultDi = null) : DiInterface
    {
        if (!$defaultDi) {
            $defaultDi = new \Phalcon\Di\FactoryDefault;
        }

        $defaultDi->set('config', function () use ($config) {
            return new Config($config);
        });

        $defaultDi->setShared('router', function () use ($config) {
            return Router::createFrom($config['routes'] ?? []);
        });

        $useHttpResponseProxy = $defaultDi instanceof MvcMessageDi;
        $defaultDi->setShared('dispatcher', function () use ($config, $useHttpResponseProxy) {
            return Dispatcher::createMvcFrom($config['dispatcher'], $useHttpResponseProxy);
        });

        if (isset($config['view'])) {
            $defaultDi->setShared('view', function () use ($config, $defaultDi) {
                return View::createFrom($config['view'] ?? [], $defaultDi);
            });
        }

        foreach ($config['services'] ?? [] as $service) {
            /** @var InjectableInterface $service */
            $service::injectTo($defaultDi);
        }

        return $defaultDi;
    }

    /**
     * @param array $config
     * @return DiInterface
     */
    public static function createCliFrom(array $config) : DiInterface
    {
        $defaultDi = new \Phalcon\Di\FactoryDefault\Cli;

        $defaultDi->set('config', function () use ($config) {
            return new Config($config);
        });

        $defaultDi->setShared('dispatcher', function () use ($config) {
            return Dispatcher::createCliFrom($config['dispatcher']);
        });

        foreach ($config['services'] ?? [] as $service) {
            /** @var InjectableInterface $service */
            $service::injectTo($defaultDi);
        }

        return $defaultDi;
    }
}
