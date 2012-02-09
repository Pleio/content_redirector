<?php

	gatekeeper();
	
	$title_text = elgg_echo("content_redirector:add:title");
	
	$title = elgg_view_title($title_text);
	
	$body = elgg_view("content_redirector/selector");
	
	page_draw($title_text, elgg_view_layout("one_column", $title . $body));
	