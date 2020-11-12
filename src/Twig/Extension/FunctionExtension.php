<?php


namespace Btinet\Ringhorn\Twig\Extension;


class FunctionExtension extends \Twig\Extension\AbstractExtension
{

    public function generateLink($uri){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol.$_SERVER['HTTP_HOST'].'/'.$uri;
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('link', array($this,'generateLink')),
        ];
    }



}