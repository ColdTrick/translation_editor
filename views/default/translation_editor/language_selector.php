<?php 

	global $CONFIG;

	$languages = $vars["languages"];
	$current_language = $vars["current_language"];
	$plugin = $vars["plugin"];
	$disabled_languages = $vars["disabled_languages"];
	$site_language = $vars["site_language"];
	
	if(!empty($languages)){
		$list = "<table id='translation_editor_language_table'>\n";
		$list .= "<tr>\n";
		$list .= "<th class='translation_editor_flag'>&nbsp;</th>\n";
		$list .= "<th>" . elgg_echo("translation_editor:language") . "</th>\n";
		if(isadminloggedin()){
			$list .= "<th class='translation_editor_enable'>" . elgg_echo("translation_editor:disabled") . "</th>\n";
		}
		$list .= "</tr>\n";
		
		foreach($languages as $language){
			$list .= "<tr>\n";
			
			// flag
			$lang_flag_file = "mod/translation_editor/_graphics/flags/" . $language . ".gif";
	
			if(file_exists($CONFIG->path . $lang_flag_file)){
				$list .= "<td class='translation_editor_flag'>"; 
				$list .= "<img src='" . $vars['url'] . $lang_flag_file . "' alt='" . elgg_echo($language)  . "' title='" . elgg_echo($language) . "'> ";
				$list .= "</td>\n";
			} else {
				$list .= "<td class='translation_editor_flag'>&nbsp;</td>\n";
			}
			
			// language
			$list .= "<td>";
			if($language != $current_language){
				$url = $vars["url"] . "pg/translation_editor/" . $language . "/" . $plugin;
				
				if($language != "en"){
					$completeness = translation_editor_get_language_completeness($language); 
					$list .= "<a href='" . $url . "'>" . elgg_echo($language) . " (" . $completeness . "%)</a>";
					
					if(isadminloggedin() && $completeness == 0){
						$list .= elgg_view("output/confirmlink", array("href" => $vars["url"] . "action/translation_editor/delete_language?language=" . $language, "class" => "translation_editor_delete_language", "confirm" => elgg_echo("translation_editor:language_selector:remove_language:confirm")));
					}
				} else {
					$list .= "<a href='" . $url . "'>" . elgg_echo($language) . "</a>";
					
				}
			} else {
				if($language != "en"){
					$list .= elgg_echo($language) . " (" . translation_editor_get_language_completeness($language) . "%)";
				} else {
					$list .= elgg_echo($language);
				}
			}
			
			if($site_language == $language){
				$list .= "<span id='translation_editor_site_language'>" . elgg_echo("translation_editor:language_selector:site_language") . "</span>";
			}
			$list .= "</td>\n";
			
			// checkbox
			if(isadminloggedin()){
				$list .= "<td class='translation_editor_enable'>";
				if($language != "en"){
					$list .= "<input type='checkbox' name='disabled_languages[]' value='" . $language . "' onchange='translation_editor_disable_language();' ";
					if(in_array($language, $disabled_languages)){
						$list .= "checked='checked' ";
					}
					$list .= "/>";
				}
				$list .= "</td>\n";
			}
			
			$list .= "</tr>\n";
		}
		
		if(isadminloggedin()){
			$list .= "<tr>\n";
			$list .= "<td colspan='3'>";
			$list .= elgg_view("input/securitytoken");
			$list .= elgg_view("input/hidden", array("internalname" => "action", "value" => $vars["url"] . "action/translation_editor/disable_languages"));
			$list .= "</td>";
			$list .= "</tr>\n";
		}
		
		$list .= "</table>\n";
	} else {
		$list = elgg_echo("translation_editor:language_selector:error:no_languages");
	}
	
	if(isadminloggedin()){
		// add a new language
		$list .= "<div id='translation_editor_add_language'>";
		$list .= "<a href='javascript:void(0);' onclick='$(\"#translation_editor_add_language_form\").toggle();'><b>+</b> " . elgg_echo("translation_editor:language_selector:add_language") . "</a>";
		$list .= elgg_view("translation_editor/add_language", array("internalid" => "translation_editor_add_language_form"));
		
		$list .= "</div>";
	}
	
?>
<div class="contentWrapper">
	<h3 class="settings"><?php echo elgg_echo("translation_editor:language_selector:title"); ?></h3>
	<?php echo $list; ?>
</div>