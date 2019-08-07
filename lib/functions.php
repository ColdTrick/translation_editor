<?php
/**
 * All helper function for this plugin are bundled here
 */

use Elgg\Project\Paths;
use Elgg\Includer;

/**
 * Returns array of all available plugins and their individual language keys
 *
 * @param string $current_language which language to use
 *
 * @return false|array
 */
function translation_editor_get_plugins($current_language) {
	
	if (empty($current_language)) {
		return false;
	}
	
	$translator = elgg()->translator;
	
	$translator->reloadAllTranslations();
	$translator->loadTranslations($current_language);
	
	translation_editor_load_translations($current_language);
	
	$result = [];
	$core = [];
	$plugins_result = [];
	
	$backup_full = $translator->getLoadedTranslations();
	$plugins = elgg_get_plugins();
	
	// Core translation
	$plugin_language = Paths::elgg() . 'languages' . DIRECTORY_SEPARATOR . 'en.php';
	
	if (file_exists($plugin_language)) {
		$plugin_keys = Includer::includeFile($plugin_language);
		
		$key_count = count($plugin_keys);
		
		if (array_key_exists($current_language, $backup_full)) {
			$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
		} else {
			$exists_count = 0;
		}
		
		$custom_content = translation_editor_read_translation($current_language, 'core');
		if (!empty($custom_content)) {
			$custom_count = count($custom_content);
		} else {
			$custom_count = 0;
		}
		
		$core['core']['total'] = $key_count;
		$core['core']['exists'] = $exists_count;
		$core['core']['custom'] = $custom_count;
	}
	
	// Plugin translations
	foreach ($plugins as $plugin) {
		
		$plugin_id = $plugin->getID();
		
		$plugin_language = $plugin->getPath() . 'languages' . DIRECTORY_SEPARATOR . 'en.php';
		
		if (file_exists($plugin_language)) {
			$plugin_keys = Includer::includeFile($plugin_language);
			if (!is_array($plugin_keys)) {
				elgg_log("Please update the language file of '{$plugin_id}' to return an array", 'WARNING');
				continue;
			}
			
			$key_count = count($plugin_keys);
			
			if (array_key_exists($current_language, $backup_full)) {
				$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
			} else {
				$exists_count = 0;
			}
			
			$custom_content = translation_editor_read_translation($current_language, $plugin_id);
			if (!empty($custom_content)) {
				$custom_count = count($custom_content);
			} else {
				$custom_count = 0;
			}
			
			$plugins_result[$plugin_id]['total'] = $key_count;
			$plugins_result[$plugin_id]['exists'] = $exists_count;
			$plugins_result[$plugin_id]['custom'] = $custom_count;
		}
	}
	
	ksort($plugins_result);
	
	$result = $core + $plugins_result;
	
	return $result;
}

/**
 * Returns translation data for a specific plugin
 *
 * @param string $current_language which language to return
 * @param string $plugin           for which plugin do you want the translations
 *
 * @return false|array
 */
function translation_editor_get_plugin($current_language, $plugin) {
	
	if (empty($current_language) || empty($plugin)) {
		return false;
	}

	if ($plugin == 'core') {
		// Core translation
		$plugin_language_path = Paths::elgg() . 'languages' . DIRECTORY_SEPARATOR;
	} else {
		// plugin translations
		$plugin_object = elgg_get_plugin_from_id($plugin);
		if (!($plugin_object instanceof ElggPlugin)) {
			return false;
		}
		$plugin_language_path = $plugin_object->getPath() . 'languages' . DIRECTORY_SEPARATOR;
	}
	
	$translator = elgg()->translator;
	
	$translator->loadTranslations($current_language);
	
	translation_editor_load_translations($current_language);
	
	$result = [
		'total' => 0
	];
	
	$backup_full = $translator->getLoadedTranslations();
	
	// Fetch translations
	if (file_exists("{$plugin_language_path}en.php")) {
		$plugin_keys = Includer::includeFile("{$plugin_language_path}en.php");
		if (!is_array($plugin_keys)) {
			elgg_log("Please update the language file of '{$plugin}' to return an array", 'WARNING');
			return false;
		}
		
		$key_count = count($plugin_keys);
		
		if (array_key_exists($current_language, $backup_full)) {
			$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
		} else {
			$exists_count = 0;
		}
		
		$custom_content = translation_editor_read_translation($current_language, $plugin);
		if (!empty($custom_content)) {
			$custom = $custom_content;
		} else {
			$custom = [];
		}
		
		$result['total'] = $key_count;
		$result['exists'] = $exists_count;
		$result['en'] = $plugin_keys;
		$result['current_language'] = array_intersect_key($backup_full[$current_language], $plugin_keys);
		$result['original_language'] = [];
		if (file_exists("{$plugin_language_path}{$current_language}.php")) {
			$result['original_language'] = Includer::includeFile("{$plugin_language_path}{$current_language}.php");
		}
		$result['custom'] = $custom;
	}
	
	return $result;
}

