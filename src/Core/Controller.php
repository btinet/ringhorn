<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

use Twig\ Loader;

class Controller {

	protected $_view;
	protected $_model;
	public $_get;
	public $_post;
	protected $_data;
	protected $_template;
	

	function __construct( array $get = NULL, array $post = NULL ) {
		$this->_view = new View();
		
		$this->_get = $get;
		$this->_post = $post;
		
		$name = get_class($this);		
		$modelpath = APPPATH . 'Models' . DS . $name . 'Model.php';

		if (file_exists($modelpath)) {
			require $modelpath;

			$modelName = $name . 'Model';
			$this->_model = new $modelName();
		};		
		
		$this->_data[ 'parent_template' ] = 'default.tpl';		
		
		$loader = new Loader\ FilesystemLoader( TPATH );
		$this->_template = new\ Twig\ Environment( $loader );
		
	}

}
