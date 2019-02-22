<?php

namespace App\Http;

use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class LoggerService
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    private $controller = '';
    private $method = '';
    private $line = 0;
    private $fileName = '';
    private $enable = true;

    /**
     * LoggerService constructor.
     * @param string $fileName
     * VD: userlog
     */
    public function __construct($fileName = '')
    {
        $this->fileName = $fileName;
        if (\App::environment() == 'testing') {
            $this->enable = false;
        }
    }

    private function getClassName($trace)
    {
        //get method, $className
        $className = '';
        foreach ($trace as $item) {
            if (isset($item['class']) && Str::contains($item['class'], 'App\Http\Controllers')) {
                $this->method = $item['function'];
                $className = str_replace('App\Http\Controllers\\', '', $item['class']);
                break;
            }
        }
        return $className;
    }

    private function initLogger()
    {
        if (!$this->enable) {
            return;
        }
        $trace = debug_backtrace();
        //get line
        foreach ($trace as $item) {
            if (isset($item['class']) && Str::contains($item['class'], 'LoggerService')) {
                if (Str::contains($item['file'], 'Controller.php')) {
                    $this->line = $item['line'];
                    break;
                }
            }
        }

        $className = $this->getClassName($trace);

        $arr = explode('\\', $className);
        if (count($arr) > 1) {
            $this->controller = array_pop($arr);
        } else {
            $this->controller = $className;
        }
        $this->controller = str_replace('Controller', '', $this->controller);

        $loggerPath = storage_path('logs') . '/' . $this->fileName . '.log';

        $this->logger = \Log::getMonolog()->withName($this->controller);
        $handler = new StreamHandler($loggerPath, \Config::get('app.log_level'));
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $this->logger->setHandlers([$handler]);
    }

    /**
     * @param $context
     */
    private function addClassInfo(&$message = '', &$context = array())
    {
        if (!$this->enable) {
            return;
        }
        if (\Auth::user() != null) {
            $context['login_user'] = \Auth::user()->email;
        }
        $context = json_decode(json_encode($context), true);
        $message = '[' . $this->method . ':' . $this->line
            . ':' . $_SERVER['REQUEST_METHOD'] . '] -> ' . $message . "\n";
    }

    /**
     * @param string $message
     * @param $context
     */
    public function debug($message = '', $context = array())
    {
        if (!$this->enable) {
            return;
        }
        if (!is_array($context)) {
            $context = array($context);
        }
        $this->initLogger();
        $this->addClassInfo($message, $context);
        $this->logger->debug($message, $context);
    }

    /**
     * @param string $message
     * @param $context
     */
    public function error($message = '', $context = array())
    {
        if (!$this->enable) {
            return;
        }
        $this->initLogger();
        $this->addClassInfo($message, $context);
        $this->logger->error($message, $context);
    }

    public function log($message = '', $context = array())
    {
        if (!$this->enable) {
            return;
        }
        $this->debug($message, $context);
    }
}
