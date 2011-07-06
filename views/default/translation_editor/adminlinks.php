<?php 
	if (isadminloggedin()){
		
		if(translation_editor_is_translation_editor($vars["entity"]->getGUID())){
			echo elgg_view("output/confirmlink", array('text' => elgg_echo("translation_editor:action:unmake_translation_editor"), "href" => $vars["url"] . "action/translation_editor/unmake_translation_editor?user=" . $vars['entity']->getGUID()));
		} else {
			echo elgg_view("output/confirmlink", array('text' => elgg_echo("translation_editor:action:make_translation_editor"), "href" => $vars["url"] . "action/translation_editor/make_translation_editor?user=" . $vars["entity"]->getGUID()));
		}
	}
?>