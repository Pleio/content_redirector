<?php

$type_selection = "a";
$container_selection = "b";
$group_selection = "c";

$supported_plugins = array(
	"file" => array(
		"group_tool_option" => "file_enable",
		"user_link" => "pg/file/new",
		"group_link" => "pg/file/new/group"
		),
	"blog" => array(
		"group_tool_option" => "blog_enable",
		"user_link" => "pg/file/new",
		"group_link" => "pg/file/new/group"
	)
);

foreach($supported_plugins as $plugin_name){
	if(is_plugin_enabled($plugin_name)){
		
	}
}

if(!empty($type_selection)){
?>
<div class='contentWrapper'>
	<h3 class='settings'>
		<?php echo elgg_echo("content_redirector:selector:type"); ?>
	</h3>
	<div class='content-redirector-selector-info'>
		<?php echo elgg_echo("content_redirector:selector:type:info"); ?>
	</div>
	<div>
		<?php echo $type_selection; ?>
	</div>
	<?php 
		if(!empty($container_selection)){
	?>
	<h3 class='settings'>
		<?php echo elgg_echo("content_redirector:selector:container"); ?>
	</h3>
	<div class='content-redirector-selector-info'>
		<?php echo elgg_echo("content_redirector:selector:container:info"); ?>
	</div>
	<div>
		<?php echo $container_selection; ?>
	</div>
	<?php 
		}
		
		if(!empty($group_selection)){
	?>
	<h3 class='settings'>
		<?php echo elgg_echo("content_redirector:selector:group"); ?>
	</h3>
	<div class='content-redirector-selector-info'>
		<?php echo elgg_echo("content_redirector:selector:group:info"); ?>
	</div>
	<div>
		<?php echo $group_selection; ?>
	</div>
	<?php 
		}
	?>
	<?php echo elgg_view("input/button", array("type" => "button", "value" => elgg_echo("add"), "js" => "onclick='content_redirector_add_content();'"));?>
</div>
<?php 
}
?>