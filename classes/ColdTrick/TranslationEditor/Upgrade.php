<?php

namespace ColdTrick\TranslationEditor;

/**
 * Handle the system upgrade event
 */
class Upgrade {
	
	/**
	 * Cleanup all custom translations from keys not present in the original plugin
	 *
	 * @return void
	 */
	public static function cleanupCustomTranslations(): void {
		$base_dir = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR;
		if (!is_dir($base_dir)) {
			// no custom translations
			return;
		}
		
		$dh = new \DirectoryIterator($base_dir);
		/* @var $language_info \DirectoryIterator */
		foreach ($dh as $language_info) {
			if ($language_info->isDot() || !$language_info->isDir()) {
				continue;
			}
			
			$ldh = new \DirectoryIterator($language_info->getPathname());
			/* @var $plugin_translation \DirectoryIterator */
			foreach ($ldh as $plugin_translation) {
				if (!$plugin_translation->isFile()) {
					continue;
				}
				
				$plugin_id = $plugin_translation->getBasename('.json');
				
				self::cleanupPlugin($language_info->getFilename(), $plugin_id);
			}
			
			// merge new translations for this language
			translation_editor_merge_translations($language_info->getFilename());
		}
	}
	
	/**
	 * Cleanup the custom translations for one plugin
	 *
	 * @param string $language  the language to clean up for
	 * @param string $plugin_id the plugin to clean up
	 *
	 * @return bool
	 */
	protected static function cleanupPlugin(string $language, string $plugin_id): bool {
		if (empty($language) || empty($plugin_id)) {
			return false;
		}
		
		$translation = translation_editor_get_plugin($language, $plugin_id);
		if ($translation === false) {
			return false;
		}
		
		$custom = elgg_extract('custom', $translation);
		if (empty($custom)) {
			// no custom translation, how did you get here??
			return true;
		}
		
		$english = elgg_extract('en', $translation);
		$clean_custom = array_intersect_key($custom, $english);
		$removed_custom = array_diff_key($custom, $clean_custom);
		
		// report cleaned-up translations
		if (empty($removed_custom)) {
			// nothing removed
			return true;
		} else {
			self::writeCleanupTranslations($language, $plugin_id, $removed_custom);
		}
		
		// write new custom translation
		if (empty($clean_custom)) {
			// no more custom translations
			translation_editor_delete_translation($language, $plugin_id);
		} else {
			// write new custom translation
			translation_editor_write_translation($language, $plugin_id, $clean_custom);
		}
		
		return true;
	}
	
	/**
	 * Write the removed custom translations to a file, so admins can act on it
	 *
	 * @param string $language     the language being handled
	 * @param string $plugin_id    the plugin ID
	 * @param array  $translations the removed translations
	 *
	 * @return void
	 */
	protected static function writeCleanupTranslations(string $language, string $plugin_id, array $translations): void {
		if (empty($language) || empty($translations) || empty($translations)) {
			return;
		}
		
		$base_dir = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR;
		$cleanup_file = $base_dir . $language . DIRECTORY_SEPARATOR . 'translation_editor_cleanup.json';
		
		$existing = [];
		if (file_exists($cleanup_file)) {
			// read previous keys
			$contents = file_get_contents($cleanup_file);
			$existing = json_decode($contents, true);
		}
		
		$new_translation = [$plugin_id => $translations];
		
		$new_content = array_merge($existing, $new_translation);
		
		// write new content
		file_put_contents($cleanup_file, json_encode($new_content));
	}
}
