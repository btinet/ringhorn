<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Core;

use  Btinet\ SimpleMVC\ Modules;

class Model {

	protected $_db;
	protected $_imap;

	public function __construct() {
		//connect to PDO here.
		$this->_db = new Modules\ Database();
		//$this->_imap = new ImapClient(IMAP_HOST, IMAP_USER, IMAP_PASS, 'tls');
	}	

}