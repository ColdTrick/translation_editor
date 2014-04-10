<?php
/**
 * Output a country flag
 *
 * @uses $vars['language'] hte language to find the flag for
 */

$language = elgg_extract("language", $vars);

$result = "&nbsp;";
if (!empty($language)) {
	$flag_location = elgg_get_plugins_path() . "translation_editor/_graphics/flags/" . $language . ".gif";
	
	if (file_exists($flag_location)) {
		$result = elgg_view("output/img", array(
			"src" => "mod/translation_editor/_graphics/flags/" . $language . ".gif",
			"alt" => elgg_echo($language),
			"title" => elgg_echo($language)
		));
	}
}

echo $result;