<?php

	function content_redirector_init(){
		// register page handler for nice URL's
		register_page_handler("add", "content_redirector_page_handler");
		
		elgg_extend_view("css", "content_redirector/css");
		elgg_extend_view("js/initialise_elgg", "content_redirector/js");
		
	}
	
	function content_redirector_page_handler(){
		//
		include(dirname(__FILE__) . "/pages/add.php");
	}
	
	register_elgg_event_handler("init", "system", "content_redirector_init");
	