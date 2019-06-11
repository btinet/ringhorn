<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

class View {

	public

	function render( $path, $data = false, $error = false ) {

		require APPPATH . 'views' . DS . "$path.php";

	}

}