<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;



class Controller {

	protected $_view;
	protected $_model;
	protected $_get;
	protected $_post;
	protected $_data;

	

	function __construct() {
		$this->_view = new View();
			
		$name = get_class($this);		
		$modelpath = APPPATH . 'Models' . DS . $name . 'Model.php';

		if (file_exists($modelpath)) {
			require $modelpath;

			$modelName = $name . 'Model';
			$this->_model = new $modelName();
		};		
				
	}

}
