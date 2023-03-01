<?php
namespace ColdTrick\TranslationEditor\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the site menu
 */
class Site {
	
	/**
	 * Add menu items to the site menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:site'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!translation_editor_is_translation_editor()) {
			return null;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor',
			'icon' => 'language',
			'text' => elgg_echo('translation_editor:menu:title'),
			'href' => elgg_generate_url('default:translation_editor', [
				'current_language' => elgg_get_current_language(),
			]),
		]);
		
		return $return;
	}
}
