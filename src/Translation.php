<?php


namespace Btinet\Ringhorn;


class Translation
{
    public $locale;
    public $availableLanguages;

    public function __construct($localization = null)
    {

        $available_languages =  Spyc::YAMLLoad(project_root."/translations/languages.yaml");
        $this->availableLanguages = $available_languages;
        $localization = self::getUserPreferedLanguage($available_languages, strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
        if (Session::get('userLocale')){
            $localization = Session::get('userLocale');
        }
        setlocale(LC_ALL,$localization);
        $this->locale = $localization;
    }

    public function parse()
    {
        $localisation = $this->locale;
        $file = project_root."/translations/$localisation.yaml";
        return Spyc::YAMLLoad($file);
    }

    public function getLocale(){
        return $this->locale;
    }

    public function getUserPreferedLanguage($available_languages, $http_accept_language)
    {
        $default_language = $available_languages[0];
        $available_languages = array_flip($available_languages);

        $langs = array();
        preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {

            list($a, $b) = explode('-', $match[1]) + array('', '');
            $value = isset($match[2]) ? (float)$match[2] : 1.0;

            if (isset($available_languages[$match[1]])) {
                $langs[$match[1]] = $value;
                continue;
            }

            if (isset($available_languages[$a])) {
                $langs[$a] = $value - 0.1;
            }

        }
        if ($langs) {
            arsort($langs);
            return key($langs); // We don't need the whole array of choices since we have a match
        } else {
            return $default_language;
        }
    }

}