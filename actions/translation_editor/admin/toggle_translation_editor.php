<?php
/**
 * Toggle the translation editor role
 */

$user_guid = (int) get_input('user');

$user = get_user($user_guid);
if (empty($user)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (translation_editor_is_translation_editor($user->guid)) {
	unset($user->translation_editor);
	return elgg_ok_response('', elgg_echo('translation_editor:action:toggle_translation_editor:remove', [$user->getDisplayName()]));
}

$user->translation_editor = true;

return elgg_ok_response('', elgg_echo('translation_editor:action:toggle_translation_editor:make', [$user->getDisplayName()]));
