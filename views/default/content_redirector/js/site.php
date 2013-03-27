<?php ?>
//<script>

$(document).ready(function(){
	$("#content-redirector-selector > .elgg-module input[type='button']").live("click", function(event){
		$(this).parent().find("input").removeClass("elgg-button-submit");
		$(this).addClass("elgg-button-submit");
	});

	$("#content-redirector-selector-container-group").live("click", function(event){
		$("#content-redirector-group-selection").show();
		$("#content-redirector-group-selection input").removeClass("elgg-button-submit");
		$("#content-redirector-selector-add").hide();
	});

	$("#content-redirector-selector-container-personal").live("click", function(event){
		$("#content-redirector-group-selection").hide();
		$("#content-redirector-selector-add").show();
	});

	$("#content-redirector-group-selection input").live("click", function(event){
		$("#content-redirector-selector-add").show();
	});

	$("#content-redirector-type-selection input").live("click", function(event){
		var content_type = $("#content-redirector-type-selection input.elgg-button-submit").attr("id");
		if(content_type !== ""){
			if(eval(content_type + "_details[0]") !== ""){
				// both group and user upload available
				$("#content-redirector-container-selection").show();
			} else {
				// only group upload available, no need for container selection
				$("#content-redirector-container-selection").hide();
				if($("#content-redirector-selector-container-group.elgg-button-submit").length === 0){
					// no groups preselected
					$("#content-redirector-selector-container-group").click();
				} else {
					// check if there was a group selected, and reselect
					$("#content-redirector-group-selection input:visible.elgg-button-submit").click();
				}
			}
		}
		
		content_redirector_check_groups($(this).attr("id"));

		// validate preselected groups
		if($("#content-redirector-selector-container-group.elgg-button-submit").length !== 0){
			$("#content-redirector-group-selection").show();
		}
		
		var show_add_button = false;
		
		if($("#content-redirector-container-selection").length === 0){
			// no container options available
			show_add_button = true;
		} else {
			if($("#content-redirector-selector-container-personal.elgg-button-submit").length !== 0){
				// personal selected
				show_add_button = true;
			} else {
				if($("#content-redirector-selector-container-group.elgg-button-submit").length !== 0){
					if($("#content-redirector-group-selection input:visible.elgg-button-submit").length !== 0){
						// group selected
						show_add_button = true;
					}
				}
			}			
		}

		if(show_add_button){
			$("#content-redirector-selector-add").show();
		} else {
			$("#content-redirector-selector-add").hide();
		}
	});

	$("#content-redirector-selector-add").live("click", function(event){
		var content_type = $("#content-redirector-type-selection input.elgg-button-submit").attr("id");
		var group_guid = $("#content-redirector-group-selection input:visible.elgg-button-submit").attr("id");
		var group_username = $("#content-redirector-group-selection input:visible.elgg-button-submit").attr("name");
		
		if(content_type){
			if(group_guid == undefined){
				link = eval(content_type + "_details[0]");
				link = link.replace("[USERNAME]", elgg.get_logged_in_user_entity().username).replace("[GUID]", elgg.get_logged_in_user_guid());
				document.location.href = elgg.config.wwwroot + link;
			} else {
				link = eval(content_type + "_details[1]");
				link = link.replace("[USERNAME]", group_username).replace("[GUID]", group_guid);
				document.location.href = elgg.config.wwwroot + link;
			}
		}
		
		event.stopPropagation();
	});

	content_redirector_preselect();
});

function content_redirector_check_groups(content_type){
	if($("#content-redirector-group-selection").length > 0){
		$("#content-redirector-group-selection input").hide();
		$("#content-redirector-group-selection input[rel*='" + content_type + "']").show();	

		$("#content-redirector-group-none").hide();
		if($("#content-redirector-group-selection input[rel*='" + content_type + "']").length == 0){
			$("#content-redirector-group-none").show();
			$("#content-redirector-group-selection input").hide();
		}	
	}
}

function content_redirector_preselect() {
	params = elgg.parse_url(document.location.href,"query", true);
	if(params){
		content_type = params["content_type"];
		container_guid = params["container_guid"];
		if(content_type){
			$("#content-redirector-type-selection #" + content_type).click();
			if(container_guid){
				$("#content-redirector-selector-container-group:visible").click();
				$("#content-redirector-group-selection #" + container_guid + ":visible").click();			
			}
		} else {
			if(container_guid){
				$("#content-redirector-selector-container-group").addClass("elgg-button-submit");
				$("#content-redirector-group-selection #" + container_guid).addClass("elgg-button-submit");			
			}
		}		
	}
}