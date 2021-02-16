<?php

namespace Btinet\Ringhorn;


use Btinet\Ringhorn\Twig\Extension\FunctionExtension;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Logger {

    public function __construct()
    {
        $debug = ($_ENV['APP_ENV'] !== 'production') ?: true;

        $loader = new FilesystemLoader(project_root.'/templates');
        $this->view = new Environment($loader, [
            'cache' => project_root.'/var/cache',
            'debug' => $debug
        ]);

        if ($debug){
            $this->view->addExtension(new \Twig\Extension\DebugExtension());
        }
        $this->view->addExtension(new FunctionExtension());
    }

    private  $print_error = false;

    public function customErrorMsg(Exception $exception) {
        echo $this->view->render('error/default.html.twig',[
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile()
        ]);
        exit;
    }

    public  function exception_handler($e) {
        self::newMessage($e);
        self::customErrorMsg($e);
    }

    public function error_handler($number, $message, $file, $line) {
        $msg = "$message in $file on line $line";

        if ( ($number !== E_NOTICE) && ($number < 2048) ) {
            self::errorMessage($msg);
            // self::customErrorMsg($e);
        }

        return 0;
    }

    public function newMessage(Exception $exception, $print_error = false, $clear = false, $error_file = 'errorlog.html') {

        $file_path = project_root.'/var/'.$error_file;

        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $date = date('M d, Y G:iA');

        $log_message = "<h3>Exception information:</h3>\n
         <p><strong>Date:</strong> {$date}</p>\n
         <p><strong>Message:</strong> {$message}</p>\n
         <p><strong>Code:</strong> {$code}</p>\n
         <p><strong>File:</strong> {$file}</p>\n
         <p><strong>Line:</strong> {$line}</p>\n
         <h3>Stack trace:</h3>\n
         <pre>{$trace}</pre>\n
         <hr />\n";

        if (is_file($file_path) === false) {
            file_put_contents($file_path, '');
        }

        if ($clear) {
            $content = '';
        } else {
            $content = file_get_contents($file_path);
        }

        file_put_contents($file_path, $log_message . $content);

        if ($print_error == true) {
            echo $log_message;
            exit;
        }
    }

    public function errorMessage($error, $print_error = false, $error_file = 'errorlog.html') {

        $date = date('M d, Y G:iA');
        $log_message = "<p>Error on $date - $error</p>\n\n";

        $file_path = project_root.'/var/'.$error_file;

        file_put_contents($file_path, $log_message, FILE_APPEND);

        if ($print_error == true) {
            echo $log_message;
            exit;
        }
    }
}