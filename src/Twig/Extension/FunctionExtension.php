<?php


namespace Btinet\Ringhorn\Twig\Extension;


class FunctionExtension extends \Twig\Extension\AbstractExtension
{
    private function getHost(){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol.$_SERVER['HTTP_HOST'];
    }

    public function generateLink($uri){
        $uri = $this->getHost().'/'.$uri;
        return $uri;
    }

    public function generateRoute($uri, array $mandatory = null){
        $uri = $this->getHost().'/'.$uri;
        if ($mandatory) {
            foreach ($mandatory as $name => $value) {
                $uri .= "/$value";
            }
        }
        return $uri;
    }

    public function getEntryLinkTags($entrypoint){
        $url = project_root.'/public/build/entrypoints.json';
        $json = file_get_contents($url);
        $json = json_decode($json, true);
        $host = $this->getHost();

        foreach ($json as $element) {
            foreach ($element as $entrypoint_name){
                $ausgabe = $entrypoint_name['css'];
            }
        }
        $css_ausgabe = '';
        foreach ($ausgabe as $css){
            $css_ausgabe .= '<link rel="stylesheet" href="'.$host.$css.'">';
        }
        return $css_ausgabe;
    }

    public function getEntryScriptTags($entrypoint){
        $url = project_root.'/public/build/entrypoints.json';
        $json = file_get_contents($url);
        $json = json_decode($json, true);
        $host = $this->getHost();

        foreach ($json as $element) {
            foreach ($element as $entrypoint_name){
                $ausgabe = $entrypoint_name['js'];
            }
        }
        $js_ausgabe = '';
        foreach ($ausgabe as $js){
            $js_ausgabe .= '<script src="'.$host.$js.'"></script>';
        }
        return $js_ausgabe;
    }



    function getTokenParsers() {
        return array(
            new BreakToken(),
        );
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('link', array($this,'generateLink')),
            new \Twig\TwigFunction('route', array($this,'generateRoute')),
            new \Twig\TwigFunction('get_entry_link_tags', array($this,'getEntryLinkTags')),
            new \Twig\TwigFunction('get_entry_script_tags', array($this,'getEntryScriptTags')),
        ];
    }

    public function getName()
    {
        return FunctionExtension::t('Vapita For-Break Tag');
    }

}