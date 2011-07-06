<?php 

	global $CONFIG;
	
	$search_results = $vars["results"];
	$current_language = $vars["current_language"];

	if(!empty($search_results)){
		$en_flag_file = "mod/translation_editor/_graphics/flags/en.gif";
		
		if(file_exists($CONFIG->path . $en_flag_file)){
			$en_flag = "<img src='" . $vars['url'] . $en_flag_file . "' alt='" . elgg_echo("en") . "' title='" . elgg_echo("en") . "'>";
		} else {
			$en_flag = "en";
		}
		
		$lang_flag_file = "mod/translation_editor/_graphics/flags/" . $current_language . ".gif";
		
		if(file_exists($CONFIG->path . $lang_flag_file)){
			$lang_flag = "<img src='" . $vars['url'] . $lang_flag_file . "' alt='" . elgg_echo($current_language)  . "' title='" . elgg_echo($current_language) . "'>";
		} else {
			$lang_flag = $current_language;
		}
		
		foreach($search_results as $plugin => $data){
			$translated_language = $data["current_language"];
			
			$list .= "<h3 class='settings'>" . $plugin . "</h3>";
			
			$list .= "<table>";
			
			foreach($data["en"] as $key => $value){
				
				// English information
				$list .= "<tr>\n";
				$list .= "<td class='translation_editor_plugin_left'>" . $en_flag . "</td>\n";
				$list .= "<td class='translation_editor_plugin_right'>\n";
				$list .= "<span class='translation_editor_plugin_key' title='" . $key . "'></span>\n";
				$list .= "<pre class='translation_editor_pre'>" . htmlspecialchars($value) . "</pre>\n";
				$list .="</td>\n";
				$list .= "</tr>\n";
				
				// Custom language information
				$list .= "<tr>\n";
				$list .= "<td class='translation_editor_plugin_left'>" . $lang_flag . "</td>\n";
				$list .= "<td class='translation_editor_plugin_right'>\n";
				$list .= "<textarea name='translation[" . $plugin . "][" . $key . "]' onchange='translationEditorJQuerySearchSave();'>";
				$list .= $translated_language[$key];
				$list .= "</textarea>\n";
				$list .= "<br /><br />\n";
				$list .= "</td>\n";
				$list .= "</tr>\n";
			}
			
			$list .= "</table>";
		}
		
		$form_data = elgg_view("input/hidden", array("internalname" => "current_language", "value" => $current_language));
		$form_data .= $list;
		
		$form_data .= "<div>";
		$form_data .= elgg_view("input/submit", array("value" => elgg_echo("save")));
		$form_data .= "</div>";
		
		$list = elgg_view("input/form", array("body" => $form_data,
												"action" => $vars["url"] . "action/translation_editor/translate_search",
												"internalid" => "translation_editor_search_result_form"));
	} else {
		$list .= elgg_echo("translation_editor:search_results:no_results");
	}

?>
<div class="contentWrapper">
	<?php echo $list; ?>
</div>