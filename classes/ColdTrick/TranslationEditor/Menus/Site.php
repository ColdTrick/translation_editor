<?php
namespace ColdTrick\TranslationEditor\Menus;

/**
 * Add menu items to the site menu
 */
class Site {
	
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
				'current_language' => elgg_get_current_language(),
			]),
			'icon' => 'language',
		]);
		
		return $return;
	}
}
