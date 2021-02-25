<?php


namespace Btinet\Ringhorn\Controller;

use Btinet\Ringhorn\Flash;
use Btinet\Ringhorn\Logger;
use Btinet\Ringhorn\Model\EntityManager;
use Btinet\Ringhorn\Password;
use Btinet\Ringhorn\Request;
use Btinet\Ringhorn\Security;
use Btinet\Ringhorn\Session;
use Btinet\Ringhorn\User;
use Btinet\Ringhorn\View\View;
use Exception;
use \ReflectionClass;


abstract class AbstractController
{

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var Security
     */
    protected Security $security;

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

        $this->session = new Session();
        $this->session->init();

        $this->request = new Request();
        $this->request->csrf_token = $this->session->get('csrf_token');
        $this->generateToken();

        $this->view = new View([
            'csrf_token' => $this->session->get('csrf_token')
        ]);

        $this->flash = new Flash($this->view);
        $this->security = new Security($this->session);

    }

    public function getEntityManager(){
        return new EntityManager();
    }

    public function getRepository($entity_class){
        $entity = new ReflectionClass($entity_class);
        $repository = 'App\Repository\\'.$entity->getShortName().'Repository';

        if(class_exists($repository)){
            return new $repository($entity);
        }
        return false;
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
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        header('Location: ' .$protocol.host.'/'.$url, true, $status);
        exit;
    }

    public function generateRoute(string $route, array $mandatory = null){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if ($mandatory) {
            foreach ($mandatory as $name => $value) {
                $route .= "/$value";
            }
        }
        return $protocol.$_SERVER['HTTP_HOST'].'/'.$route;
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