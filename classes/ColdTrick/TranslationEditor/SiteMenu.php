<?php

namespace ColdTrick\TranslationEditor;

/**
 * Add menu items to the site menu
 *
 * @package    ColdTrick
 * @subpackage TranslationEditor
 */
class SiteMenu {
	
	/**
	 * Add menu items to the site menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!translation_editor_is_translation_editor()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor',
			'text' => elgg_echo('translation_editor:menu:title'),
			'href' => elgg_generate_url('default:translation_editor', [
				'current_language' => get_current_language(),
			]),
			'icon' => 'language',
		]);
		
		return $return;
	}
}
