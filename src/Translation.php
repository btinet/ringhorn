<?php


namespace Btinet\Ringhorn;


class Translation
{
    public $localization;

    public function __construct($localization = null)
    {
        if(!$localization){
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $acceptLang = ['fr', 'it', 'en','de'];
            $localization = in_array($lang, $acceptLang) ? $lang : 'en';
        }
        $this->localization = $localization;
    }

    public function parse()
    {
        $localisation = $this->localization;
        $file = project_root."/translations/$localisation.yaml";
        return Spyc::YAMLLoad($file);
    }

}