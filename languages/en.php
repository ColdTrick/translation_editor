<?php

return array(

	// global
	'translation_editor:language' => "Language",
	'translation_editor:language:unsupported' => "Language unsupported",
	'translation_editor:gatekeeper' => "You're not an authorized translation editor",
	
	'translation_editor:exception:plugin_disabled' => "You can not translate a disabled plugin",
	'translation_editor:last_import:actor' => "Last import by: %s",
	
	// upgrades
	'translation_editor:upgrade:2020042401:title' => "Migrate Translation Editor disabled languages",
	'translation_editor:upgrade:2020042401:description' => "Since allowed language management has moved to Elgg core, you might want to migratie the previous Translation Editor setting.",
	'translation_editor:upgrade:2020051801:title' => "Remove remaining custom keys",
	'translation_editor:upgrade:2020051801:description' => "Custom key support was removed in Translation Editor 7.0. This upgrade removes the left over keys.",
	
	// menu
	'translation_editor:menu:title' => "Translation Editor",
	'translation_editor:menu:title:plugin' => "Translating %s to %s",
	'translation_editor:show_language_selector' => "Show language selector",
	'translation_editor:hide_language_selector' => "Hide language selector",
	'translation_editor:snapshots' => "Snapshots",

	// settings
	'translation_editor:settings:remote:title' => "Remote translation settings",
	'translation_editor:settings:remote:description' => "Here you can configure a remote Elgg website as a translation source. You can import custom translations from this remote Elgg website which will overrule the custom translations on this website.
This should mostly be used to synchronize the translations between a test and production server.",
	'translation_editor:settings:remote:error:web_service' => "The Web Services plugin needs te be enabled on this site in order to be able to use remote translation synchronization.",
	'translation_editor:settings:remote:web_service:info' => "Make sure the Translation Editor and Web Services plugin are enabled on the remote website.",
	'translation_editor:settings:remote:host' => "Remote host",
	'translation_editor:settings:remote:host:help' => "Please enter the base URL of the remote Elgg website from which to synchronize the translations (including trailing /)",
	'translation_editor:settings:remote:public_key' => "Public API key",
	'translation_editor:settings:remote:private_key' => "Private API key",
	'translation_editor:settings:remote:private_key:help' => "For improved security during the translation synchronization",
	
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
	'translation_editor:plugin_list:invalid' => "Invalid",
	'translation_editor:plugin_list:percentage' => "Percentage complete",
	
	'translation_editor:plugin_list:garbage' => "The translation contains keys that no longer exist in the English translation",
	'translation_editor:plugin_list:merge' => "Merge to PHP language file",
	
	// export
	'translation_editor:export' => 'Export custom translations',
	'translation_editor:export:no_plugins' => 'No exportable translations found.',
	'translation_editor:export:plugins' => 'Select plugins to export',
	
	// import
	'translation_editor:import:remote:title' => "From remote source",
	'translation_editor:import:file:title' => "From file",
	'translation_editor:import' => 'Import custom translations',
	'translation_editor:import:file' => 'Select importable file from previous export',
	'translation_editor:import:remote:description' => "From here you can import the custom translations from the remote source %s.",
	'translation_editor:import:remote:warning' => "This will override all the custom <b>%s</b> translations with the custom translations from the remote source.",
	'translation_editor:import:remote:plugins' => "Please select the plugins to update",
	'translation_editor:import:remote:plugins:filter' => "Filter specific plugins",
	'translation_editor:import:remote:plugins:all' => "Select all plugins",
	
	// cleanup
	'translation_editor:cleanup:title' => "Translation cleanup",
	'translation_editor:cleanup:description' => "%d custom translations were cleaned-up. This means they could not be found in the plugin they were part of. You can download the list by clicking on the download button.
When you're finished you can delete the file.",
	
	// search
	'translation_editor:search' => "Search results for '%s' in %s",
	'translation_editor:forms:search:default' => "Find a translation",
	'translation_editor:search_results:no_results' => "No translation found",

	'translation_editor:show_original' => "Show original translation",

	// plugin translation
	'translation_editor:plugin_edit:title' => "Edit the translations for plugin:",
	'translation_editor:plugin_edit:show' => "show",
	'translation_editor:plugin_edit:show:missing' => "missing",
	'translation_editor:plugin_edit:show:equal' => "equal",
	'translation_editor:plugin_edit:show:all' => "all",
	'translation_editor:plugin_edit:show:custom' => "custom",
	'translation_editor:plugin_edit:show:params' => "missing params",

	// snapshots
	'translation_editor:snapshots:title' => "Translation snapshots",
	'translation_editor:snapshots:description' => "Here you can create a translation snapshot. A snapshot should be created before (potential) new translations are introduced, for example due to an upgrade of Elgg and or plugins.
After the deployment the snapshot can be compared to the current translations to find out if there are any new or updated translations.",
	'translation_editor:snapshots:create' => "Create new snapshot",
	'translation_editor:snapshots:table:snapshot' => "Snapshot",
	'translation_editor:snapshots:table:actions' => "Actions",
	'translation_editor:snapshots:table:actions:compare' => "Compare",
	
	// compare snapshots
	'translation_editor:compare' => "Compare %s snapshot to %s",
	'translation_editor:compare:no_results' => "No differences found between the current translations and the snapshot.",
	
	// actions
	'translation_editor:action:translate:error:input' => "Incorrect input provided to add a translation",
	'translation_editor:action:translate:error:write' => "Error while writing the translations",
	'translation_editor:action:translate:error:not_authorized' => "You are not authorized to translate",
	'translation_editor:action:translate:success' => "Translations saved successfully",

	'translation_editor:action:make_translation_editor' => "Make Translator",
	'translation_editor:action:unmake_translation_editor' => "UnMake Translator",
	'translation_editor:action:toggle_translation_editor:make' => "%s is now a translator",
	'translation_editor:action:toggle_translation_editor:remove' => "%s is no longer a translator",
	
	'translation_editor:action:delete:error:input' => "Incorrect input to delete translation",
	'translation_editor:action:delete:error:delete' => "Error while deleting translation",
	'translation_editor:action:delete:success' => "Translation successfully deleted",

	'translation_editor:action:add_language:success' => "Language successfully added",
	'translation_editor:action:delete_language:success' => "Language successfully removed",

	'translation_editor:action:import:incorrect_language' => "The import does not contain translations for %s",
	'translation_editor:action:import:no_plugins' => "The import does not contain plugins to be imported",
	'translation_editor:action:import:success' => "Successfully imported translations",

	'translation_editor:action:add_custom_key:success' => "Custom key successfully added",
	'translation_editor:action:add_custom_key:file_error' => "Error when saving the custom key to the file",
	'translation_editor:action:add_custom_key:exists' => "Can't add this key as it already exists. Enter a unique key.",
	'translation_editor:action:add_custom_key:invalid_chars' => "Key contains invalid characters. Only a-z, 0-9, colon or underscore are allowed.",
	'translation_editor:action:add_custom_key:key_numeric' => "Key can not contain only numbers",
	'translation_editor:action:add_custom_key:missing_input' => "Invalid input. Please enter a key and a default (English) translation.",

	'translation_editor:action:cleanup:remove:error:no_file' => "The requested file to delete could not be found",
	'translation_editor:action:cleanup:remove:error:remove' => "An error occurred while deleting the file, please try again",
	'translation_editor:action:cleanup:remove:success' => "The file was deleted",
	
	'translation_editor:action:snapshots:create:success' => "Snapshot successfully created",
	
	'translation_editor:action:snapshot:delete:error' => "Snapshot could not be deleted",
	'translation_editor:action:snapshot:delete:success' => "Snapshot successfully deleted",
	
	'translation_editor:action:remote:error:client' => "An error occurred while creating a remote client, please check your plugin settings",
	'translation_editor:action:remote:error:request' => "An error occurred during the request, check the log files for more information",
	'translation_editor:action:remote:error:result' => "The remote source returned an unknown result",
	'translation_editor:action:remote:success' => "The custom translations have been updated from the remote source",
);
