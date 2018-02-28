<?php

namespace ColdTrick\TranslationEditor;

/**
 * Add menu items to the page menu
 *
 * @package    ColdTrick
 * @subpackage TranslationEditor
 */
class PageMenu {
	
	/**
	 * Add menu items to the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor',
			'href' => elgg_generate_url('default:translation_editor', [
				'current_language' => get_current_language(),
			]),
			'text' => elgg_echo('translation_editor:menu:title'),
			'parent_name' => 'configure_utilities',
			'section' => 'configure',
		]);
		
		return $return;
	}
}
