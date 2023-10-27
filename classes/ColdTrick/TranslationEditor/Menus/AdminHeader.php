<?php

namespace ColdTrick\TranslationEditor\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the page menu
 */
class AdminHeader {
	
	/**
	 * Add menu items to the page menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_admin_logged_in()) {
			return null;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor',
			'text' => elgg_echo('translation_editor:menu:title'),
			'href' => elgg_generate_url('default:translation_editor', [
				'current_language' => elgg_get_current_language(),
			]),
			'parent_name' => 'configure',
		]);
		
		return $return;
	}
}
