<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

class Errors extends Controller {

	private $_error = null;

	public

	function __construct( $error ) {

		parent::__construct();
		$this->_error = $error;

	}

	public

	function index( array $get = NULL, array $post = NULL ) {

		$this->_data[ 'get' ] = $get;
		$this->_data[ 'post' ] = $post;
		$this->_data[ 'page_title' ] = $this->_error;	

		$template = 'errors/404.tpl';

		$loader = new Loader\ FilesystemLoader( TPATH );
		$twig = new\ Twig\ Environment( $loader );
		$template = $twig->load( "$template" );

		echo $template->render( [ 'data' => $this->_data ] );

	}

}