<?php


namespace Btinet\Ringhorn;

use Btinet\Ringhorn\View\View;

class Flash
{

    /**
     * @var View
     */
    protected View $view;

    /**
     * Flash constructor.
     * @param $view
     */
    function __construct($view){

        $this->view = $view;

    }

    /**
     * @param false $message
     * @param false $type
     */
    public function show($message = false, $type = false) {

        $message = $message ? $message : Session::get('message');
        $type = $type ? $type : Session::get('message_type');
        $type = $type ? $type : 'success';

        if ($message) {

            $this->view->render('/messages/message.html.twig', [
                'type' => $type,
                'message' => $message
            ]);
            Session::clear('message');
            Session::clear('message_type');
        }
    }

    /**
     * @param false $message
     * @param string $type
     * @return false
     */
    public function add($message = false, $type = 'success') {

        Session::set('message', $message);
        Session::set('message_type', $type);
        return false;
    }

}