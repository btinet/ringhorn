<?php


namespace Btinet\Ringhorn;

class Session {

    private static $_sessionStarted = false;

    public static function init() {
        if (self::$_sessionStarted == false) {
            session_start();
            self::$_sessionStarted = true;
        }
    }

    public static function set($key, $value) {
        return $_SESSION[$_ENV['APP_SECRET'] . $key] = $value;
    }

    public static function get($key, $secondkey = false) {
        if ($secondkey == true) {
            if (isset($_SESSION[$_ENV['APP_SECRET'] . $key][$secondkey])) {
                return $_SESSION[$_ENV['APP_SECRET'] . $key][$secondkey];
            }
        } else {
            if (isset($_SESSION[$_ENV['APP_SECRET'] . $key])) {
                return $_SESSION[$_ENV['APP_SECRET'] . $key];
            }
        }
        return false;
    }

    public static function display() {
        return $_SESSION;
    }

    public static function clear($key) {
        unset($_SESSION[$_ENV['APP_SECRET'] . $key]);
    }

    public static function destroy() {
        if (self::$_sessionStarted == true) {
            session_unset();
            session_destroy();
        }
    }

}
