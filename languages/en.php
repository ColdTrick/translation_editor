<?php

return array(
	//'translation_editor' => "Translation Editor",

	// global
	'translation_editor:language' => "Language",
	'translation_editor:gatekeeper' => "You're not an authorized translation editor",
	
	// menu
	'translation_editor:menu:title' => "Translation Editor",
	'translation_editor:menu:title:plugin' => "Translating %s to %s",
	'translation_editor:show_language_selector' => "Show language selector",
	'translation_editor:hide_language_selector' => "Hide language selector",

	// views
	// language selector
	'translation_editor:language_selector:title' => "Select the language you wish to edit",
	'translation_editor:language_selector:add_language' => "Add a new language",
	'translation_editor:language_selector:remove_language:confirm' => "Are you sure you wish to remove this language? You can always add it again!",
	'translation_editor:language_selector:site_language' => "Site language",

	// plugins list
	'translation_editor:plugin_list:title' => "Select a component to translate",
	'translation_editor:plugin_list:plugin' => "Plugin name",
	'translation_editor:plugin_list:total' => "Total keys",
	'translation_editor:plugin_list:exists' => "Translated",
	'translation_editor:plugin_list:custom' => "Custom",
	'translation_editor:plugin_list:percentage' => "Percentage complete",
	
	'translation_editor:plugin_list:merge' => "Merge to PHP language file",
	
	// cleanup
	'translation_editor:cleanup:description' => "%d custom translations were cleaned-up. This means they could not be found in the plugin they were part of. You can download the list by clicking on the Download button.
When you're finished you can %s the file.",
	
	// search
	'translation_editor:search' => "Search results for '%s' in %s",
	'translation_editor:forms:search:default' => "Find a translation",
	'translation_editor:search_results:no_results' => "No translation found",

	// custom key
	'translation_editor:custom_keys:title' => "Add a custom language key",
	'translation_editor:custom_keys:key' => "Key",
	'translation_editor:custom_keys:translation' => "Translation",
	'translation_editor:custom_keys:translation_info' => "New keys will always be created as an English translation. After creation you can translate it to other languages.",

	'translation_editor:plugin_edit:title' => "Edit the translations for plugin:",
	'translation_editor:plugin_edit:show' => "show",
	'translation_editor:plugin_edit:show:missing' => "missing",
	'translation_editor:plugin_edit:show:equal' => "equal",
	'translation_editor:plugin_edit:show:all' => "all",
	'translation_editor:plugin_edit:show:custom' => "custom",
	'translation_editor:plugin_edit:show:params' => "missing params",

	// actions
	'translation_editor:action:translate:error:input' => "Incorrect input provided to add a translation",
	'translation_editor:action:translate:error:write' => "Error while writing the translations",
	'translation_editor:action:translate:error:not_authorized' => "You are not authorized to translate",
	'translation_editor:action:translate:success' => "Translations saved successfully",

	'translation_editor:action:make_translation_editor' => "Make Translator",
	'translation_editor:action:make_translation_editor:success' => "Succesfully made a translator",
	'translation_editor:action:make_translation_editor:error' => "Error while making the user a translator",
	'translation_editor:action:unmake_translation_editor' => "UnMake Translator",
	'translation_editor:action:unmake_translation_editor:success' => "Succesfully removed translator",
	'translation_editor:action:unmake_translation_editor:error' => "Error while removing the translator role",

	'translation_editor:action:delete:error:input' => "Incorrect input to delete translation",
	'translation_editor:action:delete:error:delete' => "Error while deleting translation",
	'translation_editor:action:delete:success' => "Translation successfully deleted",

	'translation_editor:action:add_language:success' => "Language successfully added",
	'translation_editor:action:delete_language:success' => "Language successfully removed",

	'translation_editor:action:add_custom_key:success' => "Custom key successfully added",
	'translation_editor:action:add_custom_key:file_error' => "Error when saving the custom key to the file",
	'translation_editor:action:add_custom_key:exists' => "Can't add this key as it already exists. Enter a unique key.",
	'translation_editor:action:add_custom_key:invalid_chars' => "Key contains invalid characters. Only a-z, 0-9, colon or underscore are allowed.",
	'translation_editor:action:add_custom_key:key_numeric' => "Key can not contain only numbers",
	'translation_editor:action:add_custom_key:missing_input' => "Invalid input. Please enter a key and a default (English) translation.",

	'translation_editor:action:cleanup:remove:error:no_file' => "The requested file to delete could not be found",
	'translation_editor:action:cleanup:remove:error:remove' => "An error occured while deleting the file, please try again",
	'translation_editor:action:cleanup:remove:success' => "The file was deleted",
);
