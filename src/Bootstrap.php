<?php 

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC;

use Btinet\ SimpleMVC\ Core;
use Btinet\ SimpleMVC\ Modules;

use Twig\ Loader;

class Bootstrap {

	public $_post;
	private $_url;
	private $_filter;
	private $_controller = null;
	private $_defaultController;

	public

	function __construct() {
		
		Modules\ Session::init();
		
		$this->_getUrl();
		$this->_getPOST();

	}

	public

	function setController( $name ) {
		
		$this->_defaultController = $name;
	}

	public

	function init() {
					
		if ( empty( $this->_url[ 0 ] ) ) {
			$this->_loadDefaultController();
			return false;
		}

		$this->_loadExistingController();
		$this->_callControllerMethod();
	}

	private

	function _getUrl() {
		$url = isset( $_GET[ 'url' ] ) ? rtrim( $_GET[ 'url' ], '/' ) : NULL;
		$url = urldecode( filter_var( urlencode( $url ), FILTER_SANITIZE_URL ) );
		$this->_url = explode( '/', $url );
	}

	public

	function _getPOST() {
		$post = isset( $_POST ) ? $_POST : NULL;
		$this->_post = filter_var_array( $post, FILTER_SANITIZE_STRING );
	}

	private

	function _loadDefaultController() {
		$this->_defaultController = APPSPACE . $this->_defaultController;
		$this->_controller = new $this->_defaultController();
		$this->_controller->index();
	}

	private

	function _loadExistingController() {

		//set url for controllers
		$controller = APPSPACE . $this->_url[ 0 ];

		if ( class_exists( $controller ) ) {
			
			//instatiate controller
			$this->_controller = new $controller;
		} else {
			$this->_errors( "File does not exist: " . $this->_url[ 0 ] );
			return false;
		}

	}

	private

	function _callControllerMethod() {
		unset( $this->_url[ 0 ] );
		$method = 'index';

		if (isset($this->_url[ 1 ])){
			
			if ( is_callable( array( $this->_controller, $this->_url[ 1 ] ) ) ) {
				$method = array_shift( $this->_url );
			}
			
		} 

		$parameter[ 'get' ] = filter_var_array( $this->_url, FILTER_SANITIZE_STRING );
		$parameter[ 'post' ] = $this->_post;

		call_user_func_array( array( $this->_controller, $method ), $parameter );
	}

	private

	function _errors( $error ) {

		$this->_controller = new Core\ Errors( $error );
		$this->_controller->index();
		die;
	}

}