<?php


namespace Btinet\Ringhorn\Controller;

use Btinet\Ringhorn\Flash;
use Btinet\Ringhorn\Logger;
use Btinet\Ringhorn\Password;
use Btinet\Ringhorn\Request;
use Btinet\Ringhorn\Session;
use Btinet\Ringhorn\View\View;
use Exception;


abstract class AbstractController
{

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var Password
     */
    protected Password $passwordEncoder;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var View
     */
    protected View $view;

    /**
     * @var Flash
     */
    protected Flash $flash;

    /**
     * AbstractController constructor.
     */
    function __construct()
    {
        $this->view = new View();

        $this->session = new Session();
        $this->session->init();

        $this->flash = new Flash($this->view);
        $this->passwordEncoder = new Password();
        $this->request = new Request();
        $this->request->csrf_token = $this->session->get('csrf_token');
        $this->generateToken();

    }

    public function generateToken(){

        $csrfToken = null;

        try {
            $csrfToken = sha1(random_bytes(9));
        } catch (Exception $e) {
            $this->catchException($e);
        }

        $this->session->set('csrf_token',$csrfToken);

        return $csrfToken;
    }

    /**
     * @param $e
     */
    public function catchException($e){
        Logger::newMessage($e);
        Logger::customErrorMsg($e);
    }

    public function redirect($status, $url = null) {
        header('Location: ' . host . $url, true, $status);
        exit;
    }

    public function halt($status = 404, $message = 'Something went wrong.') {
        if (ob_get_level() !== 0) {
            ob_clean();
        }

        http_response_code($status);
        $data['status'] = $status;
        $data['message'] = $message;

        // TODO: Change internal View to Twig Engine

        if (!file_exists("/templates/error/$status.php")) {
            $status = 'default';
        }
        require project_root."/templates/error/$status.php";

        exit;
    }

    //abstract function index(Request $request, $get = false);

}