<?php 

	global $CONFIG;

	$current_language = $vars["current_language"];
	$q = $vars["query"];
	$in_search = $vars["in_search"];
	
	if(empty($q)){
		$q = elgg_echo("translation_editor:forms:search:default");
	}
	
	$back_url = $vars['url'] . "pg/translation_editor/" . $current_language;
	
	// build form
	if(!empty($in_search)){
		$form_data .= "<div>";
		
		// flag
		$lang_flag_file = "mod/translation_editor/_graphics/flags/" . $current_language . ".gif";
		
		if(file_exists($CONFIG->path . $lang_flag_file)){
			$form_data .= "<img src='" . $vars["url"] . $lang_flag_file . "' alt='" . elgg_echo($current_language)  . "' title='" . elgg_echo($current_language) . "' /> ";
		}
		$form_data .= "<b>" . elgg_echo($current_language) . "</b>";
	
		$form_data .= "</div>";
	}
	
	$form_data .= elgg_view("input/text", array("internalname" => "translation_editor_search", "value" => $q));
	$form_data .= elgg_view("input/hidden", array("internalname" => "language", "value" => $current_language));
	
	$form_data .= elgg_view("input/submit", array("value" => elgg_echo("search")));

	$form = elgg_view("input/form", array("body" => $form_data,
											"internalid" => "translation_editor_search_form",
											"action" => $vars["url"] . "pg/translation_editor/search",
											"disable_security" => true));

?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#translation_editor_search_form input[name="translation_editor_search"]').focus(function(){
			if($(this).val() == "<?php echo elgg_echo("translation_editor:forms:search:default"); ?>"){
				$(this).val("");
			}
		}).blur(function(){
			if($(this).val() == ""){
				$(this).val("<?php echo elgg_echo("translation_editor:forms:search:default"); ?>");
			}
		});
	});
</script>

<div class="contentWrapper">
	<?php echo $form; ?>
	
	<?php if($in_search) {?>
	<a href='<?php echo $back_url;?>'>&laquo; <?php echo elgg_echo("translation_editor:plugin_edit:back");?></a>
	<?php } ?>
</div>