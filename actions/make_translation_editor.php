<?php
/**
 * give a user the right to modify the translations
 */

$user_guid = (int) get_input('user');

$user = get_user($user_guid);
if (empty($user)) {
	return elgg_ok_response('', elgg_echo('translation_editor:action:make_translation_editor:error'));
}

if (!create_metadata($user_guid, 'translation_editor', true, 'integer', $user_guid, ACCESS_PUBLIC)) {
	return elgg_ok_response('', elgg_echo('translation_editor:action:make_translation_editor:error'));
}

return elgg_ok_response('', elgg_echo('translation_editor:action:make_translation_editor:success'));
