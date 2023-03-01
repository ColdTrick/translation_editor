<?php

namespace ColdTrick\TranslationEditor\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the user_hover menu
 */
class UserHover {
	
	/**
	 * Add menu items to the user_hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:user_hover'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_admin_logged_in()) {
			// only for admins
			return null;
		}
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || $user->isAdmin()) {
			// no user, or user is admin and therefor is already editor
			return null;
		}
		
		$is_editor = translation_editor_is_translation_editor($user->guid);
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor_make_editor',
			'icon' => 'level-up-alt',
			'text' => elgg_echo('translation_editor:action:make_translation_editor'),
			'href' => elgg_generate_action_url('translation_editor/admin/toggle_translation_editor', [
				'user' => $user->guid,
			]),
			'section' => 'admin',
			'item_class' => $is_editor ? 'hidden' : '',
			'priority' => 500,
			'data-toggle' => 'translation-editor-unmake-editor',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation_editor_unmake_editor',
			'icon' => 'level-down-alt',
			'text' => elgg_echo('translation_editor:action:unmake_translation_editor'),
			'href' => elgg_generate_action_url('translation_editor/admin/toggle_translation_editor', [
				'user' => $user->guid,
			]),
			'section' => 'admin',
			'item_class' => $is_editor ? '' : 'hidden',
			'priority' => 501,
			'data-toggle' => 'translation-editor-make-editor',
		]);
				
		return $return;
	}
}
