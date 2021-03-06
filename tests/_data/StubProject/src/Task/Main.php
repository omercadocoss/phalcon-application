<?php

namespace StubProject\Task;

use Phalcon\Cli\Task;

class Main extends Task
{
    public function mainAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function customAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function argumentAction(array $arguments)
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '(' . implode(', ', $arguments). ')';
    }

    public function serviceAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }

        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function errorAction()
    {
        throw new \Exception('error');
    }
}
