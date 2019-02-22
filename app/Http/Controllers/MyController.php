<?php

namespace App\Http\Controllers;

use App\Http\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class MyController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    protected $header = '';

    protected $routerName = '';

    private $loggerVersion = 'v1';
    /**
     * @var LoggerService
     */
    protected $logger = null;

    public function __construct(Router $router)
    {
        $this->request = $router->getCurrentRequest();
        $currentRouter = $router->getCurrentRoute();
        if ($currentRouter != null) {
            $this->routerName = $currentRouter->getName();
        }
        $this->logger = new LoggerService($this->loggerVersion);
    }

    protected function getAllParams()
    {
        $data = $this->request->all();
        foreach ($data as $index => $vl) {
            if (!is_array($vl)) {
                $data[$index] = trim($vl);
            }
        }
        return $data;
    }

    public function view($view = null, $data = [], $mergeData = [])
    {
        $data['header'] = $this->header;
        $data['router_name'] = $this->routerName;
        $data['router_controller'] = '';
        $data['router_action'] = '';
        $data['router_module'] = '';
        $module = ['be-creator', 'ichimatsu'];
        if ($this->routerName != '') {
            $arr = explode('.', $this->routerName);
            if (in_array($arr[0], $module)) {
                $data['router_module'] = array_shift($arr);
                $data['router_controller'] = array_shift($arr);
                $data['router_action'] = implode('/', $arr);
            } else {
                $data['router_controller'] = array_shift($arr);
                $data['router_action'] = implode('/', $arr);
            }
        }
        return view($view, $data, $mergeData);
    }

    protected function submitError($error)
    {
        $this->logger->error($this->routerName . ' submit form fail', $error->getMessages());
        return redirect()->back(301)->withInput($this->getAllParams())->withErrors($error);
    }
}
