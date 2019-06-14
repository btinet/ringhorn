<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

use Btinet\ SimpleMVC\ Core;

use Twig\ Loader;

class View {

	protected $_template;
	
	public

	function assign( $path, $data = false, $parent = "default" ) {

		$loader = new Loader\ FilesystemLoader( TPATH );
		$this->_template = new\ Twig\ Environment( $loader );
		
		$data['parent_template'] = "$parent.tpl";		

		if ( file_exists( TPATH . "$path.tpl" ) ) {

			$template = $this->_template->load( "$path.tpl" );

			return $template->render( [ 'data' => $data ] );
			
			
		} else {
			
			return "File not found";
			
		}

	}
	
	public

	function render( $path, $data = false, $error = false ) {

		require APPPATH . 'views' . DS . "$path.php";		
		
	}

}