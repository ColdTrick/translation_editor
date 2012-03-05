<?php 
	
	if (isadminloggedin()){
		$user = $vars["entity"];
		
		if(!$user->isAdmin()){
			if(translation_editor_is_translation_editor($user->getGUID())){
				echo elgg_view("output/confirmlink", array('text' => elgg_echo("translation_editor:action:unmake_translation_editor"), "href" => $vars["url"] . "action/translation_editor/unmake_translation_editor?user=" . $user->getGUID()));
			} else {
				echo elgg_view("output/confirmlink", array('text' => elgg_echo("translation_editor:action:make_translation_editor"), "href" => $vars["url"] . "action/translation_editor/make_translation_editor?user=" . $user->getGUID()));
			}
		}
	}
	