/**
 * Compare the provided translations to filter out the custom translations
 *
 * @param array $translated      the provided translations
 * @param array $plugin_original the original language keys/values of the plugin
 *
 * @return false|array
 */
function translation_editor_compare_translations(array $translated, array $plugin_original) {
	
	if (empty($translated)) {
		return false;
	}
	
	$result = [];
	
	foreach ($translated as $key => $value) {
		if (elgg_is_empty(trim($value))) {
			// no need to return empty values
			continue;
		}
		
		if (!isset($plugin_original[$key])) {
			// not yet translated
			$result[$key] = $value;
			continue;
		}
		
		$original_value = $plugin_original[$key];
		
		$original = translation_editor_clean_line_breaks(html_entity_decode($original_value, ENT_NOQUOTES, 'UTF-8'));
		$new = translation_editor_clean_line_breaks(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'));
		
		// if original string contains beginning/trailing spaces (eg ' in the group '),
		// don't trim translated
		$trim_needed = (strlen($original) === strlen(trim($original)));
		if ($trim_needed) {
			$new = trim($new);
		}
		
		if (($original != $new) && strlen($new) > 0) {
			$result[$key] = $new;
		}
	}
	
	return $result;
}

/**
 * Replace different line endings with the ones used by the current OS
 *
 * @param string $string the text to replace the line endings in
 *
 * @return string
 */
function translation_editor_clean_line_breaks($string) {
	return preg_replace("/(\r\n)|(\n|\r)/", PHP_EOL, $string);
}

/**
 * Write the custom translation for a plugin to disk
 *
 * @param string $current_language the language for the translations
 * @param string $plugin           the translated plugin
 * @param array  $translation      the translations
 *
 * @return false|int
 */
function translation_editor_write_translation($current_language, $plugin, $translations) {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->saveTranslations($translations);
	} catch (InvalidArgumentException $e) {
		elgg_log($e->getMessage());
	}
	
	return false;
}

/**
 * Read the custom translations from disk
 *
 * @param string $current_language the language to fetch
 * @param string $plugin           the plugin to fetch
 *
 * @return false|array
 */
function translation_editor_read_translation($current_language, $plugin) {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->readTranslations();
	} catch (InvalidArgumentException $e) {
		elgg_log($e->getMessage());
	}
	
	return false;
}

/**
 * Load all the custom translations into the running translations
 *
 * @param string $current_language the language to load (defaults to the language of the current user)
 *
 * @return void
 */
function translation_editor_load_translations($current_language = '') {
	
	if (empty($current_language)) {
		$current_language = get_current_language();
	}
	
	// load translations
	// using elgg_get_system_cache() to bypass enabled setting
	$cache = elgg_get_system_cache();
	$translations = $cache->load("translation_editor_merged_{$current_language}");
	if (!is_array($translations)) {
		// cache was reset rebuild it
		$translations = translation_editor_merge_translations($current_language);
	}
	
	if (!empty($translations)) {
		// need to make sure translations are loaded in order to append/override existing translations
		elgg_language_key_exists('ensure_translations_loaded', $current_language);
		
		add_translation($current_language, $translations);
	}
}

/**
 * Remove the custom translations for a plugin
 *
 * @param string $current_language the language to remove
 * @param string $plugin           the plugin to remove
 *
 * @return bool
 */
function translation_editor_delete_translation($current_language, $plugin) {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->removeTranslations();
	} catch (InvalidArgumentException $e) {
		elgg_log($e->getMessage());
	}
	
	return false;
}

/**
 * Custom version of get_language_completeness() to give better results
 *
 * @see get_language_completeness()
 *
 * @param string $current_language the language to check
 *
 * @return float|false
 */
function translation_editor_get_language_completeness($current_language) {
	
	if (empty($current_language) || ($current_language == 'en')) {
		return false;
	}
	
	$plugins = translation_editor_get_plugins($current_language);
	if (empty($plugins)) {
		return (float) 0;
	}
		
	$english_count = 0;
	$current_count = 0;
	
	foreach ($plugins as $plugin) {
		$english_count += $plugin['total'];
		$current_count += $plugin['exists'];
	}
	
	return round(($current_count / $english_count) * 100, 2);
}

/**
 * Check if the provided user is a translation editor
 *
 * @param int $user_guid the user to check (defaults to current user)
 *
 * @return bool
 */
