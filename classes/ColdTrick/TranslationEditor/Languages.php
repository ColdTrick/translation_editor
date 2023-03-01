<?php

namespace ColdTrick\TranslationEditor;

/**
 * Language related functions
 */
class Languages {
	
	/**
	 * Add languages to the languagelist
	 *
	 * @param \Elgg\Event $event 'languages', 'translations'
	 *
	 * @return null|array
	 */
	public static function registerCustomLanguages(\Elgg\Event $event): ?array {
		$return = $event->getValue();
		
		$custom_languages = elgg_get_plugin_setting('custom_languages', 'translation_editor');
		if (empty($custom_languages)) {
			return null;
		}
		
		$custom_languages = explode(',', $custom_languages);
		foreach ($custom_languages as $lang) {
			if (in_array($lang, $return)) {
				continue;
			}
			
			$return[] = $lang;
		}
		
		return $return;
	}
}
