<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

use Btinet\ SimpleMVC\ Modules;

class Controller {

	protected $_view;
	protected $_model;
	protected $_get;
	protected $_post;
	protected $_data;
	protected $_user;

	

	function __construct() {
		$this->_view = new View();			
		$this->_user = new Modules\User();		
				
	}

}
