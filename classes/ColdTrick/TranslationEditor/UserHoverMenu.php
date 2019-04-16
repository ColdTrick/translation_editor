<?php

namespace ColdTrick\TranslationEditor;

/**
 * Add menu items to the user_hover menu
 *
 * @package    ColdTrick
 * @subpackage TranslationEditor
 */
class UserHoverMenu {
	
	/**
	 * Add menu items to the usericon dropdown
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in()) {
			// only for admins
			return;
		}
		
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser) {
			// no user
			return;
		}
		
		if ($user->isAdmin()) {
			// user is admin, so is already editor
			return;
		}
		
		$is_editor = translation_editor_is_translation_editor($user->guid);
		
		$return = $hook->getValue();
		
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
