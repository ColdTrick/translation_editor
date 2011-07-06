<?php 
	$current_language = $vars['current_language'];
	$back_url = $vars['url'] . "pg/translation_editor/" . $current_language;
	$english = $vars['translation']['en'];
	$translated_language = $vars['translation']['current_language'];
	$custom = $vars['translation']['custom'];
	
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
	
	$list .= "<table>";
	
	$missing_count = 0;
	$equal_count = 0;
	$custom_count = 0;
	
	foreach($english as $en_key => $en_value){
		
		if(!array_key_exists($en_key, $translated_language)){
			$row_class = "class='translation_editor_missing_translation'";
			$missing_count++;
		} elseif($en_value == $translated_language[$en_key]){
			$row_class = "class='translation_editor_equal_translation'";
			$equal_count++;
		} elseif(array_key_exists($en_key, $custom)){
			$row_class = "class='translation_editor_custom_translation'";
			$custom_count++;
		} else {
			$row_class = "";
		}
		
		// English information
		$list .= "<tr " . $row_class . ">\n";
		$list .= "<td class='translation_editor_plugin_left'>" . $en_flag . "</td>\n";
		$list .= "<td class='translation_editor_plugin_right'>\n";
		$list .= "<span class='translation_editor_plugin_key' title='" . $en_key . "'></span>\n";
		$list .= "<pre class='translation_editor_pre'>" . htmlspecialchars($en_value) . "</pre>\n";
		$list .="</td>\n";
		$list .= "</tr>\n";
		
		// Custom language information
		$list .= "<tr ". $row_class . ">\n";
		$list .= "<td class='translation_editor_plugin_left'>" . $lang_flag . "</td>\n";
		$list .= "<td class='translation_editor_plugin_right'>\n";
		$list .= "<textarea name='translation[" . $en_key . "]' >";
		$list .= $translated_language[$en_key];
		$list .= "</textarea>\n";
		$list .= "<br /><br />\n";
		$list .= "</td>\n";
		$list .= "</tr>\n";
	}
	$list .= "</table>";
	
	$selected_view_mode = "missing";
	
	if($missing_count == 0){
		$selected_view_mode = "all";
?>
<style type="text/css">
	#translation_editor_plugin_form tr {
		display: block;
	}
</style>
<?php 
	}	

?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#translation_editor_plugin_form textarea').live("change", function(){
			translationEditorJQuerySave();
		});
	});
</script>

<div class="contentWrapper">
	<span id='translation_editor_plugin_toggle'>
		<?php 
			echo elgg_echo("translation_editor:plugin_edit:show") . " ";
			
			$missing_class = "";
			$equal_class = "";
			$custom_class = "";
			$all_class = "";
			
			switch($selected_view_mode){
				case "missing":
					$missing_class = "view_mode_active";
					break;
				case "all":
					$all_class = "view_mode_active";
					break;
				case "equal":
					$equal_class = "view_mode_active";
					break;
				case "custom":
					$custom_class = "view_mode_active";
					break;
			}
			
			echo "<a class='$missing_class' id='view_mode_missing' href='javascript:toggleViewMode(\"missing\");'>" . elgg_echo("translation_editor:plugin_edit:show:missing") . "</a> (" . $missing_count . "), ";
			
			echo "<a class='$equal_class' id='view_mode_equal' href='javascript:toggleViewMode(\"equal\");'>" . elgg_echo("translation_editor:plugin_edit:show:equal") . "</a> (" . $equal_count . "), ";
			
			echo "<a class='$custom_class' id='view_mode_custom' href='javascript:toggleViewMode(\"custom\");'>" . elgg_echo("translation_editor:plugin_edit:show:custom") . "</a> (" . $custom_count . "), ";
			
			echo "<a class='$all_class' id='view_mode_all' href='javascript:toggleViewMode(\"all\");'>" . elgg_echo("translation_editor:plugin_edit:show:all") . "</a> (" . $vars['translation']['total'] . ")";
		?>
	</span>
	
	<a href='<?php echo $back_url;?>'>&laquo; <?php echo elgg_echo("translation_editor:plugin_edit:back");?></a>
	
	<h3 class="settings"><?php echo elgg_echo("translation_editor:plugin_edit:title") . " " . $vars['plugin']; ?></h3>
	
	<form id="translation_editor_plugin_form" action="<?php echo $vars['url'];?>action/translation_editor/translate" method="post">
		<?php echo elgg_view("input/securitytoken"); ?>
		<input type='hidden' name='current_language' value='<?php echo $current_language; ?>' />
		<input type='hidden' name='plugin' value='<?php echo $vars['plugin']; ?>' />
		
		<?php echo $list;?>
		
		<?php echo elgg_view("input/submit", array("value" => elgg_echo("save")));?>	
	</form>
</div>