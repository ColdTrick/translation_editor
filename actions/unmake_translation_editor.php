<?php
/**
 * Remove the translation role form the provided user
 */

$user_guid = (int) get_input('user');

$user = get_user($user_guid);
if (empty($user)) {
	return elgg_error_response(elgg_echo('translation_editor:action:unmake_translation_editor:error'));
}

unset($user->translation_editor);

return elgg_ok_response('', elgg_echo('translation_editor:action:unmake_translation_editor:success'));
