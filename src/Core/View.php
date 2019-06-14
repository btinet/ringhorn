<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

use Twig\ Loader;

class View {

	public

	function assign( $path, $data = false, $parent = "default" ) {

		$loader = new Loader\ FilesystemLoader( TPATH );
		return new\ Twig\ Environment( $loader );
		
		$parent = "$parent.tpl";		

		if ( file_exists( TPATH . "$path.tpl" ) ) {

			$template = $this->_template->load( "$path.tpl" );

			echo $template->render( [ 'data' => $data ] );
			
		} else {

			$view = new Core\ Errors( "Datei nicht gefunden:" );

			$view->index();

		}	

	}
	
	public

	function render( $path, $data = false, $error = false ) {

		require APPPATH . 'views' . DS . "$path.php";		
		
	}

}