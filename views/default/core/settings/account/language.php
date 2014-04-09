<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

if ($user = elgg_get_page_owner_entity()) {
	translation_editor_unregister_translations();
	
	$translations = get_installed_translations();
	
	$value = elgg_get_config("language");
	if (!empty($user->language)) {
		$value = $user->language;
	}
	
	if(count($translations ) > 1){
		
		$title = elgg_echo('user:set:language');
		
		$body = elgg_echo('user:language:label');
		$body .= "&nbsp;" . elgg_view("input/dropdown", array('name' => 'language', 'value' => $value, 'options_values' => $translations));
		
		echo elgg_view_module("info", $title, $body);
	} else {
		echo elgg_view("input/hidden", array("name" => "language", "value" => $value));
	}
}