<?php

	function translation_editor_actions_hook($hook, $type, $returnvalue, $params){
		global $CONFIG;
		
		$allowed_actions = array(
			"admin/plugins/reorder",
			"admin/plugins/enable",
			"admin/plugins/disable",
			"admin/plugins/enableall",
			"admin/plugins/disableall"
		);
		
		if((!empty($type) && in_array($type, $allowed_actions)) || (defined("upgrading") && (upgrading == "upgrading"))){
			// make sure we have all translations
			translation_editor_reload_all_translations();
			
			// reset all times
			if($languages = get_installed_translations()){
				foreach($languages as $key => $desc){
					remove_private_setting($CONFIG->site_guid, "te_last_update_" . $key);
				}
			}
		}
	}