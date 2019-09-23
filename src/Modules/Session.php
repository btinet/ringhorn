<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Modules;

class Session {

   private static $_sessionStarted = false;

   public static function init() {
      if (self::$_sessionStarted == false) {
         session_start();
         self::$_sessionStarted = true;
      }      
   }

   public static function set($key, $value) {
      return $_SESSION[SESSION_PREFIX . $key] = $value;
   }

   public static function get($key, $secondkey = false) {
      if ($secondkey == true) {
         if (isset($_SESSION[SESSION_PREFIX . $key][$secondkey])) {
            return $_SESSION[SESSION_PREFIX . $key][$secondkey];
         }
      } else {
         if (isset($_SESSION[SESSION_PREFIX . $key])) {
            return $_SESSION[SESSION_PREFIX . $key];
         }
      }
      return false;
   }

   public static function display() {
      return $_SESSION;
   }

   public static function clear($key) {
      unset($_SESSION[SESSION_PREFIX . $key]);
   }

   public static function destroy() {
      if (self::$_sessionStarted == true) {
         session_unset();
         session_destroy();
      }
   }

}