function translation_editor_is_translation_editor($user_guid = 0) {
	static $editors_cache;
	
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	$user = get_user($user_guid);
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if ($user->isAdmin()) {
		return true;
	}
	
	// preload all editors
	if (!isset($editors_cache)) {
		$editors_cache = [];

		$guids = elgg_get_entities([
			'type' => 'user',
			'limit' => false,
			'metadata_name_value_pairs' => [
				'name' => 'translation_editor',
				'value' => true,
			],
			'callback' => function ($row) {
				return (int) $row->guid;
			},
		]);
		if (!empty($guids)) {
			$editors_cache = $guids;
		}
	}
	
	// is the user an editor or an admin
	return in_array($user_guid, $editors_cache);
}

/**
 * Search for a translation
 *
 * @param string $query    the text to search for
 * @param string $language the language to search in (defaults to English)
 *
 * @return array|bool
 */
function translation_editor_search_translation($query, $language = 'en') {
	
	$plugins = translation_editor_get_plugins($language);
	if (empty($plugins)) {
		return false;
	}
	
	$found = [];
	foreach ($plugins as $plugin => $data) {
		$translations = translation_editor_get_plugin($language, $plugin);
		if (empty($translations) || empty(elgg_extract('total', $translations))) {
			continue;
		}

		foreach ($translations['en'] as $key => $value) {
			if (stristr($key, $query) || stristr($value, $query) || (array_key_exists($key, $translations['current_language']) && stristr($translations['current_language'][$key], $query))) {
				if (!array_key_exists($plugin, $found)) {
					$found[$plugin] = ['en' => [], 'current_language' => []];
				}
				
				$found[$plugin]['en'][$key] = $value;
				if (array_key_exists($key, $translations['current_language'])) {
					$found[$plugin]['current_language'][$key] = $translations['current_language'][$key];
				}
			}
		}
	}
	
	if (empty($found)) {
		return false;
	}

	return $found;
}

/**
 * Merge all custom translations into a single file for performance
 *
 * @param string $language the language to merge
 *
 * @return false|array
 */
function translation_editor_merge_translations($language = '') {
	
	if (empty($language)) {
		$language = get_current_language();
	}
	
	if (empty($language)) {
		return false;
	}
	
	$translations = [];
	
	// get core translations
	$core = translation_editor_read_translation($language, 'core');
	if (!empty($core)) {
		$translations = $core;
	}
		
	// proccess all plugins
	$plugins = elgg_get_plugins();
	if (!empty($plugins)) {
		foreach ($plugins as $plugin) {
			// add plugin translations
			$plugin_translation = translation_editor_read_translation($language, $plugin->getID());
			if (!empty($plugin_translation)) {
				$translations += $plugin_translation;
			}
		}
	}
	
	// write merged to cache
	// using elgg_get_system_cache() to bypass enabled setting
	$cache = elgg_get_system_cache();
	$cache->save("translation_editor_merged_{$language}", $translations);
	
	// clear system cache
	$cache->delete("{$language}.lang");
			
	// let others know this happend
	elgg_trigger_event('language:merge', 'translation_editor', $language);
	
	return $translations;
}

/**
 *  parses a string meant for printf and returns an array of found parameters
 *
 *  @param string $string the string to search parameters for
 *  @param bool   $count  return the count of the parameters (default = true)
 *
 *  @return array
 */
function translation_editor_get_string_parameters($string, $count = true) {
	$valid = '/%[-+]?(?:[ 0]|\'.)?a?\d*(?:\.\d*)?[%bcdeEufFgGosxX]/';
	
	$result = [];
	
	if (!empty($string)) {
		if (!$string = preg_replace('/^[^%]*/', '', $string)) {
			// no results
		} elseif (preg_match_all($valid, $string, $matches)) {
			$result = $matches[0];
		}
	}
	
	if ($count) {
		$result = count($result);
	}
	
	return $result;
}

/**
 * Get the disabled languages
 *
 * @return array
 */
function translation_editor_get_disabled_languages() {
	static $result;

	if (isset($result)) {
		return $result;
	}
		
	$result = [];
		
	$disabled_languages = elgg_get_plugin_setting(TRANSLATION_EDITOR_DISABLED_LANGUAGE, 'translation_editor');
	if (!empty($disabled_languages)) {
		$result = string_to_tag_array($disabled_languages);
	}

	return $result;
}

/**
 * Protect pages for only translation editor
 *
 * @return void
 */
function translation_editor_gatekeeper() {
	elgg_gatekeeper();
	
	if (translation_editor_is_translation_editor()) {
		return;
	}
	
	register_error(elgg_echo('translation_editor:gatekeeper'));
	forward();
}

/**
 * Get available languages on the system
 *
 * Used for caching purpose
 *
 * @see elgg_get_available_languages()
 * @return array
 */
function translation_editor_get_available_languages() {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$result = elgg_get_available_languages();
	
	return $result;
}
