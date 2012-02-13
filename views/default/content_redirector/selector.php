<?php

$type_selection = "";
$container_selection = "";
$group_selection = "";

$supported_plugins = array(
	"file" => array(
		"title" => elgg_echo("item:object:file"),
		"group_tool_option" => "file_enable",
		"user_link" => "pg/file/new/[USERNAME]",
		"group_link" => "pg/file/new/[USERNAME]"
		),
	"blog" => array(
		"title" => elgg_echo("item:object:blog"),
		"group_tool_option" => "blog_enable",
		"user_link" => "pg/blog/new/[USERNAME]",
		"group_link" => "pg/blog/new/[USERNAME]"
	),
	"pages" => array(
		"title" => elgg_echo("item:object:page"),
		"group_tool_option" => "pages_enable",
		"user_link" => "pg/pages/new/?container_guid=[GUID]",
		"group_link" => "pg/pages/new/?container_guid=[GUID]",
	),
	"thewire" => array(
		"title" => elgg_echo("item:object:thewire"),
		"group_tool_option" => true,
		"user_link" => "pg/thewire/owner/[USERNAME]",
		"group_link" => "pg/thewire/group/[GUID]"
	),
	
	"tidypics" => array(
		"title" => elgg_echo("item:object:album"),
		"group_tool_option" => "photos_enable",
		"user_link" => "pg/photos/new/[USERNAME]",
		"group_link" => "pg/photos/new/[USERNAME]"
	),

	"bookmarks" => array(
		"title" => elgg_echo("item:object:bookmarks"),
		"group_tool_option" => "bookmarks_enable",
		"user_link" => "pg/bookmarks/add/[USERNAME]",
		"group_link" => "pg/bookmarks/add/[USERNAME]",
	),
	"event_manager" => array(
		"title" => elgg_echo("event_manager:event:view:event"),
		"group_tool_option" => "event_manager_enable",
		"user_link" => "pg/events/event/new",
		"group_link" => "pg/events/event/new/[USERNAME]",
	),
	
);

foreach($supported_plugins as $plugin_name => $plugin_details){
	if(is_plugin_enabled($plugin_name)){
		$type_selection .= elgg_view("input/button", array("internalid" => $plugin_name, "type" => "button", "value" => $plugin_details["title"])) . " ";
	}
}

if(is_plugin_enabled("groups")){
	
	// check for membership
	$options = array(
			"type" => "group",
			"limit" => false
		);
	
	$groups = elgg_get_entities($options);
	if(!empty($groups)){
		// personal or group
		$container_selection .= elgg_view("input/button", array("internalid" => "content-redirector-selector-container-personal", "type" => "button", "value" => elgg_echo("content_redirector:selector:container:personal"))) . " ";
		$container_selection .= elgg_view("input/button", array("internalid" => "content-redirector-selector-container-group", "type" => "button", "value" => elgg_echo("content_redirector:selector:container:group")));
		$group_selection_items = array();
		foreach($groups as $group){
			$group_rels = array();
			foreach($supported_plugins as $plugin_name => $plugin_details){
				if($plugin_details["group_tool_option"] === true){
					$group_rels[] = $plugin_name;
				} else {
					$tool_option = $group->$plugin_details["group_tool_option"];
					if($tool_option == "yes"){
						$group_rels[] = $plugin_name;
					}
				}
			}
			if(!empty($group_rels)){
				$group_rels = implode($group_rels, " ");
			}
			
			$key = strtolower($group->name) . "-" . $group->getGUID();
			$button = "<input type='button' id='" . $group->guid . "' rel='" . $group_rels . "' name='" . $group->username . "' value='" . $group->name . "' class='submit_button'>";
			$group_selection_items[$key] = $button;
		}
		ksort($group_selection_items);
		$group_selection = implode($group_selection_items, " ");
	}
}

