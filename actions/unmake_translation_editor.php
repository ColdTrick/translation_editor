<?php 

	action_gatekeeper();
	admin_gatekeeper();
	
	$result = false;
	
	$user = get_input("user");
	$role = "translation_editor";
	
	$user = get_entity($user);
	if($user instanceof ElggUser){
		if(remove_metadata($user->guid, $role)){
			$result = true;	
		}
	}

	if(!$result){
		register_error(elgg_echo("translation_editor:action:unmake_translation_editor:error"));
	} else {
		system_message(elgg_echo("translation_editor:action:unmake_translation_editor:success"));
	}
	forward($_SERVER['HTTP_REFERER']);
?>