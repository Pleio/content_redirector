<?php

	function content_redirector_init(){
		// register page handler for nice URL's
		elgg_register_page_handler("add", "content_redirector_page_handler");
		
		elgg_extend_view("js/elgg", "content_redirector/js/site");
	}
	
	function content_redirector_page_handler(){
		include(dirname(__FILE__) . "/pages/add.php");
		return true;
	}
	
	elgg_register_event_handler("init", "system", "content_redirector_init");
	