if(!empty($type_selection)){
?>
<div id='content-redirector-selector' class='contentWrapper'>
	<div id="content-redirector-type-selection">
		<h3 class='settings'>
			<?php echo elgg_echo("content_redirector:selector:type"); ?>
		</h3>
		<div class='content-redirector-selector-info'>
			<?php echo elgg_echo("content_redirector:selector:type:info"); ?>
		</div>
		<div>
			<?php echo $type_selection; ?>
		</div>
	</div>
	<?php 
		if(!empty($container_selection)){
	?>
	<div id="content-redirector-container-selection">
		<h3 class='settings'>
			<?php echo elgg_echo("content_redirector:selector:container"); ?>
		</h3>
		<div class='content-redirector-selector-info'>
			<?php echo elgg_echo("content_redirector:selector:container:info"); ?>
		</div>
		<div>
			<?php echo $container_selection; ?>
		</div>
	</div>
	<?php 
		}
		
		if(!empty($group_selection)){
	?>
	<div id="content-redirector-group-selection">
		<h3 class='settings'>
			<?php echo elgg_echo("content_redirector:selector:group"); ?>
		</h3>
		<div class='content-redirector-selector-info'>
			<?php echo elgg_echo("content_redirector:selector:group:info"); ?>
		</div>
		<div>
			<?php echo $group_selection; ?>
			<div id='content-redirector-group-none'>
				<?php echo elgg_echo("content_redirector:selector:group:none"); ?>
			</div>
		</div>
	</div>
	<?php 
		}
	?>
	<?php echo elgg_view("input/button", array("internalid" => "content-redirector-selector-add", "type" => "button", "value" => elgg_echo("content_redirector:selector:add")));?>
</div>
<script type="text/javascript">

<?php 

	foreach($supported_plugins as $plugin_name => $plugin_details){
		echo "var " . $plugin_name . "_details = new Array('" . $plugin_details["user_link"] . "', '" . $plugin_details["group_link"] . "');
			";	
	}

?>

$(document).ready(function(){
	$("#content-redirector-selector input[type='button']").live("click", function(event){
		$(this).parent().find("input").removeClass("submit_button");
		$(this).addClass("submit_button")
	});

	$("#content-redirector-selector-container-group").click(function(event){
		$("#content-redirector-group-selection").show();
		$("#content-redirector-group-selection input").addClass("submit_button");
		$("#content-redirector-selector-add").hide();
	});

	$("#content-redirector-selector-container-personal").click(function(event){
		$("#content-redirector-group-selection").hide();
		$("#content-redirector-selector-add").show();
	});

	$("#content-redirector-group-selection input").click(function(event){
		$("#content-redirector-selector-add").show();
	});

	$("#content-redirector-type-selection input").click(function(event){
		$("#content-redirector-container-selection").show();
		content_redirector_check_groups($(this).attr("id"));
		// no container selection available
		if($("#content-redirector-container-selection").length === 0){
			$("#content-redirector-selector-add").show();
		}
	});

	$("#content-redirector-selector-add").click(function(event){
		var content_type = $("#content-redirector-type-selection input.submit_button").attr("id");
		var group_guid = $("#content-redirector-group-selection input:visible.submit_button").attr("id");
		var group_username = $("#content-redirector-group-selection input:visible.submit_button").attr("name");
		var wwwroot = "<?php echo $vars["url"] ; ?>";
		
		if(content_type){
			if(group_guid == undefined){
				link = eval(content_type + "_details[0]");
				link = link.replace("[USERNAME]", "<?php echo get_loggedin_user()->username; ?>").replace("[GUID]", "<?php echo get_loggedin_userid(); ?>");
				document.location.href = wwwroot + link;
			} else {
				link = eval(content_type + "_details[1]");
				link = link.replace("[USERNAME]", group_username).replace("[GUID]", group_guid);
				document.location.href = wwwroot + link;
			}
		}
		
		event.stopPropagation();
	});

	
	
});

function content_redirector_check_groups(content_type){
	if($("#content-redirector-group-selection").length > 0){
		$("#content-redirector-group-selection input").hide();
		$("#content-redirector-group-selection input[rel*='" + content_type + "']").show();	

		$("#content-redirector-group-none").hide();
		if($("#content-redirector-group-selection input[rel*='" + content_type + "']").length == 0){
			$("#content-redirector-group-none").show();
		}	
	}
}

</script>
<?php 
} else {
	echo elgg_echo("no supported content types available");
}
