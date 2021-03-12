<?php

namespace Btinet\Ringhorn\View;

use Btinet\Ringhorn\Controller\AbstractController;
use Btinet\Ringhorn\Logger;
use Btinet\Ringhorn\Translation;
use Btinet\Ringhorn\Twig\Extension\FunctionExtension;
use Exception;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class View
{

    /**
     * @var Environment
     */
    protected Environment $view;
    protected $globals;
    protected $trans;

    /**
     * View constructor.
     */
    function __construct($globals = null){
        $debug = ($_ENV['APP_ENV'] !== 'production') ?: true;
        $this->globals = $globals;

        $trans = new Translation();
        $this->trans = $trans->parse();

        $loader = new FilesystemLoader(project_root.'/templates');
        $this->view = new Environment($loader, [
            'cache' => project_root.'/var/cache',
            'debug' => $debug
        ]);

        if ($debug){
            $this->view->addExtension(new \Twig\Extension\DebugExtension());
        }
        $this->view->addExtension(new IntlExtension());
        $this->view->addExtension(new FunctionExtension());
        $this->view->addGlobal('app',$this->globals);
        $this->view->addGlobal('trans',$this->trans);
        $this->view->addGlobal('locale',$trans->locale);
        $this->view->addGlobal('locales',$trans->availableLanguages);

    }

    /**
     * @param $template
     * @param array $options
     */
    public function render($template, Array $options = [] ){

        try {
            echo $this->view->render($template, $options);
        } catch (Exception $e) {
            $logger = new Logger();
            $logger->newMessage($e);
            $logger->customErrorMsg($e);
        }
    }
}