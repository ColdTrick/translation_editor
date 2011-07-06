<?php 
	$plugins = $vars["plugins"];
	$current_language = $vars["current_language"];
	
	if(!empty($plugins)){
		$total = 0;
		$exists = 0; 
		$custom = 0;
		
		$ts = time();
		$token = generate_action_token($ts);
		
		$list .= "<table id='translation_editor_plugin_list'>\n";
		$list .= "<tr>\n";
		$list .= "<th>" . elgg_echo("translation_editor:plugin_list:plugin") . "</th>\n";
		$list .= "<th class='translation_editor_plugin_list_centered'>" . elgg_echo("translation_editor:plugin_list:total") . "</th>\n";
		$list .= "<th class='translation_editor_plugin_list_centered'>" . elgg_echo("translation_editor:plugin_list:exists") . "</th>\n";
		$list .= "<th class='translation_editor_plugin_list_centered'>" . elgg_echo("translation_editor:plugin_list:custom") . "</th>\n";
		$list .= "<th class='translation_editor_plugin_list_centered'>" . elgg_echo("translation_editor:plugin_list:percentage") . "</th>\n";
		$list .= "<th class='translation_editor_plugin_list_centered'>&nbsp;</th>\n";
		$list .= "</tr>\n";
		
		foreach($plugins as $plugin_name => $plugin_stats){
			$url = $vars["url"] . "pg/translation_editor/" . $current_language . "/" . $plugin_name;
			
			$total += $plugin_stats["total"];
			$exists += $plugin_stats["exists"];
			$custom += $plugin_stats["custom"];
			
			if(!empty($plugin_stats["total"])){
				$percentage = round(($plugin_stats["exists"] / $plugin_stats["total"]) * 100);
			} else {
				$percentage = 100;
			}
			
			$complete_class = "";
			
			if($percentage == 100){
				$complete_class = " translation_editor_translation_complete";
			} elseif($percentage == 0){
				$complete_class = " translation_editor_translation_needed";
			}
			
			$list .= "<tr class='translation_editor_plugin_list_row'>\n";
			$list .= "<td><a href='" . $url . "'>" . $plugin_name . "</a></td>\n";
			$list .= "<td class='translation_editor_plugin_list_centered'>" . $plugin_stats["total"] . "</td>\n";
			$list .= "<td class='translation_editor_plugin_list_centered'>" . $plugin_stats["exists"] . "</td>\n";
			
			if($plugin_stats["custom"] > 0){
				$list .= "<td class='translation_editor_plugin_list_centered'>" . $plugin_stats["custom"] . "</td>\n";
			} else {
				$list .= "<td class='translation_editor_plugin_list_centered'>&nbsp;</td>\n";
			}
			
			$list .= "<td class='translation_editor_plugin_list_centered " . $complete_class . "'>" . $percentage . "%</td>\n";
			
			if($plugin_stats["custom"] > 0){
				$merge_url = $vars["url"] . "action/translation_editor/merge?current_language=" . $current_language . "&plugin=" . $plugin_name . "&__elgg_ts=" . $ts . "&__elgg_token=" . $token;
				if(isadminloggedin()){
					$delete_url = $vars["url"] . "action/translation_editor/delete?current_language=" . $current_language . "&plugin=" . $plugin_name . "&__elgg_ts=" . $ts . "&__elgg_token=" . $token;
				}
				
				$list .= "<td class='translation_editor_plugin_list_centered'>";
				$list .= "<span class='translation_editor_plugin_list_merge' title='" . elgg_echo("translation_editor:plugin_list:merge") . "' onclick='document.location.href=\"" . $merge_url . "\"'></span>";
				$list .= "<span class='translation_editor_plugin_list_delete' title='" . elgg_echo("translation_editor:plugin_list:delete") . "' onclick='translation_editor_delete_custom(\"" . $delete_url . "\");'></span>";
				$list .= "</td>\n";
			} else {
				$list .= "<td class='translation_editor_plugin_list_centered'>&nbsp;</td>\n";
			}
			$list .= "</tr>\n";
		}
		
		$list .= "<tr class='translation_editor_plugin_list_total_row'>\n";
		$list .= "<td>&nbsp;</td>\n";
		$list .= "<td class='translation_editor_plugin_list_centered'>" . $total . "</td>\n";
		$list .= "<td class='translation_editor_plugin_list_centered'>" . $exists . "</td>\n";
		$list .= "<td class='translation_editor_plugin_list_centered'>" . $custom . "</td>\n";
		$list .= "<td class='translation_editor_plugin_list_centered'>" . round(($exists / $total) * 100, 2) . "%</td>\n";
		$list .= "</tr>\n";
		
		$list .= "</table>\n";
	} else {
		$list = elgg_echo("translation_editor:plugin_list:error:no_plugins");
	}
?>
<script type="text/javascript">
	function translation_editor_delete_custom(url){
		if(confirm('<?php echo elgg_echo("translation_editor:plugin_list:delete:confirm"); ?>')){
			document.location.href = url;
		}
	}
</script>

<div class="contentWrapper">
	<h3 class="settings"><?php echo elgg_echo("translation_editor:plugin_list:title"); ?></h3>
	<?php echo $list; ?>
</div>