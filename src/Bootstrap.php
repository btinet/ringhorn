<?php

namespace Btinet\Baldur;


use Exception;
use Symfony\Component\Dotenv\Dotenv;

class Bootstrap {

    private array $url;
    private $controller = null;
    private $defaultController;

    /**
     * Kernel constructor.
     */
    function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(dirname(__DIR__) . '/.env');

        if (isset($_ENV['APP_ENV'])) {
            switch ($_ENV['APP_ENV']) {
                case 'development':
                    error_reporting(E_ALL);
                    break;
                case 'production':
                    error_reporting(0);
                    break;
                default:
                    die('The application environment "'.$_ENV['APP_ENV'].'" is not allowed. Allowed: "development" or "production" ');
            }
        }

        $this->parseUrl();
    }

    /**
     * @param $name
     */
    public function setController($name) {
        $this->defaultController = $name;
    }

    public function init() {
        //if no page requested set default controller
        if(empty($this->url[0])) {
            $this->loadDefaultController();
            return false;
        }
        $this->loadExistingController();
        $this->callControllerMethod();
        return false;
    }

    private function parseUrl() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : NULL;
        $url = urldecode(filter_var(urlencode($url), FILTER_SANITIZE_URL));
        $this->url = explode('/', $url);
    }

    private function loadDefaultController() {
        $this->controller = new $this->defaultController();
        $this->controller->index();
    }

    private function loadExistingController() {


        $this->controller = 'App\\Controller\\'.ucfirst($this->url[0]).'Controller';

        try {
            if (class_exists($this->controller)){
                $this->controller = new $this->controller();
            } else {
                throw new Exception('Controller-Klasse gibts nicht, daher m&ouml;chte ich nun eine Fehlerseite ausgeben.');
            }
        } catch(Exception $e) {
            Logger::newMessage($e);
            Logger::customErrorMsg($e);
        }


    }

    /**
     * If a method is passed in the GET url paremter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Parameter
     *  url[3] = Parameter
     *  url[4] = Parameter
     */
    private function callControllerMethod()
    {
        unset($this->url[0]);
        $method = (empty($this->url[1])) ? 'index' : $this->url[1];

        try {
            if (is_callable(array($this->controller, $method))) {
                $method = (is_array($method)) ? array_shift($this->url) : $method;
            } else {
                throw new Exception('Methode gibts nicht, daher m&ouml;chte ich nun eine Fehlerseite ausgeben.');
            }
        } catch(Exception $e) {
            // TODO: Add Error Page
            Logger::newMessage($e);
            Logger::customErrorMsg($e);
        }


        $parameter = filter_var_array($this->url, FILTER_SANITIZE_STRING);
        call_user_func_array(array($this->controller, $method), $parameter);
    }

    /**
     * Display an error page if nothing exists
     *
     * @param $error
     * @return void
     */
    private function error($error) {
        Logger::exception_handler($error);
        Logger::errorMessage($error, true);
        die;
    }

}
